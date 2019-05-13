<?php
require_once 'class/conline.class.php';
require_once 'class/output.class.php';
require_once 'functions.php';

if ($_POST["kt"]) {
    $keyTitle = RemoveXSS($_POST["kt"]);
} elseif ($_GET["kt"]) {
    $keyTitle = RemoveXSS($_GET["kt"]);
} else {
    die('error');
}

$conline = new allSearchOnline();
$output = new allOutput();

// 添加搜索源_在线抓取源
$urls = array();
array_push($urls, 'http://m.ac.qq.com/search/result?word=' . urlencode($keyTitle)); //0
array_push($urls, 'https://www.manhuagui.com/s/' . urlencode($keyTitle) . '.html'); //1
array_push($urls, 'http://m.dm5.com/search?title=' . urlencode($keyTitle)); //2
array_push($urls, 'http://www.manhuatai.com/getjson.shtml?q=' . urlencode($keyTitle)); //3

// 抓取数据并整理_多线程
$oriData = $conline->__getSDdata($urls);

$frst = array();
$frst['acqq'] = $oriData[0];
$frst['manhuagui'] = $oriData[1];
$frst['dm5'] = $oriData[2];
$frst['manhuatai'] = $oriData[3];
$frst['dmzj'] = curl_get_contents_form_post('https://www.dmzj.com/dynamic/o_search/index', 'keywords=' . urlencode($keyTitle));

// 结构化数据整理_在线抓取源+本地源
$data = $conline->__doS($frst, $keyTitle);

if ($_POST["kt"]) {
    $output->__doOutputSOnline($data['dmzj'], '动漫之家', 'www.dmzj.com', $keyTitle);
    $output->__doOutputSOnline($data['manhuagui'], '漫画柜', 'www.manhuagui.com', $keyTitle);
    $output->__doOutputSOnline($data['dm5'], '动漫屋', 'www.dm5.com', $keyTitle);
    $output->__doOutputSOnline($data['manhuatai'], '漫画台', 'www.manhuatai.com', $keyTitle);
    $output->__doOutputSOnline($data['acqq'], '腾讯漫画', 'ac.qq.com', $keyTitle);
} elseif ($_GET["kt"]) {
    $data = delairAnimeHeader($data);
    $output->__doOutputOri($data);
} else {
    echo 'error';
}
?>