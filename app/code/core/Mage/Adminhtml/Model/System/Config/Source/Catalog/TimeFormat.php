<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_System_Config_Source_Catalog_TimeFormat
{
    public function toOptionArray()
    {
        return [
            ['value' => '12h', 'label' => Mage::helper('adminhtml')->__('12h AM/PM')],
            ['value' => '24h', 'label' => Mage::helper('adminhtml')->__('24h')],
        ];
    }
}
