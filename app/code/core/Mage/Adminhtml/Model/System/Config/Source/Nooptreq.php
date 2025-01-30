<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_System_Config_Source_Nooptreq
{
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => Mage::helper('adminhtml')->__('No')],
            ['value' => 'opt', 'label' => Mage::helper('adminhtml')->__('Optional')],
            ['value' => 'req', 'label' => Mage::helper('adminhtml')->__('Required')],
        ];
    }
}
