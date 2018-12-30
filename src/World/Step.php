<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\World;

/**
 * 世界模式里地图上台阶的映射
 * @package RazeSoldier\ArcRawToWiki\World
 */
class Step
{
    /**
     * @var int 台阶在地图上的位置
     */
    private $pos;

    /**
     * @var int 台阶的高度
     */
    private $height;

    /**
     * @var bool 是否包含限制
     */
    private $isRestrict;

    /**
     * @var array|null 奖励，如果没有奖励则为NULL
     */
    private $reward;

    /**
     * @return int
     */
    public function getPos() : int
    {
        return $this->pos;
    }

    /**
     * @param int $pos
     */
    public function setPos(int $pos) : void
    {
        $this->pos = $pos;
    }

    /**
     * @return int
     */
    public function getHeight() : int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height) : void
    {
        $this->height = $height;
    }

    /**
     * @return bool
     */
    public function isRestrict() : bool
    {
        return $this->isRestrict;
    }

    /**
     * @param bool $isRestrict
     */
    public function setIsRestrict(bool $isRestrict) : void
    {
        $this->isRestrict = $isRestrict;
    }

    /**
     * @return array|null
     */
    public function getReward() : ?array
    {
        return $this->reward;
    }

    /**
     * @param array|null $reward
     */
    public function setReward(?array $reward) : void
    {
        $this->reward = $reward;
    }
}