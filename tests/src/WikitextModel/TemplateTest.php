<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace src\WikitextModel;

use RazeSoldier\ArcRawToWiki\WikitextModel\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public static function data1Provider()
    {
        return '{{Test|Foo = 123|Var = OK}}';
    }

    public static function data2Provider()
    {
        return <<<TEXT
{{Test
| Foo = 123
| Var = OK
| 测试 = OK
| Arcaea = low
}}
TEXT;

    }

    public function testGetWikitextWithShort()
    {
        $template = new Template('Test');
        $template->addParam('Foo', 123);
        $template->addParam('Var', 'OK');
        $this->assertSame(self::data1Provider(), $template->getWikitext());
    }

    public function testGetWikitextWithLong()
    {
        $template = new Template('Test');
        $template->addParam('Foo', 123);
        $template->addParam('Var', 'OK');
        $template->addParam('测试', 'OK');
        $template->addParam('Arcaea', 'low');
        $this->assertSame(self::data2Provider(), $template->getWikitext());
    }
}
