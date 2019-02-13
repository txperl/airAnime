<?php
/**
 * 本文件是為第三方應用預留的. 本插件中不會載入和使用這個文件.
 * 
 * 通過include本文件, 你可以使用中文繁簡轉換函數zhconversion($str, $variant)
 * 如果$_GET['doconversion']或$_POST['doconversion'])有設置, 本文件將獲取$_REQUEST['data']并把其轉換為$_REQUEST['variant']語言后輸出.
 *
 * 你不應該也不需要在Wordpress程序, 插件/主題 或 任何已經包含wp-config.php文件的php程序中包含本文件
 *
 * 本插件目录下convert.html是一个简单的在线繁简转换工具, 使用了本php文件. 当作是本插件的bonus吧 ^_^
 */

global $zh2Hans, $zh2Hant, $zh2TW, $zh2CN, $zh2SG, $zh2HK;
require_once( dirname(__FILE__) . '/ZhConversion.php');

global $wpcc_langs;
$wpcc_langs = array(
	'zh-hans' => array('zhconversion_hans', 'zh2Hans', '简体中文'),
	'zh-hant' => array('zhconversion_hant', 'zh2Hant', '繁體中文'),
	'zh-cn' => array('zhconversion_cn', 'zh2CN', '大陆简体'),
	'zh-hk' => array('zhconversion_hk', 'zh2HK', '港澳繁體'),
	'zh-sg' => array('zhconversion_sg', 'zh2SG', '马新简体'),
	'zh-tw' => array('zhconversion_tw', 'zh2TW', '台灣正體'),
	'zh-mo' => array('zhconversion_hk', 'zh2MO', '澳門繁體'),
	'zh-my' => array('zhconversion_sg', 'zh2MY', '马来西亚简体'),
	'zh' => array('zhconversion_zh', 'zh2ZH', '中文'),
);

if( empty($nochineseconversion) && empty($GLOBALS['nochineseconversion']) ) {
if( ( isset($_GET['dochineseconversion']) || isset($_POST['dochineseconversion']) ) &&
	isset($_REQUEST['data']) )
{	$wpcc_data = get_magic_quotes_gpc() ? stripslashes($_REQUEST['data']) : $_REQUEST['data'];
	$wpcc_variant = str_replace('_', '-', strtolower(trim($_REQUEST['variant'])));
	if( !empty($wpcc_variant) && in_array($wpcc_variant, array('zh-hans', 'zh-hant', 'zh-cn', 'zh-hk', 'zh-sg', 'zh-tw', 'zh-my', 'zh-mo')) )
		echo zhconversion($wpcc_data, $wpcc_variant);
	else echo $wpcc_data;
	die();
}
}

function zhconversion($str, $variant) {
	global $wpcc_langs;
	return $wpcc_langs[$variant][0]($str);
}

function zhconversion_hant($str) {
	global $zh2Hant;
	return strtr($str, $zh2Hant );
}

function zhconversion_hans($str) {
	global $zh2Hans;
	return strtr($str, $zh2Hans);
}

function zhconversion_cn($str) {
	global $zh2Hans, $zh2CN;
	return strtr(strtr($str, $zh2CN), $zh2Hans);
}

function zhconversion_tw($str) {
	global $zh2Hant, $zh2TW;
	return strtr(strtr($str, $zh2TW), $zh2Hant);
}

function zhconversion_sg($str) {
	global $zh2Hans, $zh2SG;
	return strtr(strtr($str, $zh2SG), $zh2Hans);
}

function zhconversion_hk($str) {
	global $zh2Hant, $zh2HK;
	return strtr(strtr($str, $zh2HK), $zh2Hant);
}

function zhconversion_zh($str) {
	return $str;
}

?>