<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Song;

/**
 * 歌曲的映射
 * @package RazeSoldier\ArcRawToWiki\Song
 */
class Song
{
    /**
     * @var string 人类可读的名字
     */
    private $name;

    /**
     * @var string 机器可读的名字
     */
    private $realName;

    /**
     * @var string 编曲者
     */
    private $artist;

    /**
     * @var string 时长
     */
    private $time;

    /**
     * @var string 收录的曲包的实际名字
     */
    private $packName;

    /**
     * @var int BPM
     */
    private $bpm;

    /**
     * @var int 更新时间的Unix时间戳
     */
    private $updateTime;

    /**
     * @var array[] 难度
     */
    private $difficulties = [
        'past' => null,
        'present' => null,
        'future' => null,
    ];

    public function __construct($name, $realName)
    {
        $this->name = $name;
        $this->realName = $realName;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRealName() : string
    {
        return $this->realName;
    }

    /**
     * @return string
     */
    public function getArtist() : string
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist(string $artist) : void
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getTime() : string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time) : void
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getBpm() : int
    {
        return $this->bpm;
    }

    /**
     * @param int $bpm
     */
    public function setBpm(int $bpm) : void
    {
        $this->bpm = $bpm;
    }

    /**
     * @return int
     */
    public function getUpdateTime() : int
    {
        return $this->updateTime;
    }

    /**
     * @param int $updateTime
     */
    public function setUpdateTime(int $updateTime) : void
    {
        $this->updateTime = $updateTime;
    }

    public function setPastInfo(array $info)
    {
        $this->difficulties['past'] = $info;
    }

    public function setPresentInfo(array $info)
    {
        $this->difficulties['present'] = $info;
    }

    public function setFutureInfo(array $info)
    {
        $this->difficulties['future'] = $info;
    }

    public function getPastInfo() : array
    {
        return $this->difficulties['past'];
    }

    public function getPresentInfo() : array
    {
        return $this->difficulties['present'];
    }

    public function getFutureInfo() : array
    {
        return $this->difficulties['future'];
    }

    /**
     * @return string
     */
    public function getPackName() : string
    {
        return $this->packName;
    }

    /**
     * @param string $packName
     */
    public function setPackName(string $packName) : void
    {
        $this->packName = $packName;
    }
}