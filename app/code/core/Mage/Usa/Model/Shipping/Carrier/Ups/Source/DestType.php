<?php

/**
 * @category   Mage
 * @package    Mage_Usa
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Usa
 */
class Mage_Usa_Model_Shipping_Carrier_Ups_Source_DestType
{
    public function toOptionArray()
    {
        $ups = Mage::getSingleton('usa/shipping_carrier_ups');
        $arr = [];
        foreach ($ups->getCode('dest_type_description') as $k => $v) {
            $arr[] = ['value' => $k, 'label' => Mage::helper('usa')->__($v)];
        }
        return $arr;
    }
}
