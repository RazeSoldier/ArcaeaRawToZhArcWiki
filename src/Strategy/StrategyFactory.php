<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy;

use RazeSoldier\ArcRawToWiki\Config;

/**
 * 策略工厂
 * @package RazeSoldier\ArcRawToWiki\Strategy
 */
class StrategyFactory
{
	private static $classMap = [];

	/**
	 * @param string $name
	 * @param Config $config
	 * @return IStrategy
	 */
	public static function make(string $name, Config $config) : IStrategy
	{
		if (self::$classMap === []) {
			self::$classMap = require APP_ROOT_PATH . '/src/Strategy/StrategyMap.php';
		}
		if (!isset(self::$classMap[$name])) {
			throw new \LogicException("Undefined class: '$name'");
		}
		$classname = __NAMESPACE__ . '\\' . self::$classMap[$name];
		return new $classname($config);
	}
}