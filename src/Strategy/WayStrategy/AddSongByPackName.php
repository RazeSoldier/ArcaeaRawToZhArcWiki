<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\WayStrategy;

use RazeSoldier\ArcRawToWiki\{
    Config,
    MWApiServices,
    Pusher\Editor,
    Strategy\IStrategy,
    WikitextModel\Page,
    WikitextModel\Section,
    WikitextModel\Table,
    WikitextModel\Template
};
use RazeSoldier\ArcaeaDataModel\{
    ChartDesigner\DesignerMap,
    Pack\PackMap,
    Pack\PackMapBuilder,
    Pack\PackSearcher,
    Song\Song,
    Song\SongMapBuilder,
    Song\SongSearcher,
};

/**
 * 根据提供的曲包名来在维基上创建曲包里的歌曲条目
 * @note 将会忽略已经创建的歌曲条目
 * @package RazeSoldier\ArcRawToWiki\Strategy\WayStrategy
 */
class AddSongByPackName implements IStrategy
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var PackMap
     */
    private $packMap;

    public function __construct(Config $config)
    {
        $this->config = $config;
        if (!$this->config->has('WantUpdatePackName')) {
            throw new \RuntimeException('$cfgWantUpdatePackName not set');
        }
        $this->packMap = (new PackMapBuilder())->getPackMap();
    }

    public function execute()
    {
        $this->checkPackExist();
        $songMap = (new SongMapBuilder())->getSongMap();
        $songs = (new SongSearcher($songMap))->searchByPackName($this->config->get('WantUpdatePackName'));
        foreach ($songs as $song) {
            $this->doAdd($song);
        }
    }

    /**
     * 执行创建页面操作
     * @param Song $song
     */
    public function doAdd(Song $song)
    {
        $songName = $song->getName();
        /** @var \Mediawiki\DataModel\Page $page */
        $page = MWApiServices::getInstance()->newPageGetter()->getFromTitle($songName);
        // 检查页面是否存在，如果存在则跳过该歌曲
        if (count($page->getRevisions()->toArray()) >= 1) {
            echo "Ignore [[{$songName}]], because it exist\n";
            return;
        }
        $packName = (new PackSearcher($this->packMap))->searchByRealName($song->getPackName())->getName();
        // 构建页面 @{
        $page = new Page($songName);
        $section = new Section($songName, 2);
        /**
         * 如果三种难度的note编写者都为同一人则使用模版{{{歌曲信息}}
         * @see http://wiki.arcaea.cn/index.php/%E6%A8%A1%E6%9D%BF:%E6%AD%8C%E6%9B%B2%E4%BF%A1%E6%81%AF
         */
        if ($song->getPastInfo()['chartDesigner'] === $song->getPresentInfo()['chartDesigner'] &&
            $song->getFutureInfo()['chartDesigner'] === $song->getPresentInfo()['chartDesigner']
        ) {
            $chartDesigner = DesignerMap::getRealName($song->getPastInfo()['chartDesigner']);
            $template = new Template('歌曲信息');
            $template->addParam('曲名', $songName);
            $template->addParam('图片', "Songs $songName.jpg");
            $template->addParam('曲包', "[[$packName Collaboration]]");
            $template->addParam('时长', '待填坑');
            $template->addParam('编曲', "[[曲师列表#{$song->getArtist()}|{$song->getArtist()}]]");
            $template->addParam('Past等级', $song->getPastInfo()['rating']);
            $template->addParam('Present等级', $song->getPresentInfo()['rating']);
            $template->addParam('Future等级', $song->getFutureInfo()['rating']);
            $template->addParam('BPM', $song->getBpm());
            $template->addParam('note编写', "[[谱师列表#{$chartDesigner}|{$chartDesigner}]]");
            $template->addParam('PastNote', '待填坑');
            $template->addParam('PresentNote', '待填坑');
            $template->addParam('FutureNote', '待填坑');
            $version = $this->config->has('Version') ? "v{$this->config->get('Version')}<br>": '';
            $time = date('(Y/m/d)', $song->getUpdateTime());
            $template->addParam('更新时间', $version . $time);
            $section->addElement($template);
        } else {
            echo "Unsupported, ignore [[{$songName}]]\n";
            return;
            // TODO
        }
        $page->addElement($section);
        $page->addElement('');

        $section = new Section('解禁方法', 3);
        $table = new Table;
        $table->setTableStyle('class="wikitable" style="text-align:center" cellspacing="0" cellpadding="5"');
        $table->setColumnHead(['Past', 'Present', 'Future']);
        $table->addLine(['', '', '']);
        $section->addElement($table);
        $page->addElement($section);
        $page->addElement('');

        $section = new Section('游戏相关', 3);
        $section->addElement('（待补充）');
        $page->addElement($section);
        $page->addElement('');

        $section = new Section('相关视频', 3);
        $section->addElement('（待补充）');
        $page->addElement($section);
        $page->addElement('');

        $page->addElement("[[分类:{$packName}曲包]]");
        // @}
        // 开始创建页面 @{
        $editor = new Editor($page->getTitle());
        $result = $editor->create($page->getWikitext(), '新增的歌曲资料（由bot进行的编辑）');
        // @}
        if ($result) {
            echo "Successfully created [[{$page->getTitle()}]]\n";
        } else {
            echo "Failed to create [[{$page->getTitle()}]]\n";
        }
    }

    /**
     * 检查配置的曲包是否存在
     * @throws \RuntimeException
     */
    private function checkPackExist() : void
    {
        $packMap = (new PackMapBuilder())->getPackMap();
        if ((new PackSearcher($packMap))->searchByRealName($this->config->get('WantUpdatePackName')) === null) {
            throw new \RuntimeException("Unknown pack: {$this->config->get('WantUpdatePackName')}");
        }
    }
}