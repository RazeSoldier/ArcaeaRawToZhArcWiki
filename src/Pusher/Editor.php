<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Pusher;

use RazeSoldier\ArcRawToWiki\MWApiServices;
use Mediawiki\DataModel\{
    Content,
    EditInfo,
    Page,
    PageIdentifier,
    Revision,
    Title,
};

/**
 * 用于编辑维基上的页面
 * @package RazeSoldier\ArcRawToWiki\Pusher
 */
class Editor
{
    /**
     * @var string 要编辑的页面标题
     */
    private $title;

    /**
     * Editor constructor.
     * @param string $title 要编辑的页面标题
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * 保存编辑
     * @param Page $page
     * @param string $text 要保存的文本
     * @param string|null $summary 编辑摘要
     * @return bool 如果保存成功，返回TRUE；否则，返回FALSE
     */
    public function edit(Page $page, string $text, string $summary = null) : bool
    {
        $content = new Content($text);
        $revision = new Revision($content, $page->getPageIdentifier());
        if ($summary !== null) {
            $summary = new EditInfo($summary);
        }
        return MWApiServices::getInstance()->newRevisionSaver()->save($revision, $summary);
    }

    /**
     * 创建一个页面
     * @param string $text 要保存的文本
     * @param string|null $summary 编辑摘要
     * @return bool 如果创建成功，返回TRUE；否则，返回FALSE
     */
    public function create(string $text, string $summary = null) : bool
    {
        $content = new Content($text);
        $title = new Title($this->title);
        $identifier = new PageIdentifier($title);
        $revision = new Revision($content, $identifier);
        if ($summary !== null) {
            $summary = new EditInfo($summary);
        }
        return MWApiServices::getInstance()->newRevisionSaver()->save($revision, $summary);
    }
}