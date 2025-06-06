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

namespace OpenMage\Tests\Unit\Base;

use Generator;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;
use XMLReader;

class XmlFileLoadingTest extends TestCase
{
    /**
     * @dataProvider provideXmlFiles
     */
    public function testFileLoading(string $filepath): void
    {
        /** @var SimpleXMLElement $simplexml */
        $simplexml = simplexml_load_file(
            $filepath,
            SimpleXMLElement::class,
            LIBXML_PEDANTIC, //not needed by OpenMage, but good to test more strictly
        );
        static::assertNotEmpty($simplexml->asXML());
    }

    /**
     * @dataProvider provideXmlFiles
     */
    public function testXmlReaderIsValid(string $filepath): void
    {
        /** @var XMLReader $xml */
        $xml = XMLReader::open($filepath);
        $xml->setParserProperty(XMLReader::VALIDATE, true);
        static::assertTrue($xml->isValid());
    }

    public function provideXmlFiles(): Generator
    {
        $root = realpath(__DIR__ . '/../../../') . '/';

        yield 'file from vendor directory' => [
            $root . 'vendor/shardj/zf1-future/library/Zend/Locale/Data/es_419.xml',
        ];
    }
}
