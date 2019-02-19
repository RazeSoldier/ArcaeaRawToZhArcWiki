<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\WayStrategy;

use RazeSoldier\ArcaeaDataModel\Song\SongMapBuilder;
use RazeSoldier\ArcRawToWiki\{
    Config,
    Kernel,
    MWApiServices,
    Pusher\Editor,
    Strategy\IStrategy,
};

/**
 * 创建日文重定向
 * @package RazeSoldier\ArcRawToWiki\Strategy\WayStrategy
 */
class JaRedirectCreation implements IStrategy
{
    private $songMap;

    public function __construct(Config $config)
    {
        $this->songMap = (new SongMapBuilder(Kernel::DATA_PATH . '/songlist.json'))->getSongMap();
    }

    public function execute()
    {
        $ignore = [];
        $srcPageNoExists = [];
        $jaPageExists = [];
        $ok = 0;
        $error = 0;
        foreach($this->songMap->getMap() as $song) {
            $i18ns = $song->getI18nName();
            if (isset($i18ns['ja'])) {
                $enName = $song->getName();
                // 如果ja翻译和歌曲名相同，则不作操作
                if ($enName === $i18ns['ja']) {
                    $ignore[] = $i18ns['ja'];
                    continue;
                }
                // 检查歌曲名对应的页面是否存在，如果不存在则跳过
                if (!$this->checkPageExists($enName)) {
                    $srcPageNoExists[] = $enName;
                    continue;
                }
                // 检查ja翻译对应的页面是否存在，如果存在则跳过
                if ($this->checkPageExists($i18ns['ja'])) {
                    $jaPageExists[] = $i18ns['ja'];
                    continue;
                }
                // 完成检查，开始创建重定向
                $editor = new Editor($i18ns['ja']);
                $res = $editor->create("#重定向 [[$enName]]", '创建日文重定向（bot所做的编辑）');
                $res ? $ok++ : $error++;
            }
        }
        // 制作报告
        echo "+--Report--+\n";
        echo "Created $ok redirect\n";
        echo "$error errors\n";
        if ($ignore !== []) {
            echo "Following pages same the ja i18n:\n";
            foreach ($ignore as $item) {
                echo " - $item\n";
            }
        }
        if ($srcPageNoExists !== []) {
            echo "Following pages not exists:\n";
            foreach ($srcPageNoExists as $item) {
                echo " - $item\n";
            }
        }
        if ($jaPageExists !== []) {
            echo "Following ja i18n pages already exists:\n";
            foreach ($jaPageExists as $item) {
                echo " - $item\n";
            }
        }
    }

    private function checkPageExists(string $name) : bool
    {
        $page = MWApiServices::getInstance()->newPageGetter()->getFromTitle($name);
        return (count($page->getRevisions()->toArray()) >= 1);
    }
}