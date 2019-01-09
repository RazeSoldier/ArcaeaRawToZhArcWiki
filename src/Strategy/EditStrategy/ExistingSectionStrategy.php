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
    WikitextModel\TableParser,
    World\Map,
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
        // 生成文本 @{
        preg_match('/(?<text>=限时：Empire of Winter=\n(.|\n)*)(=.*=)?/', $source, $matches);
        $text = $matches['text'];
        $table = (new TableParser($text))->getResult();
        foreach ($this->map->getSteps() as $step) {
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