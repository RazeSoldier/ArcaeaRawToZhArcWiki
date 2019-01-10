<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Test\WikitextModel;

use RazeSoldier\ArcRawToWiki\WikitextModel\Section;
use PHPUnit\Framework\TestCase;
use RazeSoldier\ArcRawToWiki\WikitextModel\TableParser;

class SectionTest extends TestCase
{
    public function testGetWikitext()
    {
        $section = new Section('Test', 2);
        $section->addElement('This is a test.');
        $expected = "== Test ==\nThis is a test.\n";
        $this->assertSame($expected, $section->getWikitext());
    }

    /**
     * @depends testGetWikitext
     */
    public function testGetWikitextWithTable()
    {
        $section = new Section('Test', 2);
        $section->addElement('This is a test.');
        $parser = new TableParser(TableParserTest::dataProvider());
        $table = $parser->getResult();
        $section->addElement($table);
        $expected = <<<TEXT
== Test ==
This is a test.
{| class="wikitable mw-collapsible mw-collapsed" border="1" cellspacing="0" cellpadding="5" style="text-align:center"
! 级数
! 步数
! 限制
! 奖励
|-
| 待
| 填
| 坑
| 
|-
| A
| B
| C
| D
ABC
|}

TEXT;
        $this->assertSame($expected, $section->getWikitext());
    }
}
