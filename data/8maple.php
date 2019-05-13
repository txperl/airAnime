<?php
// 获取 8maple.ru 的番剧并生成 json && http://8maple.ru/
include("chttochs/convert.php");
$frst = array();
$w = 'http://8maple.ru/category/%E5%88%86%E9%A1%9E/%E5%8B%95%E7%95%AB%E5%88%86%E9%A1%9E/%E5%8B%95%E7%95%AB2019/';
$web = curl_get_contents($w);

$re = '/<a class="last" href=".*?\/page\/(.*?)\/">最舊/m';
preg_match_all($re, $web, $matches);
$page = $matches[1][0];

for ($n = 1; $n <= $page; $n++) {
    $web = curl_get_contents($w . 'page/' . $n);
    $re = '/<a class="clip-link" data-id=".*?" title="(.*?)" href="(.*?)\/"> <span class="clip">/m';
    preg_match_all($re, $web, $rst);

    for ($i = 0; $i < count($rst[1]); $i++) {
        $f = array();
        $link = $rst[2][$i];
        $title = zhconversion_hans($rst[1][$i]);
        $f['title'] = $title;
        $f['link'] = $link;
        array_push($frst, $f);
    }
}

$frst = json_encode($frst, JSON_UNESCAPED_UNICODE);
print_r($frst);

function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');
    curl_setopt($ch, CURLOPT_REFERER, 'http://8maple.ru/');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}
