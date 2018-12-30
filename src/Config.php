<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki;

/**
 * 用于从配置文件里获取对应的配置信息
 * @package RazeSoldier\ArcRawToWiki
 */
class Config
{
    /**
     * 配置文件的路径
     */
    public const CONFIG_PATH = __DIR__ . '/../config.php';

    /**
     * 所有的配置变量以“cfg”开头
     */
    public const CONFIG_VAR_PREFIX = 'cfg';

    private static $instance;

    private $options = [];

    private function __construct()
    {
        require_once self::CONFIG_PATH;
        // 从已定义的变量的列表里获取配置变量
        $vars = get_defined_vars();
        foreach ($vars as $varName => $value) {
            if (strpos($varName, self::CONFIG_VAR_PREFIX) === 0) {
                $varName = str_replace(self::CONFIG_VAR_PREFIX, null, $varName);
                $this->options[$varName] = $value;
            }
        }
    }

    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 获取以$key为名的配置的值
     * @param string $key 配置的键
     * @return mixed 返回配置的值，如果配置不存在则返回NULL（请不要依赖本方法来检查配置是否存在）
     */
    public function get(string $key)
    {
        if (!$this->has($key)) {
            return null;
        }
        return $this->options[$key];
    }

    /**
     * 检查以$key为名的配置是否存在
     * @param string $key
     * @return bool 如果存在返回TRUE，不存在返回FALSE
     */
    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->options);
    }
}