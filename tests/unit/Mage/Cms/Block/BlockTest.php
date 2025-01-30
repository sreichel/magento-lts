<?php

/**
 * @category   OpenMage
 * @package    OpenMage_Tests
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace OpenMage\Tests\Unit\Mage\Cms\Block;

use Generator;
use Mage_Cms_Block_Block;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /**
     * @dataProvider provideGetCacheKeyInfoData
     * @group Mage_Cms
     * @group Mage_Cms_Block
     */
    public function testGetCacheKeyInfo(string $blockId): void
    {
        $mock = $this->getMockBuilder(Mage_Cms_Block_Block::class)
            ->setMethods(['getBlockId'])
            ->getMock();

        $mock->method('getBlockId')->willReturn($blockId);
        $this->assertIsArray($mock->getCacheKeyInfo());
    }

    public function provideGetCacheKeyInfoData(): Generator
    {
        yield 'valid block ID' => [
            '2',
        ];
        yield 'invalid block ID' => [
            '0',
        ];
    }
}
