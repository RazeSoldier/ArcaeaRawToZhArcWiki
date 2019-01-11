<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 维基文本里模版的映射
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class Template implements IElement
{
    /**
     * @var string 模版的名字
     */
    private $name;

    /**
     * @var array[] 模版的参数
     */
    private $params = [];

    /**
     * @var string 维基文本
     */
    private $wikitext;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * 往模版添加一个参数
     * @param string $key
     * @param $value
     */
    public function addParam(string $key, $value)
    {
        $this->params[] = [
            'key' => $key,
            'value' => $value
        ];
    }

    private function sync()
    {
        // 如果参数的数量大于3，则隔行一个参数
        $count = count($this->params);
        if ($count >= 3) {
            $text = "{{{$this->name}\n";
            foreach ($this->params as $i => $param) {
                $text .= "| {$param['key']} = {$param['value']}\n";
            }
            $text .= '}}';
            $this->wikitext = $text;
        } else {
            $text = "{{{$this->name}";
            foreach ($this->params as $i => $param) {
                $text .= "|{$param['key']} = {$param['value']}";
            }
            $text .= '}}';
            $this->wikitext = $text;
        }
    }

    public function getWikitext() : string
    {
        $this->sync();
        return $this->wikitext;
    }
}