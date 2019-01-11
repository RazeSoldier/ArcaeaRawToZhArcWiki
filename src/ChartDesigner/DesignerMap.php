<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\ChartDesigner;

/**
 * 谱师马甲到谱师真实名字的映射
 * @package RazeSoldier\ArcRawToWiki\ChartDesigner
 * @note 该类属于硬编码，由于技术限制暂时是无法改善的技术债务
 */
class DesignerMap
{
    public const MAP = [
        '闇運' => 'Kurorak',
        'Kurorak' => 'Kurorak',
        'Got More Taro?' => 'TaroNuke',
        'DX譜面作者フルメタルNitro' => 'Nitro',
        'Groove 東星' => 'Toaster',
    ];

    public const KNOWN_DESIGNER = [
        'Nitro',
        'Toaster',
        'Kurorak',
        'k//eternal',
        'TaroNuke',
    ];

    /**
     * 转换马甲为谱师的真实名字
     * @param string $sockpuppet 马甲
     * @return string|null 如果成功返回谱师的真实名字，反之返回NULL
     */
    public static function getRealName(string $sockpuppet) :? string
    {
        if (!isset(self::MAP[$sockpuppet])) {
            return null;
        }
        return self::MAP[$sockpuppet];
    }
}