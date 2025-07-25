<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    OpenMage_Tests
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Cms\Model;

use Mage;
use Mage_Cms_Model_Page as Subject;
use OpenMage\Tests\Unit\OpenMageTest;

final class PageTest extends OpenMageTest
{
    public const SKIP_WITH_LOCAL_DATA = 'Constant DATA_MAY_CHANGED is defined.';

    private static Subject $subject;

    public function setUp(): void
    {
        self::$subject = Mage::getModel('cms/page');
    }

    /**
     * @group Model
     */
    public function testLoad(): void
    {
        static::assertInstanceOf(Subject::class, self::$subject->load(null));
        static::assertInstanceOf(Subject::class, self::$subject->load(2));
    }

    /**
     * @group Model
     */
    public function testCheckIdentifier(): void
    {
        static::assertIsString(self::$subject->checkIdentifier('home', 1));
    }

    /**
     * @group Model
     */
    public function testGetCmsPageTitleByIdentifier(): void
    {
        if (defined('DATA_MAY_CHANGED')) {
            static::markTestSkipped(self::SKIP_WITH_LOCAL_DATA);
        }
        static::assertSame('Home page', self::$subject->getCmsPageTitleByIdentifier('home'));
    }

    /**
     * @group Model
     */
    public function testGetCmsPageTitleById(): void
    {
        if (defined('DATA_MAY_CHANGED')) {
            static::markTestSkipped(self::SKIP_WITH_LOCAL_DATA);
        }
        static::assertSame('Home page', self::$subject->getCmsPageTitleById(2));
    }

    /**
     * @group Model
     */
    public function testGetCmsPageIdentifierById(): void
    {
        static::assertSame('home', self::$subject->getCmsPageIdentifierById(2));
    }

    /**
     * @group Model
     */
    public function testGetAvailableStatuses(): void
    {
        static::assertIsArray(self::$subject->getAvailableStatuses());
    }

    /**
     * @group Model
     * @doesNotPerformAssertions
     */
    public function testGetUsedInStoreConfigCollection(): void
    {
        self::$subject->getUsedInStoreConfigCollection();
    }

    /**
     * @group Model
     */
    public function testIsUsedInStoreConfig(): void
    {
        static::assertFalse(self::$subject->isUsedInStoreConfig());
    }
}
