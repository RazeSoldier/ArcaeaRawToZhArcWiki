<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\WayStrategy;

use RazeSoldier\ArcRawToWiki\{
    Config,
    Strategy\IStrategy,
};

/**
 * @package RazeSoldier\ArcRawToWiki\Strategy\WayStrategy
 */
class StrategyFactory
{
    private static $classMap = [
        'UpdateWorldMapData' => 'UpdateWorldMapData',
    ];

    /**
     * @param string $name
     * @param Config $config
     * @return IStrategy
     */
    public static function make(string $name, Config $config) : IStrategy
    {
        if (!isset(self::$classMap[$name])) {
            throw new \LogicException("Undefined class: '$name'");
        }
        $classname = __NAMESPACE__ . '\\' . self::$classMap[$name];
        return new $classname($config);
    }
}