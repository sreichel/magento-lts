<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    OpenMage_Tests
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Eav\Model\Convert\Adapter;

use Mage;
use Mage_Eav_Model_Convert_Adapter_Grid as Subject;
use Mage_Eav_Model_Entity_Interface;
use Override;
use OpenMage\Tests\Unit\OpenMageTest;
use OpenMage\Tests\Unit\Traits\DataProvider\Mage\Eav\Model\Convert\Adapter\GridTrait;

final class GridTest extends OpenMageTest
{
    use GridTrait;

    private static Subject $subject;

    #[Override]
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$subject = Mage::getModel('eav/convert_adapter_grid');
    }

    /**
     * @group Model
     */
    public function testGetEntity(): void
    {
        self::$subject->setVar('entity_type', 'catalog/product');
        self::assertInstanceOf(Mage_Eav_Model_Entity_Interface::class, self::$subject->getEntity());
    }

    /**
     * @group Model
     * @group test
     */
    public function testLoad(): void
    {
        self::$subject->setVar('entity_type', 'catalog/product');
        self::assertInstanceOf(Mage_Eav_Model_Entity_Interface::class, self::$subject->load());
    }
}
