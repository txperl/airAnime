<?php
require_once 'functions.php';
require_once 'small/extraFuns.php';
require_once 'class/aonline.class.php';
require_once 'class/output.class.php';

if (@$_POST["kt"]) {
    $keyTitle = RemoveXSS($_POST["kt"]);
} elseif ($_GET["kt"]) {
    $keyTitle = RemoveXSS($_GET["kt"]);
} else {
    die('error');
}

$arr = [
    'mgjh' => [
        'type' => 'getOnline',
        'url' => [
            'https://mikanani.me/Home/Search?searchstr=' . urlencode($keyTitle)
        ],
        'fun' => ['reg', '', ['/<a href="\/Home\/Bangumi\/(.*?)".*?class="an-text" title="(.*?)"/ms'], [1], [], ['mgjh']]
    ],
    'agefans' => [
        'type' => 'getLocal',
        'uri' => '../data/agefans.json',
        'thred' => [0.5, 0.45]
    ]
];

$my = new animeOnline($arr);
$my->getUrlsData();

$data = $my->doS($keyTitle);

$out = new allOutput();

if (@$_POST["kt"]) {
    $out->__doOutputSOnline($data['mgjh'], '蜜柑计划', 'mikanani.me', $keyTitle, 'mgjh.ico');
    $out->__doOutputBTS($data['agefans'], 'AGE动漫&百度云', 'donghua.agefans.com', $keyTitle, 'agefuns.ico');
} elseif ($_GET["kt"]) {
    $data = delairAnimeHeader($data);
    $out->__doOutputOri($data);
} else {
    echo 'error';
}
