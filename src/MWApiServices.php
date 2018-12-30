<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki;

use Mediawiki\Api\{
    ApiUser,
    MediawikiApi,
    MediawikiFactory,
};

/**
 * 包装Mediawiki\Api\MediawikiFactory
 * @package RazeSoldier\ArcRawToWiki
 */
class MWApiServices
{
    private static $instance;

    /**
     * @var MediawikiFactory
     */
    private $services;

    private function __construct()
    {
        $config = Config::getInstance();
        $api = new MediawikiApi($config->get('ApiEntry'));
        $api->login(new ApiUser($config->get('Username'), $config->get('Password')));
        $this->services = new MediawikiFactory($api);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __call(string $name, array $params = null)
    {
        return $this->services->$name(...$params);
    }
}