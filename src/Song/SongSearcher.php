<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Song;

/**
 * 用于在SongMap里搜索特定的Song
 * @package RazeSoldier\ArcRawToWiki\Song
 */
class SongSearcher
{
    /**
     * @var SongMap 在这个实例搜索
     */
    private $haystack;

    public function __construct(SongMap $page)
    {
        $this->haystack = $page;
    }

    /**
     * 根据实际名字搜索Song
     * @param string $realName 歌曲的实际名字（机器可读的名字）
     * @return Song|null 返回搜索到的Song实例，如果找不到则返回NULL
     */
    public function searchByRealName(string $realName) :? Song
    {
        $map = $this->haystack->getMap();
        foreach ($map as $song) {
            if ($song->getRealName() === $realName) {
                return $song;
            }
        }
        return null;
    }

    /**
     * 根据曲包的实际名字搜索Song
     * @param string $packName 曲包的实际名字（机器可读的名字）
     * @return Song[]|null 返回搜索到的Song实例，如果找不到则返回NULL
     */
    public function searchByPackName(string $packName) :? array
    {
        $map = $this->haystack->getMap();
        $result = null;
        foreach ($map as $song) {
            if ($song->getPackName() === $packName) {
                $result[] = $song;
            }
        }
        return $result;
    }
}