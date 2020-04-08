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
    'iqiyi' => [
        'type' => 'getOnline',
        'url' => [
            'https://so.iqiyi.com/so/q_' . urlencode($keyTitle) . '_ctg_%E5%8A%A8%E6%BC%AB_t_3_page_1_p_1_qc_0_rd__site_iqiyi_m_1_bitrate_?af=true',
            'https://so.iqiyi.com/so/q_' . urlencode($keyTitle) . '_ctg_%E5%8A%A8%E6%BC%AB_t_5_page_1_p_1_qc_0_rd__site_iqiyi_m_1_bitrate_?af=true'
        ],
        'fun' => ['reg', '', ['/qy-search-result-item vertical-pic.*?title="(.*?)".*?href="(.*?)"/ms'], [0], [], []]
    ],
    'qqtv' => [
        'type' => 'getOnline',
        'url' => [
            'https://v.qq.com/x/search/?ses=last_query%3D' . urlencode($keyTitle) . '%26tabid_list%3D0%7C4%7C7%7C2%7C1%7C3%7C11%7C6%7C12%7C21%7C14%7C5%7C17%7C13%7C15%7C20%26tabname_list%3D%E5%85%A8%E9%83%A8%7C%E5%8A%A8%E6%BC%AB%7C%E5%85%B6%E4%BB%96%7C%E7%94%B5%E8%A7%86%E5%89%A7%7C%E7%94%B5%E5%BD%B1%7C%E7%BB%BC%E8%89%BA%7C%E6%96%B0%E9%97%BB%7C%E7%BA%AA%E5%BD%95%E7%89%87%7C%E5%A8%B1%E4%B9%90%7C%E6%B1%BD%E8%BD%A6%7C%E4%BD%93%E8%82%B2%7C%E9%9F%B3%E4%B9%90%7C%E6%B8%B8%E6%88%8F%7C%E8%B4%A2%E7%BB%8F%7C%E6%95%99%E8%82%B2%7C%E6%AF%8D%E5%A9%B4%26resolution_tabid_list%3D0%7C1%7C2%7C3%7C4%7C5%26resolution_tabname_list%3D%E5%85%A8%E9%83%A8%7C%E6%A0%87%E6%B8%85%7C%E9%AB%98%E6%B8%85%7C%E8%B6%85%E6%B8%85%7C%E8%93%9D%E5%85%89%7CVR&q=' . urlencode($keyTitle) . '&needCorrect=' . urlencode($keyTitle) . '&stag=4&filter=sort%3D0%26pubfilter%3D0%26duration%3D0%26tabid%3D4%26resolution%3D0'
        ],
        'fun' => ['reg', '', ["/curPlaysrc: 'qq'.*?title: '(.*?)';.*?id: '(.*?)';/ms"], [0], [], ['qqtv']]
    ],
    'acfun' => [
        'type' => 'getOnline',
        'url' => [
            'https://www.acfun.cn/rest/pc-direct/search/bgm?keyword=' . urlencode($keyTitle) . '&pCursor=1'
        ],
        'fun' => ['json', '', ['bgmList{-}{index}{-}bgmTitle'], ['bgmList{-}{index}{-}bgmId'], [], ['acfun']]
    ],
    'qinmei' => [
        'type' => 'getOnline',
        'url' => [
            'https://qinmei.video/api/v2/animates?type=queryAnimate&title=' . urlencode($keyTitle) . '&page=1&size=20&sortBy=updatedAt&sortOrder=-1'
        ],
        'fun' => ['json', '', ['data{-}list{-}{index}{-}title'], ['data{-}list{-}{index}{-}slug'], [], ['qinmei']]
    ],
    'anime1' => [
        'type' => 'getLocal',
        'uri' => '../data/anime1.json',
        'thred' => [0.5, 0.45]
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
    '8maple' => [
        'type' => 'getLocal',
        'uri' => '../data/8maple.json',
        'thred' => [0.5, 0.45]
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
    ],
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
        $out->__doOutputSOnline($data['anime1'], 'Anime1', 'anime1.me', $keyTitle, 'anime1.ico');
        $out->__doOutputSOnline($data['bimibimi'], 'Bimibimi', 'www.bimibimi.tv', $keyTitle, 'bimibimi.ico');
    }
    $out->__doOutputSOnline($data['qinmei'], 'Qinmei', 'qinmei.video', $keyTitle, 'qinmei.png');
    $out->__doOutputSOnline($data['nicotv'], '妮可动漫', 'www.nicotv.me', $keyTitle, 'nicotv.ico');
    if ($isLocal) {
        $out->__doOutputSOnline($data['opacg'], '欧派动漫', 'www.opacg.com', $keyTitle, 'opacg.png');
        $out->__doOutputSOnline($data['halitv'], '哈哩哈哩', 'www.halitv.com', $keyTitle, 'halitv.ico');
        $out->__doOutputSOnline($data['yhdm'], '樱花动漫', 'www.imomoe.in', $keyTitle, 'none.png');
    }
    if ($isLocal) {
        $out->__doOutputSOnline($data['8maple'], '枫林网', '8maple.ru', $keyTitle, '8maple.ico');
    }
    $out->__doOutputSOnline($data['iqiyi'], '爱奇艺', 'www.iqiyi.com', $keyTitle, 'iqiyi.png');
    $out->__doOutputSOnline($data['qqtv'], '腾讯视频', 'v.qq.com', $keyTitle);
} elseif ($_GET["kt"]) {
    // API Json 输出
    $data = delairAnimeHeader($data);
    $out->__doOutputOri($data);
} else {
    echo 'error';
}
