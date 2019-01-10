<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\WikitextModel;

/**
 * 用于解析页面代码（wikitext）
 * @package RazeSoldier\ArcRawToWiki\WikitextModel
 */
class PageParser
{
    /**
     * @var string 未经处理的wikitext
     */
    private $text;

    /**
     * @var Page
     */
    private $page;

    /**
     * @var string|null 页面的标题
     */
    private $pageTitle;

    /**
     * PageParser constructor.
     * @param string $text 需要解析的页面维基文本
     * @param string|null $pageTitle
     */
    public function __construct(string $text, string $pageTitle = null)
    {
        $this->text = $text;
        $this->pageTitle = $pageTitle;
        $this->parse();
    }

    private function parse()
    {
        $lines = explode("\n", $this->text);
        // 初始化Page实例 @{
        $pageObj = new Page($this->pageTitle);
        // @}
        /** @var bool $inSection 当前的指针是否在段落内 */
        $inSection = false;
        /** @var bool $inTitle 当前的指针是否在标题内 */
        $inTitle = false;
        /** @var int $sectionCount 段落的数量 */
        $sectionCount = 0;
        /** @var int $sectionAddedCount 已经加入Page实例段落的数量 */
        $sectionAddedCount = 0;
        /** @var int $i 从0开始的行号 */
        /** @var string $lineText 行文本 */
        foreach ($lines as $i => $lineText) {
            // 尝试捕捉段落的标题开始的符号
            if (preg_match('/^(?<symbol>=+)[\s|\W|\w]*/', $lineText, $matches)) {
                $symbolCount = strlen($matches['symbol']);
                // 尝试捕捉段落的标题结束的符号
                if (preg_match('/[\s|\W|\w]*(?<symbol>=+)$/', $lineText, $matches)) {
                    // 如果前面的=符号数量等于后面=符号的数量
                    if ($symbolCount === strlen($matches['symbol'])) {
                        preg_match('/^=+\s*(?<text>[\W|\w]*)\s*=+$/', $lineText, $matches);
                        if ($inSection) {
                            /** @var Section $sectionObj */
                            $pageObj->addElement($sectionObj);
                            $sectionAddedCount++;
                        } else {
                            $inSection = true;
                        }
                        $sectionObj = new Section($matches['text'], $symbolCount);
                        $sectionCount++;
                        $inTitle = true;
                    }
                }
            }
            // 如果指针在一个段落里
            if ($inSection) {
                // 如果指针在标题里，则跳过
                if ($inTitle) {
                    $inTitle = false;
                    continue;
                }
                $sectionObj->addElement($lineText);
            } else {
                $pageObj->addElement($lineText);
            }
        } // End foreach
        if ($sectionCount !== $sectionAddedCount) {
            $pageObj->addElement($sectionObj);
        }
        $this->page = $pageObj;
    }

    public function getResult() : Page
    {
        return $this->page;
    }
}