<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml transaction details grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Block_Sales_Transactions_Detail_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize default sorting and html ID
     */
    protected function _construct()
    {
        $this->setId('transactionDetailsGrid');
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = new Varien_Data_Collection();
        foreach ($this->getTransactionAdditionalInfo() as $key => $value) {
            $data = new Varien_Object(['key' => $key, 'value' => $value]);
            $collection->addItem($data);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('key', [
            'header'    => Mage::helper('sales')->__('Key'),
            'index'     => 'key',
            'sortable'  => false,
            'type'      => 'text',
            'width'     => '50%',
        ]);

        $this->addColumn('value', [
            'header'    => Mage::helper('sales')->__('Value'),
            'index'     => 'value',
            'sortable'  => false,
            'type'      => 'text',
            'escape'    => true,
        ]);

        return parent::_prepareColumns();
    }

    /**
     * Retrieve Transaction additional info
     *
     * @return array
     */
    public function getTransactionAdditionalInfo()
    {
        $info = Mage::registry('current_transaction')->getAdditionalInformation(
            Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,
        );
        return (is_array($info)) ? $info : [];
    }
}
