<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki;

use RazeSoldier\ArcRawToWiki\Strategy\WayStrategy\StrategyFactory;

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
        $strategy = StrategyFactory::make($this->config->get('Strategy'), $this->config);
        $strategy->execute();
    }

    public function __destruct()
    {
        echo "+--Script Running Report--+\n";
        echo 'Duration: ' . round(microtime(true) - START_TIME, 2) . "s \n";
        echo 'Memory usage: ' . round(memory_get_usage()/1024/1024, 2) . ' M (Peak: ' .
            round(memory_get_peak_usage()/1024/1024, 2) . " M)\n";
    }
}
