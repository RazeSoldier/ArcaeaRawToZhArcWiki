<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Pack;

use Curl\Curl;
use RazeSoldier\ArcRawToWiki\Kernel;

/**
 * 用于构建PackMap
 * @package RazeSoldier\ArcRawToWiki\Pack
 */
class PackMapBuilder
{
    public const RAW_DATA_URL = 'https://share-1252159562.cos.ap-guangzhou.myqcloud.com/arcaea/packlist.json';

    public const RAW_LOCAL_PATH = Kernel::DATA_PATH . '/packlist.json';

    /**
     * @var PackMap
     */
    private $packMap;

    public function __construct()
    {
        // 如果songlist.json不存在则从服务器拉取
        if (!file_exists(self::RAW_LOCAL_PATH)) {
            $this->downloadRAWData();
        }
        $this->build();
    }

    private function build()
    {
        $json = json_decode(file_get_contents(self::RAW_LOCAL_PATH), true);
        $this->packMap = new PackMap();
        foreach ($json['packs'] as $pack) {
            $packObj = new Pack($pack['name_localized']['en'], $pack['id']);
            $this->packMap->addPack($packObj);
        }
    }

    public function getPackMap() : PackMap
    {
        return $this->packMap;
    }

    private function downloadRAWData()
    {
        $curl = new Curl;
        $curl->download(self::RAW_DATA_URL, self::RAW_LOCAL_PATH);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            die(1);
        }
    }
}