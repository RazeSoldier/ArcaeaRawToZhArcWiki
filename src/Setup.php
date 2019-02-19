<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

define('START_TIME', microtime(true));
define('APP_ROOT_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';

// 检查data目录是否存在，如果不存在则尝试创建 @{
if (!is_dir(__DIR__ . '/../data')) {
    if (!mkdir(__DIR__ . '/../data')) {
        throw new \RuntimeException('Failed to create data dir');
    }
}
// @}