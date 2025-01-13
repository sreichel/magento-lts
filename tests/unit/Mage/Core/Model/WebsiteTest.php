<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) 2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Core\Model;

use Mage;
use Mage_Core_Model_Resource_Store_Collection;
use Mage_Core_Model_Resource_Store_Group_Collection;
use Mage_Core_Model_Website;
use Mage_Directory_Model_Currency;
use PHPUnit\Framework\TestCase;
use Varien_Db_Select;

class WebsiteTest extends TestCase
{
    public Mage_Core_Model_Website $subject;

    public function setUp(): void
    {
        Mage::app();
        $this->subject = Mage::getModel('core/website');
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testLoad(): void
    {
        static::assertInstanceOf(Mage_Core_Model_Website::class, $this->subject->load(1));
        static::assertInstanceOf(Mage_Core_Model_Website::class, $this->subject->load('default'));
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testLoadConfig(): void
    {
        static::assertInstanceOf(Mage_Core_Model_Website::class, $this->subject->loadConfig('1'));
        static::assertInstanceOf(Mage_Core_Model_Website::class, $this->subject->loadConfig('default'));
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetStoreCollection(): void
    {
        static::assertInstanceOf(Mage_Core_Model_Resource_Store_Collection::class, $this->subject->getStoreCollection());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetGroupCollection(): void
    {
        static::assertInstanceOf(Mage_Core_Model_Resource_Store_Group_Collection::class, $this->subject->getGroupCollection());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetStores(): void
    {
        static::assertIsArray($this->subject->getStores());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetStoreIds(): void
    {
        static::assertIsArray($this->subject->getStoreIds());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetStoreCodes(): void
    {
        static::assertIsArray($this->subject->getStoreCodes());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetStoresCount(): void
    {
        static::assertIsInt($this->subject->getStoresCount());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetGroups(): void
    {
        static::assertIsArray($this->subject->getGroups());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetGroupIds(): void
    {
        static::assertIsArray($this->subject->getGroupIds());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetGroupsCount(): void
    {
        static::assertIsInt($this->subject->getGroupsCount());
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testGetBaseCurrency(): void
    {
        static::assertIsObject($this->subject->getBaseCurrency());
        static::assertInstanceOf(Mage_Directory_Model_Currency::class, $this->subject->getBaseCurrency());
    }

    //    /**
    //     * @group Mage_Core
    //     * @group Mage_Core_Model
    //     */
    //    public function testGetDefaultStore(): void
    //    {
    //        $this->assertIsObject($this->subject->getDefaultStore());
    //        $this->assertInstanceOf(Mage_Core_Model_Store::class, $this->subject->getDefaultStore());
    //    }

    /**
     * @group Mage_Core
     */
    public function testGetDefaultStoresSelect(): void
    {
        static::assertIsObject($this->subject->getDefaultStoresSelect());
        static::assertInstanceOf(Varien_Db_Select::class, $this->subject->getDefaultStoresSelect(true));
    }

    /**
     * @group Mage_Core
     * @group Mage_Core_Model
     */
    public function testIsReadOnly(): void
    {
        static::assertFalse($this->subject->isReadOnly());
        static::assertTrue($this->subject->isReadOnly(true));
    }
}
