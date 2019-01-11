<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 维基文本里标题的映射
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class Title implements IElement
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $level;

    private $wikitext;

    public function __construct(string $text, string $level)
    {
        $this->text = $text;
        $this->level = $level;
    }

    private function sync()
    {
        $symbol = str_repeat('=', $this->level);
        $this->wikitext = "$symbol $this->text $symbol";
    }

    public function getWikitext() : string
    {
        if ($this->wikitext === null) {
            $this->sync();
        }
        return $this->wikitext;
    }

    /**
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }
}