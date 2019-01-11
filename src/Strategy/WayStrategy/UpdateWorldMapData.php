<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\WayStrategy;

use RazeSoldier\ArcRawToWiki\{
    Config,
    Kernel,
    Strategy\IStrategy,
    Strategy\EditStrategy\StrategyFactory,
    World\Map,
    WorldRawParser\Parser
};
use Curl\Curl;
use mikehaertl\tmp\File as TmpFile;

class UpdateWorldMapData implements IStrategy
{
    public const RAW_DATA_URL = 'https://github.com/esterTion/Arcaea_World_Mode_Raw_Data/archive/master.zip';

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function execute()
    {
        $this->downloadRawData();
        $map = $this->parseRawData($this->config->get('RawName'));
        if ($this->pushToWiki($map)) {
            echo "Success\n";
        }
    }

    /**
     * 从Github下载原始数据
     * @throws \ErrorException
     */
    private function downloadRawData()
    {
        // 下载Zip @{
        $curl = new Curl();
        $curl->setOpts([
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        $curl->get(self::RAW_DATA_URL);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
            die(1);
        }
        // @}

        // 解压下载的Zip文件到data目录下 @{
        $tmp = new TmpFile($curl->response, '.zip', null, dirname(__DIR__));
        $zip = new \ZipArchive;
        $zip->open($tmp->getFileName());
        for($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            // 只解压data目录下面的Json文件
            if (preg_match('/Arcaea_World_Mode_Raw_Data-master\/data\/.*json$/', $filename) === 0) {
                continue;
            }
            $basename = basename($filename);
            copy("zip://{$tmp->getFileName()}#$filename", Kernel::DATA_PATH . "/$basename");
        }
        $zip->close();
        // @}
    }

    /**
     * 解析指定的Raw数据文件
     * @param string $name
     * @return Map
     */
    private function parseRawData(string $name) : Map
    {
        $raw = file_get_contents(Kernel::DATA_PATH . "/$name.json");
        $parser = new Parser($raw);
        return $parser->getResult();
    }


    /**
     * 将解析的数据保存到维基上
     * @param Map $map
     * @return bool
     */
    private function pushToWiki(Map $map) : bool
    {
        $strategy = StrategyFactory::make('ExistingSectionStrategy', $this->config);
        $strategy->addMap($map);
        return $strategy->execute();
    }
}