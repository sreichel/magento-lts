<?php

/**
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Catalog\Model;

use Generator;
use Mage;
use Mage_Catalog_Model_Category;
use Mage_Catalog_Model_Category_Url;
use Mage_Catalog_Model_Resource_Product_Collection;
use Mage_Catalog_Model_Url;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public const TEST_STRING = 'a & B, x%, ä, ö, ü';

    public Mage_Catalog_Model_Category $subject;

    public function setUp(): void
    {
        Mage::app();
        $this->subject = Mage::getModel('catalog/category');
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testGetDefaultAttributeSetId(): void
    {
        $this->assertIsInt($this->subject->getDefaultAttributeSetId());
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testGetProductCollection(): void
    {
        $this->assertInstanceOf(Mage_Catalog_Model_Resource_Product_Collection::class, $this->subject->getProductCollection());
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testGetAvailableSortByOptions(): void
    {
        $this->assertIsArray($this->subject->getAvailableSortByOptions());
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testGetDefaultSortBy(): void
    {
        $this->assertSame('position', $this->subject->getDefaultSortBy());
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testValidate(): void
    {
        $this->assertIsArray($this->subject->validate());
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testAfterCommitCallback(): void
    {
        $this->assertInstanceOf(Mage_Catalog_Model_Category::class, $this->subject->afterCommitCallback());
    }

    /**
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testGetUrlModel(): void
    {
        $this->assertInstanceOf(Mage_Catalog_Model_Url::class, $this->subject->getUrlModel());
        $this->assertInstanceOf(Mage_Catalog_Model_Category_Url::class, $this->subject->getUrlModel());
    }

    /**
     * @dataProvider provideFormatUrlKey
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     * @runInSeparateProcess
     */
    //    public function testGetCategoryIdUrl($expectedResult, ?string $locale): void
    //    {
    //        $this->subject->setName(self::TEST_STRING);
    //        $this->subject->setLocale($locale);
    //        $this->assertSame($expectedResult, $this->subject->getCategoryIdUrl());
    //    }

    /**
     * @dataProvider provideFormatUrlKey
     * @group Mage_Catalog
     * @group Mage_Catalog_Model
     */
    public function testFormatUrlKey($expectedResult, ?string $locale): void
    {
        $this->subject->setLocale($locale);
        $this->assertSame($expectedResult, $this->subject->formatUrlKey(self::TEST_STRING));
    }

    public function provideFormatUrlKey(): Generator
    {
        yield 'null locale' => [
            'a-b-x-a-o-u',
            null,
        ];
        yield 'de_DE' => [
            'a-und-b-x-prozent-ae-oe-ue',
            'de_DE',
        ];
    }
}
