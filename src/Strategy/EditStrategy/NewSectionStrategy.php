<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\EditStrategy;

use RazeSoldier\ArcRawToWiki\{
    Config,
    MWApiServices,
    Pusher\Editor,
    Strategy\IStrategy,
    WikitextModel\Section,
    WikitextModel\Table,
};
use RazeSoldier\ArcaeaDataModel\World\Map;

class NewSectionStrategy implements IStrategy, IEditStrategy
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
    }

    public function addMap(Map $map)
    {
        $this->map = $map;
    }

    /**
     * 执行策略
     * @return mixed
     */
    public function execute()
    {
        /** @var \Mediawiki\DataModel\Page $page */
        $page = MWApiServices::getInstance()->newPageGetter()->getFromTitle( 'World模式梯子详表' );
        $source = $page->getRevisions()->getLatest()->getContent()->getData();
        // 构建表格 @{
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
                        if ($value == 20) {
                            $reward = '[[搭档#20..E7.88.B1.E6.89.98_.26_.E9.9C.B2.E5.A8.9C_-.E5.86.AC-.EF.BC.88Eto_.26_Luna_-Winter-.EF.BC.89|爱托 & 露娜 -冬-]]';
                        } else {
                            $reward = "character: $value";
                        }
                        break;
                }
            } else {
                $reward = '';
            }
            // @}
            // 生成限制 @{
            $restrict = $step->isRestrict() ? $step->getRestrict() : '';
            // @}
            $data = [$step->getPos(), $height, $restrict, $reward];
            $table->addLine($data);
        }
        // 在表格末尾附加总计数据
        $table->addLine(['总计', $totalHeight, '', $totalFrag]);
        // @}
        // 构建新的段落 @{
        $section = new Section('限时：Groovecoaster', 1);
        $section->addElement('解锁条件：购入[[Groove Coaster Collaboration]]');
        $section->addElement('');
        $section->addElement($table);
        // @}
        // 开始编辑
        $finalText = $source . "\n" . $section->getWikitext();
        $editor = new Editor('World模式梯子详表');
        return $editor->edit($page, $finalText, '填充梯子资料（由bot进行的编辑）');
    }
}