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
 * Catalog Layer Decimal Attribute Filter Model
 *
 * @category   Mage
 * @package    Mage_Catalog
 *
 * @method $this setRange(int $value)
 */
class Mage_Catalog_Model_Layer_Filter_Decimal extends Mage_Catalog_Model_Layer_Filter_Abstract
{
    public const MIN_RANGE_POWER = 10;

    /**
     * Resource instance
     *
     * @var Mage_Catalog_Model_Resource_Layer_Filter_Decimal|null
     */
    protected $_resource;

    /**
     * Initialize filter and define request variable
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'decimal';
    }

    /**
     * Retrieve resource instance
     *
     * @return Mage_Catalog_Model_Resource_Layer_Filter_Decimal
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('catalog/layer_filter_decimal');
        }
        return $this->_resource;
    }

    /**
     * Apply decimal range filter to product collection
     *
     * @param Mage_Catalog_Block_Layer_Filter_Decimal $filterBlock
     * @return $this
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        parent::apply($request, $filterBlock);

        /**
         * Filter must be string: $index, $range
         */
        $filter = $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $this;
        }

        $filter = explode(',', $filter);
        if (count($filter) != 2) {
            return $this;
        }

        [$index, $range] = $filter;
        if ((int) $index && (int) $range) {
            $this->setRange((int) $range);

            $this->_getResource()->applyFilterToCollection($this, $range, $index);
            $this->getLayer()->getState()->addFilter(
                $this->_createItem($this->_renderItemLabel($range, $index), $filter),
            );

            $this->_items = [];
        }

        return $this;
    }

    /**
     * Retrieve price aggreagation data cache key
     *
     * @return string
     */
    protected function _getCacheKey()
    {
        return $this->getLayer()->getStateKey()
            . '_ATTR_' . $this->getAttributeModel()->getAttributeCode();
    }

    /**
     * Prepare text of item label
     *
     * @param   int $range
     * @param   float $value
     * @return  string
     */
    protected function _renderItemLabel($range, $value)
    {
        $from   = Mage::app()->getStore()->formatPrice(($value - 1) * $range, false);
        $to     = Mage::app()->getStore()->formatPrice($value * $range, false);
        return Mage::helper('catalog')->__('%s - %s', $from, $to);
    }

    /**
     * Retrieve maximum value from layer products set
     *
     * @return float
     */
    public function getMaxValue()
    {
        $max = $this->getData('max_value');
        if (is_null($max)) {
            [$min, $max] = $this->_getResource()->getMinMax($this);
            $this->setData('max_value', $max);
            $this->setData('min_value', $min);
        }
        return $max;
    }

    /**
     * Retrieve minimal value from layer products set
     *
     * @return float
     */
    public function getMinValue()
    {
        $min = $this->getData('min_value');
        if (is_null($min)) {
            [$min, $max] = $this->_getResource()->getMinMax($this);
            $this->setData('max_value', $max);
            $this->setData('min_value', $min);
        }
        return $min;
    }

    /**
     * Retrieve range for building filter steps
     *
     * @return int
     */
    public function getRange()
    {
        $range = $this->getData('range');
        if (!$range) {
            $maxValue = $this->getMaxValue();
            $index = 1;
            do {
                $range = 10 ** (strlen(floor($maxValue)) - $index);
                $items = $this->getRangeItemCounts($range);
                $index++;
            } while ($range > self::MIN_RANGE_POWER && count($items) < 2);
            $this->setData('range', $range);
        }

        return $range;
    }

    /**
     * Retrieve information about products count in range
     *
     * @param int $range
     * @return array
     */
    public function getRangeItemCounts($range)
    {
        $rangeKey = 'range_item_counts_' . $range;
        $items = $this->getData($rangeKey);
        if (is_null($items)) {
            $items = $this->_getResource()->getCount($this, $range);
            $this->setData($rangeKey, $items);
        }
        return $items;
    }

    /**
     * Retrieve data for build decimal filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $key = $this->_getCacheKey();

        $data = $this->getLayer()->getAggregator()->getCacheData($key);
        if ($data === null) {
            $data       = [];
            $range      = $this->getRange();
            $dbRanges   = $this->getRangeItemCounts($range);

            foreach ($dbRanges as $index => $count) {
                $data[] = [
                    'label' => $this->_renderItemLabel($range, $index),
                    'value' => $index . ',' . $range,
                    'count' => $count,
                ];
            }
        }
        return $data;
    }
}
