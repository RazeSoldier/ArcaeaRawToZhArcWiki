<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy;

use RazeSoldier\ArcRawToWiki\Config;

interface IStrategy
{
    public function __construct(Config $config);

    /**
     * 执行策略
     * @return mixed
     */
    public function execute();
}