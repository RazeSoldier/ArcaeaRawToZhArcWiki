<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki;

use RazeSoldier\ArcRawToWiki\Strategy\StrategyFactory;

final class Kernel
{
    public const DATA_PATH = __DIR__ . '/../data';

    /**
     * @var Config
     */
    private $config;

    public function __construct()
    {
        $this->config = Config::getInstance();
    }

    public function run()
    {
    	$strategy = StrategyFactory::make('WayStrategy.' . $this->config->get('Strategy'), $this->config);
    	$strategy->execute();
    }
}
