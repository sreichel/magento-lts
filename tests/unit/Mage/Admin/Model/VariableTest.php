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

namespace OpenMage\Tests\Unit\Mage\Admin\Model;

use Exception;
use Mage;
use Mage_Admin_Model_Variable as Subject;
use OpenMage\Tests\Unit\OpenMageTest;
use OpenMage\Tests\Unit\Traits\DataProvider\Mage\Admin\Model\VariableTrait;

class VariableTest extends OpenMageTest
{
    use VariableTrait;

    private static Subject $subject;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$subject = Mage::getModel('admin/variable');
    }

    /**
     * @dataProvider provideValidateAdminVariableData
     * @group Model
     * @throws Exception
     */
    public function testValidate(bool|array $expectedResult, array $methods): void
    {
        $mock = $this->getMockWithCalledMethods(Subject::class, $methods);

        static::assertInstanceOf(self::$subject::class, $mock);
        static::assertSame($expectedResult, $mock->validate());
    }

    public function testIsPathAllowed(): void
    {
        static::assertIsBool(self::$subject->isPathAllowed('invalid-path'));
    }
}
