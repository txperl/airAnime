<?php
error_reporting(E_ALL);
ini_set('display_errors', 'off');

// on 为开启，其他为关闭
// 数据库类@本地数据库搜索源
$GLOBALS['res_is'] = 'off'; //是否开启本地数据库搜索源(需要 MySQL 数据库)
$GLOBALS['db_server'] = ''; //数据库地址
$GLOBALS['db_username'] = ''; //数据库用户名
$GLOBALS['db_password'] = ''; //数据库密码
$GLOBALS['db_name'] = ''; //数据库名

// BT 下载源搜索
$GLOBALS['bt_is'] = 'on';

// PicSearch(以图搜番)
$GLOBALS['picS_token'] = ''; //trace.moe 申请
?>