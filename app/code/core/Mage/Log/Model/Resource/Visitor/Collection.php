<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Log
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2019-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Visitor log collection
 *
 * @category   Mage
 * @package    Mage_Log
 */
class Mage_Log_Model_Resource_Visitor_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Visitor data table name
     *
     * @var string
     */
    protected $_visitorTable;

    /**
     * Visitor data info table name
     *
     * @var string
     */
    protected $_visitorInfoTable;

    /**
     * Customer data table
     *
     * @var string
     */
    protected $_customerTable;

    /**
     * Log URL data table name.
     *
     * @var string
     */
    protected $_urlTable;

    /**
     * Log URL expanded data table name.
     *
     * @var string
     */
    protected $_urlInfoTable;

    /**
     * Aggregator data table.
     *
     * @var string
     */
    protected $_summaryTable;

    /**
     * Aggregator type data table.
     *
     * @var string
     */
    protected $_summaryTypeTable;

    /**
     * Quote data table.
     *
     * @var string
     */
    protected $_quoteTable;

    /**
     * Online filter used flag
     *
     * @var bool
     */
    protected $_isOnlineFilterUsed = false;

    /**
     * Field map
     *
     * @var array
     */
    protected $_fieldMap = [
        'customer_firstname'  => 'customer_firstname_table.value',
        'customer_middlename' => 'customer_middlename_table.value',
        'customer_lastname'   => 'customer_lastname_table.value',
        'customer_email'      => 'customer_email_table.email',
        'customer_id'         => 'customer_table.customer_id',
        'url'                 => 'url_info_table.url',
    ];

    /**
     * Collection resource initialization
     */
    protected function _construct()
    {
        $this->_init('log/visitor');

        $this->_visitorTable     = $this->getTable('log/visitor');
        $this->_visitorInfoTable = $this->getTable('log/visitor_info');
        $this->_urlTable         = $this->getTable('log/url_table');
        $this->_urlInfoTable     = $this->getTable('log/url_info_table');
        $this->_customerTable    = $this->getTable('log/customer');
        $this->_summaryTable     = $this->getTable('log/summary_table');
        $this->_summaryTypeTable = $this->getTable('log/summary_type_table');
        $this->_quoteTable       = $this->getTable('log/quote_table');
    }

    /**
     * Filter for customers only
     *
     * @return $this
     */
    public function showCustomersOnly()
    {
        $this->getSelect()
            ->where('customer_table.customer_id > 0')
            ->group('customer_table.customer_id');

        return $this;
    }

    /**
     * Get GROUP BY date format
     *
     * @deprecated since 1.5.0.0
     * @param string $type
     * @return string
     */
    protected function _getGroupByDateFormat($type)
    {
        return match ($type) {
            'day' => '%Y-%m-%d',
            default => '%Y-%m-%d %H',
        };
    }

    /**
     * Get range by type
     *
     * @deprecated since 1.5.0.0
     * @param string $typeCode
     * @return string
     */
    protected function _getRangeByType($typeCode)
    {
        return match ($typeCode) {
            'day' => 'DAY',
            'hour' => 'HOUR',
            default => 'MINUTE',
        };
    }

    /**
     * Filter by customer ID, as 'type' field does not exist
     *
     * @inheritDoc
     */
    public function addFieldToFilter($fieldName, $condition = null)
    {
        if ($fieldName == 'type' && is_array($condition) && isset($condition['eq'])) {
            $fieldName = 'customer_id';
            if ($condition['eq'] === Mage_Log_Model_Visitor::VISITOR_TYPE_VISITOR) {
                $condition = ['null' => 1];
            } else {
                $condition = ['moreq' => 1];
            }
        }
        return parent::addFieldToFilter($this->_getFieldMap($fieldName), $condition);
    }

    /**
     * Return field with table prefix
     *
     * @param string $fieldName
     * @return string
     */
    protected function _getFieldMap($fieldName)
    {
        return $this->_fieldMap[$fieldName] ?? ('main_table.' . $fieldName);
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        Mage::dispatchEvent('log_visitor_collection_load_before', ['collection' => $this]);
        return parent::load($printQuery, $logQuery);
    }

    /**
     * Return true if online filter used
     *
     * @return bool
     */
    public function getIsOnlineFilterUsed()
    {
        return $this->_isOnlineFilterUsed;
    }

    /**
     * Filter visitors by specified store ids
     *
     * @param array|int $storeIds
     */
    public function addVisitorStoreFilter($storeIds)
    {
        $this->getSelect()->where('visitor_table.store_id IN (?)', $storeIds);
    }
}
