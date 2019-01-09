<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 用于解析表格代码（wikitext）
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class TableParser
{
    private $text;

    /**
     * @var Table
     */
    private $table;

    public function __construct($text)
    {
        $this->text = $text;
        $this->parse();
    }

    private function parse()
    {
        preg_match("/(?<table>{\|.*\n(.|\n)*\|})/", $this->text, $matches);
        $table = explode("\n", $matches['table']);
        $tableObj = new Table();
        $columnHead = [];
        $i = 0;
        foreach ($table as $line => $text) {
            // 捕捉表格样式
            if ($line === 0) {
                preg_match('/{\|\s*(?<style>.*)\s*/', $text, $matches);
                $tableObj->setTableStyle($matches['style']);
                continue;
            }
            // 排除表格的最后一行
            if ($line === count($table) - 1) {
                continue;
            }
            // 并且排除空行
            if ($text === '') {
                continue;
            }
            // 捕捉列标题
            if (strpos($text, '!') !== false) {
                preg_match('/!\s*(?<text>[\w|\W]*)/', $text, $matches);
                $text = $matches['text'];
                $columnHead[] = $text;
                continue;
            }
            // 捕捉行开始
            if (strpos($text, '|-') !== false) {
                $i++;
                continue;
            }
            // 捕捉行内容 @{
            if (preg_match('/\|(.(?!\-)).*/', $text) === 1) {
                preg_match('/\|\s*(?<text>[\w|\W]*)/', $text, $matches);
                $rows[$i][] = $matches['text'];
                continue;
            }
            $text = end($rows[$i]) . "\n$text";
            $rows[$i][key($rows[$i])] = $text;
            // @}
        }
        $tableObj->setColumnHead($columnHead);
        $tableObj->setLines($rows);
        $this->table = $tableObj;
    }

    public function getResult() : Table
    {
        return $this->table;
    }
}