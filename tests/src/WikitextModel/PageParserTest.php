<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Test\WikitextModel;

use RazeSoldier\ArcRawToWiki\WikitextModel\PageParser;
use PHPUnit\Framework\TestCase;

class PageParserTest extends TestCase
{
    public static function dataProvider()
    {
        return file_get_contents(__DIR__ . '/page_test.wikitext');
    }

    public function testGetResult()
    {
        $parser = new PageParser(self::dataProvider());
        $this->assertSame(file_get_contents(__DIR__ . '/page_test_expected.wikitext'),
            $parser->getResult()->getWikitext());
    }
}
