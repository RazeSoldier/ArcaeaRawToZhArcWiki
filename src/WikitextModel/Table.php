<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 维基文本里表格的映射
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class Table
{
    /**
     * @var array 数组化后的原始wikitext代码
     */
    private $source;

    /**
     * @var string 表格的wikitext
     */
    private $wikitext;

    /**
     * @var string 表格样式
     */
    private $tableStyle;

    /**
     * @var string[] 表格头部
     */
    private $columnHead;

    /**
     * @var array 每行的数据
     */
    private $lines = [];

    public function __construct(array $source)
    {
        $this->source = $source;
    }

    /**
     * @return string[]
     */
    public function getColumnHead() : array
    {
        return $this->columnHead;
    }

    /**
     * @param string[] $columnHead
     */
    public function setColumnHead(array $columnHead) : void
    {
        $this->columnHead = $columnHead;
    }

    /**
     * @return array
     */
    public function getLines() : array
    {
        return $this->lines;
    }

    /**
     * @param array $lines
     */
    public function setLines(array $lines) : void
    {
        $this->lines = $lines;
    }

    /**
     * @return string
     */
    public function getTableStyle() : string
    {
        return $this->tableStyle;
    }

    /**
     * @param string $tableStyle
     */
    public function setTableStyle(string $tableStyle) : void
    {
        $this->tableStyle = $tableStyle;
    }

    /**
     * 增加一行
     * @param array $data 要插入的表格的数据
     * @param int|null $fillLine 插入的位置，如果未指定则插入表格末尾
     * @return array 返回插入后的行数据
     */
    public function addLine(array $data, int $fillLine = null) : array
    {
        if ($fillLine === null) {
            $this->lines[] = $data;
            $this->sync();
            return $this->lines;
        }
        if ($fillLine > count($this->lines)) {
            throw new \RangeException('$fillLine out of offset');
        }
        $prefix = array_slice($this->lines, 0, $fillLine - 1, true);
        $suffix = array_slice($this->lines, $fillLine - 1, null);
        array_push($prefix, $data, ...$suffix);
        $this->sync();
        return $this->lines = $prefix;
    }

    /**
     * 根据当前的表格树重新生成表格wikitext
     */
    private function sync()
    {
        $text = "{| {$this->tableStyle}\n";
        foreach ($this->columnHead as $item) {
            $text .= "! $item\n";
        }
        foreach ($this->lines as $line) {
            $text .= "|-\n";
            foreach ($line as $item) {
                $text .= "| $item\n";
            }
        }
        $text .= '|}';
        $this->wikitext =  $text;
    }

    public function getWikitext() : string
    {
        if ($this->wikitext === null) {
            $this->sync();
        }
        return $this->wikitext;
    }
}