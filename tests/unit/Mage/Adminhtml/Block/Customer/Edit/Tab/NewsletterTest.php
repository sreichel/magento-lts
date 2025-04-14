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

namespace OpenMage\Tests\Unit\Mage\Adminhtml\Block\Customer\Edit\Tab;

use Mage;
use Mage_Adminhtml_Block_Customer_Edit_Tab_Newsletter as Subject;
use Mage_Customer_Model_Customer;
use PHPUnit\Framework\TestCase;

class NewsletterTest extends TestCase
{
    /** @phpstan-ignore property.onlyWritten */
    private static Subject $subject;

    public static function setUpBeforeClass(): void
    {
        Mage::app();
        self::$subject = new Subject();
    }

    /**
     *
     * @group Mage_Adminhtml
     * @group Mage_Adminhtml_Block
     */
    public function testInitForm(): void
    {
        $mock = $this->getMockBuilder(Subject::class)
            ->setMethods(['getRegistryCurrentCustomer'])
            ->getMock();

        $mock
            ->method('getRegistryCurrentCustomer')
            // phpcs:ignore Ecg.Classes.ObjectInstantiation.DirectInstantiation
            ->willReturn(new Mage_Customer_Model_Customer());

        static::assertInstanceOf(Subject::class, $mock->initForm());
    }
}
