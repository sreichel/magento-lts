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

namespace OpenMage\Tests\Unit\Mage\Adminhtml\Helper;

use Generator;
use Mage;
use Mage_Adminhtml_Helper_Sales as Subject;
use PHPUnit\Framework\TestCase;

class SalesTest extends TestCase
{
    private static Subject $subject;

    public static function setUpBeforeClass(): void
    {
        Mage::app();
        self::$subject = Mage::helper('adminhtml/sales');
    }

    /**
     * @covers Mage_Adminhtml_Helper_Sales::escapeHtmlWithLinks()
     * @dataProvider provideDecodeGridSerializedInput
     * @group Mage_Adminhtml
     * @group Mage_Adminhtml_Helper
     */
    public function testEscapeHtmlWithLinks(string $expectedResult, string $data): void
    {
        static::assertSame($expectedResult, self::$subject->escapeHtmlWithLinks($data, ['a']));
    }

    public function provideDecodeGridSerializedInput(): Generator
    {
        yield 'test #1' => [
            '&lt;a href=&quot;https://localhost&quot;&gt;',
            '<a href="https://localhost">',
        ];
    }
}
