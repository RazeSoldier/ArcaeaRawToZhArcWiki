<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Song;

use Curl\Curl;
use RazeSoldier\ArcRawToWiki\Kernel;

/**
 * 用于构建SongMap
 * @package RazeSoldier\ArcRawToWiki\Song
 */
class SongMapBuilder
{
    public const RAW_DATA_URL = 'https://share-1252159562.cos.ap-guangzhou.myqcloud.com/arcaea/songlist.json';

    public const RAW_LOCAL_PATH = Kernel::DATA_PATH . '/songlist.json';

    /**
     * @var SongMap
     */
    private $songMap;

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
        $this->songMap = new SongMap;
        foreach ($json['songs'] as $song) {
            $songObj = new Song($song['title_localized']['en'], $song['id']);
            //$songObj->setTime($song['']); // TODO
            $songObj->setArtist($song['artist']);
            $songObj->setBpm($song['bpm_base']);
            $songObj->setUpdateTime($song['date']);
            $songObj->setPastInfo($song['difficulties'][0]);
            $songObj->setPresentInfo($song['difficulties'][1]);
            $songObj->setFutureInfo($song['difficulties'][2]);
            $songObj->setPackName($song['set']);
            $this->songMap->addSong($songObj);
        }
    }

    public function getSongMap() : SongMap
    {
        return $this->songMap;
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