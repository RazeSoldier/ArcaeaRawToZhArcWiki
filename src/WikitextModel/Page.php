<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 维基文本里页面的映射
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class Page implements IElement
{
    /**
     * @var string|null 页面的标题
     */
    private $title;

    /**
     * @var string 页面的wikitext
     */
    private $wikitext;

    /**
     * @var array 页面的结构
     */
    private $structure;

    /**
     * Page constructor.
     * @param string|null $title 页面的标题
     */
    public function __construct(string $title = null)
    {
        $this->title = $title;
    }

    /**
     * 添加元素到页面里
     * @param IElement|string $element
     */
    public function addElement($element)
    {
        if ($element instanceof IElement || is_string($element)) {
            $this->structure[] = $element;
        } else {
            throw new \LogicException('Invalid type');
        }
    }

    /**
     * @return string|null
     */
    public function getTitle() :? string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    /**
     * 根据当前的结构重新生成段落wikitext
     */
    private function sync()
    {
        $text = '';
        foreach ($this->structure as $element) {
            if ($element instanceof IElement) {
                $text .= $element->getWikitext();
                continue;
            }
            $text .= "$element\n";
        }
        $this->wikitext = $text;
    }

    public function getWikitext() : string
    {
        if ($this->wikitext === null) {
            $this->sync();
        }
        return $this->wikitext;
    }
}