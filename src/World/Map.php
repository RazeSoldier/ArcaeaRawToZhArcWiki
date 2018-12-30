<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\World;

/**
 * 游戏里世界模式里地图的映射
 * @package RazeSoldier\ArcRawToWiki\World
 */
class Map
{
    /**
     * @var string 地图的名字
     */
    private $name;

    /**
     * @var int 台阶的总数
     */
    private $stepCount;

    /**
     * @var Step[] 台阶的列表
     */
    private $steps;

    /**
     * Map constructor.
     * @param string $name 地图的名字
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getStepCount() : int
    {
        return $this->stepCount;
    }

    /**
     * @param int $stepCount
     */
    public function setStepCount(int $stepCount) : void
    {
        $this->stepCount = $stepCount;
    }

    public function addStep(Step $step) : void
    {
        $this->steps[$step->getPos()] = $step;
    }

    /**
     * @return Step[]
     */
    public function getSteps() : array
    {
        return $this->steps;
    }
}