<?php

/**
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * @category   Mage
 * @package    Mage_Sales
 */
class Mage_Sales_Model_Quote_Address_Total_Custbalance extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    /**
     * @return $this
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $address->setCustbalanceAmount(0);
        $address->setBaseCustbalanceAmount(0);

        $address->setGrandTotal($address->getGrandTotal() - $address->getCustbalanceAmount());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseCustbalanceAmount());

        return $this;
    }
}
