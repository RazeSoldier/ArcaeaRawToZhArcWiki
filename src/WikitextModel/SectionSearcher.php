<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 用于搜索Page实例里的一个Section实例
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class SectionSearcher
{
    /**
     * @var Page 在这个实例搜索
     */
    private $haystack;

    public function __construct(Page $page)
    {
        $this->haystack = $page;
    }

    /**
     * 根据段落的名字来搜索
     * @note 注意：在同一页面下有多个相同名字的段落使用此方法会返回包含这些段落的数组
     * @param string $name
     * @return Section[]|null 返回包括找到的Section实例的数组，如果找不到则返回NULL
     */
    public function searchByName(string $name) :? array
    {
        $result = [];
        $index = $this->haystack->getSectionIndex();
        foreach ($index as $value) {
            if ($value['title'] === $name) {
                $result[] = $this->haystack->getStructure()[$value['position']];
            }
        }
        return $result;
    }

    /**
     * 根据段落的名字来搜索段落在页面的位置
     * @note 注意：在同一页面下有多个相同名字的段落使用此方法会返回包含这些段落的数组
     * @param string $name
     * @return int[]|null 返回包括找到的Section位置的数组，如果找不到则返回NULL
     */
    public function searchPosByName(string $name) :? array
    {
        $result = [];
        $index = $this->haystack->getSectionIndex();
        foreach ($index as $value) {
            if ($value['title'] === $name) {
                $result[] = $value['position'];
            }
        }
        return $result;
    }
}