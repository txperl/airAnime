<?php
// 获取 anime1.me 的番剧并生成 json && https://anime1.me/
include("chttochs/convert.php");
$frst = array();
$web = file_get_contents('https://anime1.me/');
$num = substr_count($web, '<td class="column-1">');
preg_match_all('/<td class="column-1"><a href="(.*?)">(.*?)<\/a><\/td>/', $web, $rst);
for ($i = 0; $i < count($rst[1]); $i++) {
    $f = array();
    $link = 'https://anime1.me' . $rst[1][$i];
    $title = zhconversion_hans($rst[2][$i]);
    $f['title'] = $title;
    $f['link'] = $link;
    array_push($frst, $f);
}
$frst = json_encode($frst, JSON_UNESCAPED_UNICODE);
print_r($frst);
?>