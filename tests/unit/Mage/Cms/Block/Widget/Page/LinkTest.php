<?php

/**
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Cms\Block\Widget\Page;

use Mage;
use Mage_Cms_Block_Widget_Page_Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public Mage_Cms_Block_Widget_Page_Link $subject;

    public function setUp(): void
    {
        Mage::app();
        // phpcs:ignore Ecg.Classes.ObjectInstantiation.DirectInstantiation
        $this->subject = new Mage_Cms_Block_Widget_Page_Link();
    }

    /**
     * @group Mage_Cms
     * @group Mage_Cms_Block
     */
    public function testGetHref(): void
    {
        $this->assertIsString($this->subject->getHref());
    }

    /**
     * @group Mage_Cms
     * @group Mage_Cms_Block
     */
    public function testGetTitle(): void
    {
        $this->assertIsString($this->subject->getTitle());
    }

    /**
     * @group Mage_Cms
     * @group Mage_Cms_Block
     */
    //    public function testGetAnchorText(): void
    //    {
    //        $this->assertIsString($this->subject->getAnchorText());
    //    }
}
