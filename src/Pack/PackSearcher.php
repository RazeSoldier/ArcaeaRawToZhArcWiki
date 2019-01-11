<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Pack;

/**
 * 用于在PackMap里搜索特定的Pack
 * @package RazeSoldier\ArcRawToWiki\Pack
 */
class PackSearcher
{
    /**
     * @var PackMap 在这个实例搜索
     */
    private $haystack;

    public function __construct(PackMap $page)
    {
        $this->haystack = $page;
    }

    /**
     * 根据实际名字搜索Pack
     * @param string $realName 曲包的实际名字（机器可读的名字）
     * @return Pack|null 返回搜索到的Pack实例，如果找不到则返回NULL
     */
    public function searchByRealName(string $realName) :? Pack
    {
        $map = $this->haystack->getMap();
        foreach ($map as $pack) {
            if ($pack->getRealName() === $realName) {
                return $pack;
            }
        }
        return null;
    }
}