<?php
require_once 'class/bts.class.php';
require_once 'class/output.class.php';
require_once 'functions.php';

if ($_POST["kt"]) {
    $keyTitle = RemoveXSS($_POST["kt"]);
} elseif ($_GET["kt"]) {
    $keyTitle = RemoveXSS($_GET["kt"]);
} else {
    die('error');
}

$bts = new allSearchOnline();
$output = new allOutput();

// 添加搜索源_在线抓取源
$urls = array();
array_push($urls, 'https://mikanani.me/Home/Search?searchstr=' . urlencode($keyTitle)); //0

// 抓取数据并整理_多线程
$oriData = $bts->__getSDdata($urls);

$frst = array();
$frst['mgjh'] = $oriData[0];

// 结构化数据整理_在线抓取源+本地源
$data = $bts->__doS($frst, $keyTitle);

if ($_POST["kt"]) {
    $output->__doOutputSOnline($data['mgjh'], '蜜柑计划', 'mikanani.me', $keyTitle);
    $output->__doOutputBTS($data['agefans'], 'AGE动漫&百度云', 'donghua.agefans.com', $keyTitle);
} elseif ($_GET["kt"]) {
    $data = delairAnimeHeader($data);
    $output->__doOutputOri($data);
} else {
    echo 'error';
}
?>