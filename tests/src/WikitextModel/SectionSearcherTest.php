<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Test\WikitextModel;

use RazeSoldier\ArcRawToWiki\WikitextModel\PageParser;
use RazeSoldier\ArcRawToWiki\WikitextModel\SectionSearcher;
use PHPUnit\Framework\TestCase;

class SectionSearcherTest extends TestCase
{
    /**
     * @var SectionSearcher
     */
    private $searcher;

    public static function dataProvider()
    {
        return file_get_contents(__DIR__ . '/page_test.wikitext');
    }

    public function setUp()
    {
        $page = (new PageParser(self::dataProvider()))->getResult();
        $this->searcher = new SectionSearcher($page);
    }

    public function testSearchPosByName()
    {
        $this->assertSame([8], $this->searcher->searchPosByName('0-L'));
    }
}
