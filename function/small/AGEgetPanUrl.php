<?php
require_once '../functions.php';

$url = @$_POST['url'];

if ($url) {
    $rst = array();
    $data = file_get_contents($url);
    $title = getSubstr($data, '<h4 class="detail_imform_name">', '</h4>');
    $link = 'https://www.agefans.tv' . getSubstr($data, '<a class="res_links_a" href="', '" rel="');
    if (substr_count($data, '<span class="res_links_pswd">') == 0) {
        $psw = '没有哦~';
    } else {
        $psw = getSubstr($data, '<span class="res_links_pswd">', ')</span>');
    }

    $rst['title'] = $title;
    $rst['link'] = $link;
    $rst['psw'] = $psw;

    $rst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    print_r($rst);
}
?>