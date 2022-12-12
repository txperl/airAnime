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

$isLocal = true;

$arr = [
    'bilibili' => [
        'type' => 'getOnline',
        'url' => [
            'https://api.bilibili.com/x/web-interface/search/all?jsonp=jsonp&keyword=' . urlencode($keyTitle)
        ],
        'fun' => ['json', '', ['data{-}result{-}media_bangumi{-}{index}{-}title'], ['data{-}result{-}media_bangumi{-}{index}{-}media_id'], [], ['bilibili']]
    ],
    'nicotv' => [
        'type' => 'getOnline',
        'url' => [
            'http://www.nicotv.me/video/search/' . urlencode($keyTitle) . '.html'
        ],
        'fun' => ['reg', '', ['/<h2 class="text-nowrap ff-text-right">.*?href="(.*?)" title="(.*?)">/ms'], [1], [], ['nicotv']]
    ],
    'acfun' => [
        'type' => 'getOnline',
        'url' => [
            'https://www.acfun.cn/rest/pc-direct/search/bgm?keyword=' . urlencode($keyTitle) . '&pCursor=1'
        ],
        'fun' => ['json', '', ['bgmList{-}{index}{-}bgmTitle'], ['bgmList{-}{index}{-}bgmId'], [], ['acfun']]
    ],
    'bimibimi' => [
        'type' => 'getLocal',
        'uri' => '../data/bimibimi.json',
        'thred' => [0.5, 0.45]
    ],
    'opacg' => [
        'type' => 'getLocal',
        'uri' => '../data/opacg.json',
        'thred' => [0.55, 0.5]
    ],
    'yhdm' => [
        'type' => 'getLocal',
        'uri' => '../data/yhdm.json',
        'thred' => [0.5, 0.45]
    ],
    'halitv' => [
        'type' => 'getLocal',
        'uri' => '../data/halitv.json',
        'thred' => [0.5, 0.45]
    ]
];

$my = new animeOnline($arr);
$my->getUrlsData();

$data = $my->doS($keyTitle, $isLocal);

$out = new allOutput();


if (@$_POST["kt"]) {
    // 输出结果
    $out->__doOutputSOnline($data['acfun'], 'AcFun', 'www.acfun.cn', $keyTitle, 'acfun.ico');
    $out->__doOutputSOnline($data['bilibili'], '哔哩哔哩', 'www.bilibili.com', $keyTitle, 'bilibili.ico');
    if ($isLocal) {
        $out->__doOutputSOnline($data['bimibimi'], 'Bimibimi', 'www.bimiacg.net', $keyTitle, 'bimibimi.ico');
    }
    $out->__doOutputSOnline($data['nicotv'], '妮可动漫', 'www.nicotv.me', $keyTitle, 'nicotv.ico');
    if ($isLocal) {
        $out->__doOutputSOnline($data['opacg'], '欧派动漫', 'www.opacg.com', $keyTitle, 'opacg.png');
        $out->__doOutputSOnline($data['halitv'], '哈哩哈哩', 'www.halitv.com', $keyTitle, 'halitv.ico');
        $out->__doOutputSOnline($data['yhdm'], '樱花动漫', 'www.imomoe.in', $keyTitle, 'none.png');
    }
} elseif ($_GET["kt"]) {
    // API Json 输出
    $data = delairAnimeHeader($data);
    $out->__doOutputOri($data);
} else {
    echo 'error';
}
