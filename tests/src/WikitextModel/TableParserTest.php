<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Test\WikitextModel;

use RazeSoldier\ArcRawToWiki\WikitextModel\TableParser;
use PHPUnit\Framework\TestCase;

class TableParserTest extends TestCase
{
    public static function dataProvider()
    {
        return <<<TEXT
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
    }

    public function testParse()
    {
        $parser = new TableParser(self::dataProvider());
        $table = $parser->getResult();
        $this->assertSame([
            '级数', '步数', '限制', '奖励'
        ], $table->getColumnHead());
        $this->assertSame([
            1 => ['待', '填', '坑', ''],
            2 => ['A', 'B', 'C', "D\nABC"]
        ], $table->getLines());
        $this->assertSame(
            'class="wikitable mw-collapsible mw-collapsed" border="1" cellspacing="0" cellpadding="5" style="text-align:center"'
            , $table->getTableStyle());
    }
}
