<?php

/**
 * @category   Mage
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Backend model for shipping table rates CSV importing
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 */
class Mage_Adminhtml_Model_System_Config_Backend_Shipping_Tablerate extends Mage_Core_Model_Config_Data
{
    public function _afterSave()
    {
        Mage::getResourceModel('shipping/carrier_tablerate')->uploadAndImport($this);
        return $this;
    }
}
