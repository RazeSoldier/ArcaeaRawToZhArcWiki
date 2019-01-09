<?php
/**
 * Author: RazeSoldier (razesoldier@outlook.com)
 * License: GPL-2.0 or later
 */

namespace RazeSoldier\ArcRawToWiki\Strategy\EditStrategy;

use RazeSoldier\ArcRawToWiki\{
    Strategy\IStrategy,
    World\Map,
};

interface IEditStrategy extends IStrategy
{
    public function addMap(Map $map);
}