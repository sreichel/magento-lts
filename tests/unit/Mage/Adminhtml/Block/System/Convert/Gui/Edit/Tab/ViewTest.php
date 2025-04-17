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

namespace OpenMage\Tests\Unit\Mage\Adminhtml\Block\System\Convert\Gui\Edit\Tab;

use Mage_Adminhtml_Block_System_Convert_Gui_Edit_Tab_View as Subject;
use Mage_Dataflow_Model_Profile;
use OpenMage\Tests\Unit\OpenMageTest;

class ViewTest extends OpenMageTest
{
    /** @phpstan-ignore property.onlyWritten */
    private static Subject $subject;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$subject = new Subject();
    }

    /**
     * @group Block
     */
    public function testInitForm(): void
    {
        $methods = [
            'getRegistryCurrentConvertProfile' => new Mage_Dataflow_Model_Profile(),
        ];
        $mock = $this->getMockWithCalledMethods(Subject::class, $methods);

        static::assertInstanceOf(self::$subject::class, $mock);
        static::assertInstanceOf(self::$subject::class, $mock->initForm());
    }
}
