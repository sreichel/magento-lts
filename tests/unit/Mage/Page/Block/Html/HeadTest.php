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

namespace OpenMage\Tests\Unit\Mage\Page\Block\Html;

use Mage;
use Mage_Page_Block_Html_Head as Subject;
use PHPUnit\Framework\TestCase;

class HeadTest extends TestCase
{
    private static Subject $subject;

    public static function setUpBeforeClass(): void
    {
        Mage::app();
        self::$subject = new Subject();
    }

    /**
     * @group Mage_Page
     * @group Mage_Page_Block
     */
    public function testAddCss(): void
    {
        static::assertInstanceOf(Subject::class, self::$subject->addCss('test'));
    }

    /**
     * @group Mage_Page
     * @group Mage_Page_Block
     */
    public function testAddJs(): void
    {
        static::assertInstanceOf(Subject::class, self::$subject->addJs('test'));
    }

    /**
     * @group Mage_Page
     * @group Mage_Page_Block
     */
    public function testAddCssIe(): void
    {
        static::assertInstanceOf(Subject::class, self::$subject->addCssIe('test'));
    }

    /**
     * @group Mage_Page
     * @group Mage_Page_Block
     */
    public function testAddJsIe(): void
    {
        static::assertInstanceOf(Subject::class, self::$subject->addJsIe('test'));
    }

    /**
     * @group Mage_Page
     * @group Mage_Page_Block
     */
    public function testAddLinkRel(): void
    {
        static::assertInstanceOf(Subject::class, self::$subject->addLinkRel('test', 'ref'));
    }
}
