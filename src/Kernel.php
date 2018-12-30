<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki;

use Curl\Curl;
use mikehaertl\tmp\File as TmpFile;
use RazeSoldier\ArcRawToWiki\Pusher\Editor;
use RazeSoldier\ArcRawToWiki\WikitextModel\TableParser;
use RazeSoldier\ArcRawToWiki\World\Map;
use RazeSoldier\ArcRawToWiki\WorldRawParser\Parser;

final class Kernel
{
    public const RAW_DATA_URL = 'https://github.com/esterTion/Arcaea_World_Mode_Raw_Data/archive/master.zip';

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
        $this->downloadRawData();
        $map = $this->parseRawData($this->config->get('RawName'));
        if ($this->pushToWiki($map))
        {
            echo "Success\n";
        }
    }

    /**
     * 从Github下载原始数据
     * @throws \ErrorException
     */
    private function downloadRawData()
    {
        // 下载Zip@{
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
            copy("zip://{$tmp->getFileName()}#$filename", self::DATA_PATH . "/$basename");
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
        $raw = file_get_contents(self::DATA_PATH . "/$name.json");
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
        // 获取现在的wiki源代码 @{
        /** @var \Mediawiki\DataModel\Page $page */
        $page = MWApiServices::getInstance()->newPageGetter()->getFromTitle( 'World模式梯子详表' );
        $source = $page->getRevisions()->getLatest()->getContent()->getData();
        // @}
        // 生成文本 @{
        preg_match('/(?<text>=限时：Empire of Winter=\n(.|\n)*)(=.*=)?/', $source, $matches);
        $text = $matches['text'];
        $table = (new TableParser($text))->getResult();
        foreach ($map->getSteps() as $step) {
            // 生成奖励文本 @{
            if ($step->getReward() !== null) {
                $value = current($step->getReward());
                switch (key($step->getReward())) {
                    case 'plus_stamina':
                        $reward = "+$value stamina";
                        break;
                    case 'fragment':
                        $reward = "$value Fragments";
                        break;
                    case 'character':
                        if ($value == 20) {
                            $reward = '[[搭档#20..E7.88.B1.E6.89.98_.26_.E9.9C.B2.E5.A8.9C_-.E5.86.AC-.EF.BC.88Eto_.26_Luna_-Winter-.EF.BC.89|爱托 & 露娜 -冬-]]';
                        } else {
                            $reward = $value;
                        }
                        break;
                }
            } else {
                $reward = '';
            }
            // @}
            $data = [$step->getPos(), $step->getHeight(), $step->isRestrict(), $reward];
            $table->addLine($data);
        }
        // @}
        // 开始编辑
        $text = preg_replace("/(?<table>{\|.*\n(.|\n)*\|})/", $table->getWikitext(), $text);
        $finalText = preg_replace('/(?<text>=限时：Empire of Winter=\n(.|\n)*)(=.*=)?/', $text, $source);
        $editor = new Editor('World模式梯子详表');
        return $editor->edit($page, $finalText, '填充梯子资料（由bot进行的编辑）');
    }
}
