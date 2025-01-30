<?php

/**
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * Flat sales order item resource
 *
 * @category   Mage
 * @package    Mage_Sales
 */
class Mage_Sales_Model_Resource_Order_Item extends Mage_Sales_Model_Resource_Order_Abstract
{
    /**
     * @var string
     */
    protected $_eventPrefix    = 'sales_order_item_resource';

    protected function _construct()
    {
        $this->_init('sales/order_item', 'item_id');
    }
}
