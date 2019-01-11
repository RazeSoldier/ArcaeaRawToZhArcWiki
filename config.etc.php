<?php
/**
 * 示例配置文件
 * @file
 */

// 脚本执行的逻辑名字
$cfgStrategy  = 'UpdateWorldMapData';

# AddSongByParkName @{
// 想要更新的曲包名
$cfgWantUpdatePackName = '';
// 曲包加入的版本号
$cfgVersion = '';
# @}

# UpdateWorldMapData @{
// 想要更新的段落名
// 在ExistingSectionStrategy下生效
$cfgWantUpdateSectionName = '';

// 想要解析的RAW的名字，可解析的列于此：https://github.com/esterTion/Arcaea_World_Mode_Raw_Data/tree/master/data
// 请移除文件扩展名
$cfgRawName = '0-le_winter';
# @}

// 维基的API接入点
$cfgApiEntry = 'http://wiki.arcaea.cn/api.php';

// 于维基注册的用户的用户名和密码
$cfgUsername = '';
$cfgPassword = '';