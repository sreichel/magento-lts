<?php

/**
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) 2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Adminhtml\Block\System\Cache\Form;

use Mage;
use Mage_Adminhtml_Block_System_Cache_Form;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public Mage_Adminhtml_Block_System_Cache_Form $subject;

    public function setUp(): void
    {
        Mage::app();
        // phpcs:ignore Ecg.Classes.ObjectInstantiation.DirectInstantiation
        $this->subject = new Mage_Adminhtml_Block_System_Cache_Form();
    }

    /**
     * @group Mage_Adminhtml
     */
    public function testInitForm(): void
    {
        $this->assertInstanceOf(Mage_Adminhtml_Block_System_Cache_Form::class, $this->subject->initForm());
    }
}
