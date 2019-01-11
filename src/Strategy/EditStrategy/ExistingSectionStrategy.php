<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\EditStrategy;

use RazeSoldier\ArcRawToWiki\{
    Config,
    MWApiServices,
    Pack\PackMapBuilder,
    Pack\PackSearcher,
    Pusher\Editor,
    Song\SongMapBuilder,
    Song\SongSearcher,
    Strategy\IStrategy,
    WikitextModel\PageParser,
    WikitextModel\Section,
    WikitextModel\SectionSearcher,
    WikitextModel\Table,
    World\Map
};

class ExistingSectionStrategy implements IStrategy, IEditStrategy
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Map
     */
    private $map;

    public function __construct(Config $config)
    {
        $this->config = $config;
        if (!$this->config->has('WantUpdateSectionName')) {
            throw new \RuntimeException('$cfgWantUpdateSectionName not set');
        }
    }

    public function addMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * 执行策略
     * @return bool
     */
    public function execute() : bool
    {
        // 获取现在的wiki源代码 @{
        /** @var \Mediawiki\DataModel\Page $page */
        $page = MWApiServices::getInstance()->newPageGetter()->getFromTitle( 'World模式梯子详表' );
        $source = $page->getRevisions()->getLatest()->getContent()->getData();
        // @}
        // 找到需要更新的段落位置 @{
        $pageObj = (new PageParser($source))->getResult();
        $searcher = new SectionSearcher($pageObj);
        $sectionPos = $searcher->searchPosByName($this->config->get('WantUpdateSectionName'));
        if (count($sectionPos) > 1) {
            throw new \RuntimeException('Multiple identical section names');
        }
        $sectionPos = $sectionPos[0];
        // @}
        // 生成段落 @{
        $songMap = (new SongMapBuilder)->getSongMap();
        $songSearcher = new SongSearcher($songMap);
        $packMap = (new PackMapBuilder())->getPackMap();
        $packSearcher = new PackSearcher($packMap);
        $table = new Table;
        $table->setTableStyle('class="wikitable mw-collapsible mw-collapsed" border="1" cellspacing="0" cellpadding="5" style="text-align:center"');
        $table->setColumnHead(['级数', '步数', '限制', '奖励']);
        /** @var int $totalHeight 地图的总高度 */
        $totalHeight = 0;
        /** @var int $totalFrag 地图可奖励的Frag数量 */
        $totalFrag = 0;
        foreach ($this->map->getSteps() as $step) {
            $height = $step->getHeight();
            $totalHeight = $totalHeight + $height;
            // 生成奖励文本 @{
            if ($step->getReward() !== null) {
                $value = current($step->getReward());
                switch (key($step->getReward())) {
                    case 'plus_stamina':
                        $reward = "+$value stamina";
                        break;
                    case 'fragment':
                        $totalFrag = $totalFrag + $value;
                        $reward = "$value Fragments";
                        break;
                    case 'character':
                        if ($value == 22) {
                            $reward = '[[搭档#22.光 & 晴音（Seine & Hikari）|光 & 晴音]]';
                        } else {
                            $reward = "character: $value";
                        }
                        $characterReward = $reward;
                        break;
                }
            } else {
                $reward = '';
            }
            // @}
            // 生成限制 @{
            if ($step->isRestrict()) {
                if ($step->getRestrictType() === 'song_id') {
                    $restrict = "[[{$songSearcher->searchByRealName($step->getRestrict())->getName()}]]";
                } elseif ($step->getRestrictType() === 'pack_id') {
                    $restrict = "'''[[{$packSearcher->searchByRealName($step->getRestrict())->getName()} Collaboration]]'''";
                } else {
                    throw new \LogicException('Unknown issue');
                }
            } else {
                $restrict = '';
            }
            // @}
            $data = [$step->getPos(), $height, $restrict, $reward];
            $table->addLine($data);
        }
        // 在表格末尾附加总计数据
        $table->addLine(['总计', $totalHeight, '', "$totalFrag Fragments<br>$characterReward"]);
        $section = new Section($this->config->get('WantUpdateSectionName'), 1);
        // 生成解锁条件
        if ($this->map->getRequireType() === 'pack') {
            $section->addElement("解锁条件：购入[[{$packSearcher->searchByRealName($this->map->getRequireId())->getName()} Collaboration]]曲包");
        }
        $section->addElement('');
        $section->addElement($table);
        $section->addElement('');
        // @}
        // 替换现有的段落 @
        $pageObj->replaceSection($section, $sectionPos);
        $finalText = $pageObj->getWikitext();
        // @}
        // 开始编辑
        $editor = new Editor('World模式梯子详表');
        return $editor->edit($page, $finalText, '填充梯子资料（由bot进行的编辑）');
    }
}