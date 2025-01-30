<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * Adminhtml source reports event store filter
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_System_Config_Source_Reports_Scope
{
    public function toOptionArray()
    {
        return [
            ['value' => 'website', 'label' => Mage::helper('adminhtml')->__('Website')],
            ['value' => 'group', 'label' => Mage::helper('adminhtml')->__('Store')],
            ['value' => 'store', 'label' => Mage::helper('adminhtml')->__('Store View')],
        ];
    }
}
