<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2019-2025 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog Layer Price Filter resource model
 *
 * @category   Mage
 * @package    Mage_Catalog
 */
class Mage_Catalog_Model_Resource_Layer_Filter_Price extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Minimal possible price
     */
    public const MIN_POSSIBLE_PRICE = .01;

    protected function _construct()
    {
        $this->_init('catalog/product_index_price', 'entity_id');
    }

    /**
     * Retrieve joined price index table alias
     *
     * @return string
     */
    protected function _getIndexTableAlias()
    {
        return 'price_index';
    }

    /**
     * Replace table alias in condition string
     *
     * @param string|null $conditionString
     * @return string|null
     */
    protected function _replaceTableAlias($conditionString)
    {
        if (is_null($conditionString)) {
            return null;
        }
        $adapter = $this->_getReadAdapter();
        $oldAlias = [
            Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS . '.',
            $adapter->quoteIdentifier(Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS) . '.',
        ];
        $newAlias = [
            Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.',
            $adapter->quoteIdentifier(Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS) . '.',
        ];
        return str_replace($oldAlias, $newAlias, $conditionString);
    }

    /**
     * Retrieve clean select with joined price index table
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return Varien_Db_Select
     */
    protected function _getSelect($filter)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $collection->addPriceData($filter->getCustomerGroupId(), $filter->getWebsiteId());

        if (!is_null($collection->getCatalogPreparedSelect())) {
            $select = clone $collection->getCatalogPreparedSelect();
        } else {
            $select = clone $collection->getSelect();
        }

        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);

        // remove join with main table
        $fromPart = $select->getPart(Zend_Db_Select::FROM);
        if (!isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS])
            || !isset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS])
        ) {
            return $select;
        }

        // processing FROM part
        $priceIndexJoinPart = $fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS];
        $priceIndexJoinConditions = explode('AND', $priceIndexJoinPart['joinCondition']);
        $priceIndexJoinPart['joinType'] = Zend_Db_Select::FROM;
        $priceIndexJoinPart['joinCondition'] = null;
        $fromPart[Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS] = $priceIndexJoinPart;
        unset($fromPart[Mage_Catalog_Model_Resource_Product_Collection::INDEX_TABLE_ALIAS]);
        $select->setPart(Zend_Db_Select::FROM, $fromPart);
        foreach ($fromPart as $key => $fromJoinItem) {
            $fromPart[$key]['joinCondition'] = $this->_replaceTableAlias($fromJoinItem['joinCondition']);
        }
        $select->setPart(Zend_Db_Select::FROM, $fromPart);

        // processing WHERE part
        $wherePart = $select->getPart(Zend_Db_Select::WHERE);
        $excludedWherePart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.status';
        foreach ($wherePart as $key => $wherePartItem) {
            if (str_contains($wherePartItem, $excludedWherePart)) {
                $wherePart[$key] = new Zend_Db_Expr('1=1');
                continue;
            }
            $wherePart[$key] = $this->_replaceTableAlias($wherePartItem);
        }
        $select->setPart(Zend_Db_Select::WHERE, $wherePart);
        $excludeJoinPart = Mage_Catalog_Model_Resource_Product_Collection::MAIN_TABLE_ALIAS . '.entity_id';
        foreach ($priceIndexJoinConditions as $condition) {
            if (str_contains($condition, $excludeJoinPart)) {
                continue;
            }
            $select->where($this->_replaceTableAlias($condition));
        }
        $select->where($this->_getPriceExpression($filter, $select) . ' IS NOT NULL');

        return $select;
    }

    /**
     * Prepare response object and dispatch prepare price event
     * Return response object
     *
     * @deprecated since 1.7.0.0
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param Varien_Db_Select $select
     * @return Varien_Object
     */
    protected function _dispatchPreparePriceEvent($filter, $select)
    {
        // prepare response object for event
        $response = new Varien_Object();
        $response->setAdditionalCalculations([]);

        // prepare event arguments
        $eventArgs = [
            'select'          => $select,
            'table'           => $this->_getIndexTableAlias(),
            'store_id'        => $filter->getStoreId(),
            'response_object' => $response,
        ];

        /**
         * @deprecated since 1.3.2.2
         */
        Mage::dispatchEvent('catalogindex_prepare_price_select', $eventArgs);

        /**
         * @since 1.4
         */
        Mage::dispatchEvent('catalog_prepare_price_select', $eventArgs);

        return $response;
    }

    /**
     * Retrieve maximal price for attribute
     *
     * @deprecated since 1.7.0.0
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return float
     */
    public function getMaxPrice($filter)
    {
        return $filter->getLayer()->getProductCollection()->getMaxPrice();
    }

    /**
     * Price expression generated by products collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param Varien_Db_Select $select
     * @param bool $replaceAlias
     * @return string
     */
    protected function _getPriceExpression($filter, $select, $replaceAlias = true)
    {
        $priceExpression = $filter->getLayer()->getProductCollection()->getPriceExpression($select);
        $additionalPriceExpression = $filter->getLayer()->getProductCollection()->getAdditionalPriceExpression($select);
        $result = empty($additionalPriceExpression)
            ? $priceExpression
            : "({$priceExpression} {$additionalPriceExpression})";
        if ($replaceAlias) {
            $result = $this->_replaceTableAlias($result);
        }

        return $result;
    }

    /**
     * Get comparing value sql part
     *
     * @param float $price
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param bool $decrease
     * @return string
     */
    protected function _getComparingValue($price, $filter, $decrease = true)
    {
        $currencyRate = $filter->getLayer()->getProductCollection()->getCurrencyRate();
        if ($decrease) {
            $result = ($price - (self::MIN_POSSIBLE_PRICE / 2)) / $currencyRate;
        } else {
            $result = ($price + (self::MIN_POSSIBLE_PRICE / 2)) / $currencyRate;
        }
        return sprintf('%F', $result);
    }

    /**
     * Get full price expression generated by products collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param Varien_Db_Select $select
     * @return Zend_Db_Expr
     */
    protected function _getFullPriceExpression($filter, $select)
    {
        return new Zend_Db_Expr('ROUND((' . $this->_getPriceExpression($filter, $select) . ') * '
            . $filter->getLayer()->getProductCollection()->getCurrencyRate() . ', 2)');
    }

    /**
     * Retrieve array with products counts per price range
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $range
     * @return array
     */
    public function getCount($filter, $range)
    {
        $select = $this->_getSelect($filter);
        $priceExpression = $this->_getFullPriceExpression($filter, $select);

        /**
         * Check and set correct variable values to prevent SQL-injections
         */
        $range = (float) $range;
        if ($range == 0) {
            $range = 1;
        }
        $countExpr = new Zend_Db_Expr('COUNT(*)');
        $rangeExpr = new Zend_Db_Expr("FLOOR(({$priceExpression}) / {$range}) + 1");
        $rangeOrderExpr = new Zend_Db_Expr("FLOOR(({$priceExpression}) / {$range}) + 1 ASC");

        $select->columns([
            'range' => $rangeExpr,
            'count' => $countExpr,
        ]);
        $select->group($rangeExpr)->order($rangeOrderExpr);

        return $this->_getReadAdapter()->fetchPairs($select);
    }

    /**
     * Apply attribute filter to product collection
     *
     * @deprecated since 1.7.0.0
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $range
     * @param int $index    the range factor
     * @return $this
     */
    public function applyFilterToCollection($filter, $range, $index)
    {
        $select = $filter->getLayer()->getProductCollection()->getSelect();
        $priceExpr = $this->_getPriceExpression($filter, $select);
        $filter->getLayer()->getProductCollection()
            ->getSelect()
            ->where($priceExpr . ' >= ' . $this->_getComparingValue(($range * ($index - 1)), $filter))
            ->where($priceExpr . ' < ' . $this->_getComparingValue(($range * $index), $filter));

        return $this;
    }

    /**
     * Load range of product prices
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $limit
     * @param int|null $offset
     * @param float|null $lowerPrice
     * @param float|null $upperPrice
     * @return array
     */
    public function loadPrices($filter, $limit, $offset = null, $lowerPrice = null, $upperPrice = null)
    {
        $select = $this->_getSelect($filter);
        $priceExpression = $this->_getPriceExpression($filter, $select);
        $select->columns([
            'min_price_expr' => $this->_getFullPriceExpression($filter, $select),
        ]);
        if (!is_null($lowerPrice)) {
            $select->where("$priceExpression >= " . $this->_getComparingValue($lowerPrice, $filter));
        }
        if (!is_null($upperPrice)) {
            $select->where("$priceExpression < " . $this->_getComparingValue($upperPrice, $filter));
        }
        $select->order(new Zend_Db_Expr("$priceExpression ASC"))->limit($limit, $offset);

        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Load range of product prices, preceding the price
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param float $price
     * @param int $index
     * @param float|null $lowerPrice
     * @return array|false
     */
    public function loadPreviousPrices($filter, $price, $index, $lowerPrice = null)
    {
        $select = $this->_getSelect($filter);
        $priceExpression = $this->_getPriceExpression($filter, $select);
        $select->columns('COUNT(*)')->where("$priceExpression < " . $this->_getComparingValue($price, $filter));
        if (!is_null($lowerPrice)) {
            $select->where("$priceExpression >= " . $this->_getComparingValue($lowerPrice, $filter));
        }
        $offset = $this->_getReadAdapter()->fetchOne($select);
        if (!$offset) {
            return false;
        }

        return $this->loadPrices($filter, $index - $offset + 1, $offset - 1, $lowerPrice);
    }

    /**
     * Load range of product prices, next to the price
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param float $price
     * @param int $rightIndex
     * @param float|null $upperPrice
     * @return array|false
     */
    public function loadNextPrices($filter, $price, $rightIndex, $upperPrice = null)
    {
        $select = $this->_getSelect($filter);

        $pricesSelect = clone $select;
        $priceExpression = $this->_getPriceExpression($filter, $pricesSelect);

        $select->columns('COUNT(*)')->where("$priceExpression > " . $this->_getComparingValue($price, $filter, false));
        if (!is_null($upperPrice)) {
            $select->where("$priceExpression < " . $this->_getComparingValue($upperPrice, $filter));
        }
        $offset = $this->_getReadAdapter()->fetchOne($select);
        if (!$offset) {
            return false;
        }

        $pricesSelect
            ->columns([
                'min_price_expr' => $this->_getFullPriceExpression($filter, $pricesSelect),
            ])
            ->where("$priceExpression >= " . $this->_getComparingValue($price, $filter));
        if (!is_null($upperPrice)) {
            $pricesSelect->where("$priceExpression < " . $this->_getComparingValue($upperPrice, $filter));
        }
        $pricesSelect->order(new Zend_Db_Expr("$priceExpression DESC"))->limit($rightIndex - $offset + 1, $offset - 1);

        return array_reverse($this->_getReadAdapter()->fetchCol($pricesSelect));
    }

    /**
     * Apply price range filter to product collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @return $this
     */
    public function applyPriceRange($filter)
    {
        $interval = $filter->getInterval();
        if (!$interval) {
            return $this;
        }

        [$from, $to] = $interval;
        if ($from === '' && $to === '') {
            return $this;
        }

        $select = $filter->getLayer()->getProductCollection()->getSelect();
        $priceExpr = $this->_getPriceExpression($filter, $select, false);

        if ($to !== '') {
            $to = (float) $to;
            if ($from == $to) {
                $to += self::MIN_POSSIBLE_PRICE;
            }
        }

        if ($from !== '') {
            $select->where($priceExpr . ' >= ' . $this->_getComparingValue($from, $filter));
        }
        if ($to !== '') {
            $select->where($priceExpr . ' < ' . $this->_getComparingValue($to, $filter));
        }

        return $this;
    }
}
