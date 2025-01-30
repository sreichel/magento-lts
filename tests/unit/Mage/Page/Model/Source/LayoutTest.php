<?php

/**
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Page\Model\Source;

use Mage;
use Mage_Page_Model_Source_Layout;
use PHPUnit\Framework\TestCase;

class LayoutTest extends TestCase
{
    public Mage_Page_Model_Source_Layout $subject;

    public function setUp(): void
    {
        Mage::app();
        $this->subject = Mage::getModel('page/source_layout');
    }

    /**
     * @group Mage_Page
     * @group Mage_Page_Model
     */
    public function testToOptionArray(): void
    {
        $this->assertIsArray($this->subject->toOptionArray(true));
    }
}
