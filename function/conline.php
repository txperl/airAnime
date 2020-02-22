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
    'qqmh' => [
        'type' => 'getOnline',
        'url' => [
            'https://m.ac.qq.com/search/result?word=' . urlencode($keyTitle)
        ],
        'fun' => ['reg', '', ['/"\/comic\/index\/id\/(.*?)".*?comic-title">(.*?)<\/strong>/ms'], [1], [], ['qqmh']]
    ],
    'manhuagui' => [
        'type' => 'getOnline',
        'url' => [
            'https://www.manhuagui.com/s/' . urlencode($keyTitle) . '.html'
        ],
        'fun' => ['reg', '', ['/<a class="bcover" href="(.*?)" title="(.*?)">/ms'], [1], [], ['manhuagui']]
    ],
    'soman' => [
        'type' => 'getOnline',
        'url' => [
            'https://api.soman.com/soman.ashx?action=getmobilesomancomics&pageindex=1&pagesize=20&keyword=' . urlencode($keyTitle)
        ],
        'fun' => ['json', '', ['Comics{-}{index}{-}Title'], ['Comics{-}{index}{-}Sources{-}{(0)}{-}SourceUrl'], [], []]
    ],
    'mangabz' => [
        'type' => 'getOnline',
        'url' => [
            [
                'GET',
                'http://www.mangabz.com/search?title=' . urlencode($keyTitle)
            ]
        ],
        'fun' => ['reg', '', ['/<h2 class="title">.*?<a href="(.*?)" title="(.*?)">/ms'], [1], [], ['mangabz']]
    ],
    'bilibilimh' => [
        'type' => 'getOnline',
        'url' => [
            [
                'POST',
                'https://manga.bilibili.com/twirp/comic.v1.Comic/Search?device=pc&platform=web',
                ['key_word' => $keyTitle, 'page_num' => 1, 'page_size' => 9]
            ]
        ],
        'fun' => ['json', '', ['data{-}list{-}{index}{-}org_title'], ['data{-}list{-}{index}{-}id'], [], ['bilibilimh']]
    ],
    'dmzjmh' => [
        'type' => 'getOnline',
        'url' => [
            [
                'POST',
                'https://www.dmzj.com/dynamic/o_search/index',
                ['keywords' => $keyTitle]
            ]
        ],
        'fun' => ['reg', '', ['/<li><a   target="_blank" title="(.*?)"href="(.*?)">/ms'], [0], [], ['dmzjmh']]
    ]
];

$my = new animeOnline($arr);
$my->getUrlsData();

$data = $my->doS($keyTitle, false);

$out = new allOutput();

if (@$_POST["kt"]) {
    $out->__doOutputSOnline($data['bilibilimh'], '哔哩哔哩漫画', 'manga.bilibili.com', $keyTitle, 'bilibili.ico');
    $out->__doOutputSOnline($data['qqmh'], '腾讯漫画', 'ac.qq.com', $keyTitle);
    $out->__doOutputSOnline($data['soman'], '搜漫', 'www.soman.com', $keyTitle);
    $out->__doOutputSOnline($data['mangabz'], 'Mangabz', 'www.mangabz.com', $keyTitle);
    $out->__doOutputSOnline($data['dmzjmh'], '动漫之家', 'www.dmzj.com', $keyTitle);
    $out->__doOutputSOnline($data['manhuagui'], '漫画柜', 'www.manhuagui.com', $keyTitle);
} elseif ($_GET["kt"]) {
    $data = delairAnimeHeader($data);
    $out->__doOutputOri($data);
} else {
    echo 'error';
}
