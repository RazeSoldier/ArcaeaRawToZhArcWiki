<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WorldRawParser;

use RazeSoldier\ArcRawToWiki\World\Step;

/**
 * 用于解析世界模式里梯子的台阶
 * @package RazeSoldier\ArcRawToWiki\WorldRawParser
 */
class StepParser
{
    /**
     * @var array 存储一系列数据的数组
     */
    private $data;

    /**
     * @var Step 解析后的结果
     */
    private $result;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->parse();
    }

    private function parse()
    {
        $step = new Step;
        $step->setPos($this->data['position']);
        $step->setHeight($this->data['capture']);
        // 设置限制条件 @{
        if (isset($this->data['restrict_type'])) {
            $step->setIsRestrict(true);
            $step->setRestrictType($this->data['restrict_type']);
            if (isset($this->data['restrict_id'])) {
                $step->setRestrict($this->data['restrict_id']);
            }
        } else {
            $step->setIsRestrict(false);
        }
        // @}
        // 是否有加耐力的奖励
        if (isset($this->data['plus_stamina_value'])) {
            $step->setReward(['plus_stamina' => $this->data['plus_stamina_value']]);
        }
        // 是否有碎片奖励
        if (isset($this->data['items'])) {
            $type = $this->data['items'][0]['type'];
            if ($type === 'fragment') {
                $value = $this->data['items'][0]['amount'];
            } elseif ($type === 'character') {
                $value = $this->data['items'][0]['id'];
            } else {
                throw new \RuntimeException('May be issued unknown item');
            }
            $step->setReward([$type => $value]);
        }
        $this->result = $step;
    }

    public function getResult() : Step
    {
        return $this->result;
    }
}