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
     * @var array 页面所有段落的索引
     */
    private $sectionIndex = [];

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
     * 根据当前的结构重新生成段落wikitext，并且重新生成段落索引
     */
    private function sync()
    {
        $text = '';
        $this->sectionIndex = [];
        foreach ($this->structure as $i => $element) {
            if ($element instanceof IElement) {
                if ($element instanceof Section) {
                    $this->sectionIndex[] = [
                        'title' => $element->getTitle()->getText(),
                        'position' => $i,
                    ];
                }
                $text .= $element->getWikitext();
                continue;
            }
            $text .= "$element\n";
        }
        $this->wikitext = $text;
    }

    /**
     * 用一个段落替换一个现有的段落
     * @param Section $section 要替换的段落
     * @param int $pos 现有段落在页面的位置
     */
    public function replaceSection(Section $section, int $pos)
    {
        $this->structure[$pos] = $section;
    }

    /**
     * @return array
     */
    public function getSectionIndex() : array
    {
        if ($this->sectionIndex === []) {
            $this->sync();
        }
        return $this->sectionIndex;
    }

    /**
     * @return array
     */
    public function getStructure() : array
    {
        return $this->structure;
    }

    public function getWikitext() : string
    {
        $this->sync();
        return $this->wikitext;
    }
}