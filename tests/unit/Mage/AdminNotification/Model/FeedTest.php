<?php

/**
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\AdminNotification\Model;

use Mage;
use Mage_AdminNotification_Model_Feed;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class FeedTest extends TestCase
{
    public Mage_AdminNotification_Model_Feed $subject;

    public function setUp(): void
    {
        Mage::app();
        $this->subject = Mage::getModel('adminnotification/feed');
    }

    /**
     * @group Mage_AdminNotification
     * @group Mage_AdminNotification_Model
     */
    public function testGetFeedUrl(): void
    {
        $this->assertIsString($this->subject->getFeedUrl());
    }

    /**
     * @group Mage_AdminNotification
     * @group Mage_AdminNotification_Model
     */
    public function testCheckUpdate(): void
    {
        $this->assertInstanceOf(Mage_AdminNotification_Model_Feed::class, $this->subject->checkUpdate());
    }

    /**
     * @group Mage_AdminNotification
     * @group Mage_AdminNotification_Model
     */
    public function testGetFeedData(): void
    {
        $this->assertInstanceOf(SimpleXMLElement::class, $this->subject->getFeedData());
    }

    /**
     * @group Mage_AdminNotification
     * @group Mage_AdminNotification_Model
     */
    public function testGetFeedXml(): void
    {
        $this->assertInstanceOf(SimpleXMLElement::class, $this->subject->getFeedXml());
    }
}
