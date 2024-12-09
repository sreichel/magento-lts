<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Paypal
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 * @category   Mage
 * @package    Mage_Paypal
 */
class Mage_Paypal_Model_System_Config_Source_YesnoShortcut
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => Mage::helper('paypal')->__('Yes (PayPal recommends this option)')],
            ['value' => 0, 'label' => Mage::helper('paypal')->__('No')],
        ];
    }
}
