<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Test\WikitextModel;

use RazeSoldier\ArcRawToWiki\WikitextModel\TableParser;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public function testAddLine()
    {
        $parser = new TableParser(TableParserTest::dataProvider());
        $table = $parser->getResult();

        $table->addLine([1, 2, 3]);
        $this->assertSame([
            1 => ['待', '填', '坑', ''],
            2 => ['A', 'B', 'C', "D\nABC"],
            3 => [1, 2, 3]
        ], $table->getLines());

        $table->addLine([4, 5, 6], 2);
        $this->assertSame([
            1 => ['待', '填', '坑', ''],
            2 => [4, 5, 6],
            3 => ['A', 'B', 'C', "D\nABC"],
            4 => [1, 2, 3]
        ], $table->getLines());
    }

    public function testSync()
    {
        $parser = new TableParser(TableParserTest::dataProvider());
        $table = $parser->getResult();
        $table->addLine([1, 2, 3]);

        $expected = <<<TEXT
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
|-
| 1
| 2
| 3
|}
TEXT;

        $this->assertSame($expected, $table->getWikitext());
    }
}
