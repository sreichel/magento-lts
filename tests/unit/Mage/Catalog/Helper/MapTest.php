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

namespace OpenMage\Tests\Unit\Mage\Catalog\Helper;

use Mage;
use Mage_Catalog_Helper_Map as Subject;
use OpenMage\Tests\Unit\OpenMageTest;

class MapTest extends OpenMageTest
{
    private static Subject $subject;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$subject = Mage::helper('catalog/map');
    }

    /**
     * @group Helper
     * @group runInSeparateProcess
     * @runInSeparateProcess
     */
    public function testGetCategoryUrl(): void
    {
        static::assertStringEndsWith('/catalog/seo_sitemap/category/', self::$subject->getCategoryUrl());
    }

    /**
     * @group Helper
     * @group runInSeparateProcess
     * @runInSeparateProcess
     */
    public function testGetProductUrl(): void
    {
        static::assertStringEndsWith('/catalog/seo_sitemap/product/', self::$subject->getProductUrl());
    }

    /**
     * @group Helper
     */
    public function testGetIsUseCategoryTreeMode(): void
    {
        static::assertIsBool(self::$subject->getIsUseCategoryTreeMode());
    }
}
