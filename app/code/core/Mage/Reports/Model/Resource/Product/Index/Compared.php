<?php

/**
 * @category   Mage
 * @package    Mage_Reports
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Reports Compared Product Index Resource Model
 *
 * @category   Mage
 * @package    Mage_Reports
 */
class Mage_Reports_Model_Resource_Product_Index_Compared extends Mage_Reports_Model_Resource_Product_Index_Abstract
{
    protected function _construct()
    {
        $this->_init('reports/compared_product_index', 'index_id');
    }
}
