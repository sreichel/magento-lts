<?php

declare(strict_types=1);

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Weee
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2025 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shopping cart operation observer
 *
 * @category   Mage
 * @package    Mage_Weee
 */
class Mage_Weee_Model_Observer_UpdateElementTypes extends Mage_Core_Model_Abstract implements Mage_Core_Observer_Interface
{
    /**
     * Add custom element type for attributes form
     */
    public function execute(Varien_Event_Observer $observer): void
    {
        /** @var Varien_Object $response */
        $response = $observer->getEvent()->getDataByKey('response');
        $types = $response->getDataByKey('types');
        $types['weee'] = Mage::getConfig()->getBlockClassName('weee/element_weee_tax');
        $response->setData('types', $types);
    }
}
