<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Configuration source model for Wysiwyg toggling
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_System_Config_Source_Cms_Wysiwyg_Enabled
{
    public function toOptionArray()
    {
        return [
            [
                'value' => Mage_Cms_Model_Wysiwyg_Config::WYSIWYG_ENABLED,
                'label' => Mage::helper('cms')->__('Enabled by Default'),
            ],
            [
                'value' => Mage_Cms_Model_Wysiwyg_Config::WYSIWYG_HIDDEN,
                'label' => Mage::helper('cms')->__('Disabled by Default'),
            ],
            [
                'value' => Mage_Cms_Model_Wysiwyg_Config::WYSIWYG_DISABLED,
                'label' => Mage::helper('cms')->__('Disabled Completely'),
            ],
        ];
    }
}
