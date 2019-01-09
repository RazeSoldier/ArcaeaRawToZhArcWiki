<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\EditStrategy;

use RazeSoldier\ArcRawToWiki\Config;

/**
 * @package RazeSoldier\ArcRawToWiki\Strategy\EditStrategy
 */
class StrategyFactory
{
    private static $classMap = [
        'ExistingSectionStrategy' => 'ExistingSectionStrategy',
        'NewSectionStrategy' => 'NewSectionStrategy',
    ];

    /**
     * @param string $name
     * @param Config $config
     * @return IEditStrategy
     */
    public static function make(string $name, Config $config) : IEditStrategy
    {
        if (!isset(self::$classMap[$name])) {
            throw new \LogicException("Undefined class: '$name'");
        }
        $classname = __NAMESPACE__ . '\\' . self::$classMap[$name];
        return new $classname($config);
    }
}