<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Core
 * @copyright  Copyright (c) 2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

/**
 * Session trait
 *
 * @category   Mage
 * @package    Mage_Core
 */
trait Mage_Core_Trait_Session_Customer
{
    protected static string $name = 'customer/session';

    /**
     * Retrieve customer session model object
     */
    final protected function getCustomerSession(): Mage_Customer_Model_Session
    {
        /** @var Mage_Customer_Model_Session $session */
        $session = Mage::getSingleton(self::$name);
        return $session;
    }

    final protected function getCustomerSessionStorage(): string
    {
        return self::$name;
    }
}
