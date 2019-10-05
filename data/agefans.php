<?php
// 抓取 agefans 数据并输出 json && http://donghua.agefans.com/
// 2004 5
// 2005 10
// 2006 20
// 2007 20
// 2008 30
// 2009 30
// 2010 40
// 2011 60
// 2012 90
// 2013 120
// 2014 150
// 2015 140
// 2016 210
// 2017 210
// 2018 300
// 2019 160
set_time_limit(0);
$rst = array();
for ($i = 190; $i <= 310; $i++) {
    $year = 2019;
    if (strlen($i) == 1) {
        $a = '000' . $i;
    }
    if (strlen($i) == 2) {
        $a = '00' . $i;
    }
    if (strlen($i) == 3) {
        $a = '0' . $i;
    }
    if (strlen($i) == 4) {
        $a = $i;
    }

    $f = array();
    $link = 'http://donghua.agefans.com/detail/' . $year . $a;
    $data = curl_get_contents($link);
    if (substr_count($data, 'Server Error (500)') == 0) {
        $title = getSubstr($data, '<h4 class="detail_imform_name"> ', ' </h4>');
        $url = 'http://donghua.agefans.com/detail/' . $year . $a;
        $f['title'] = $title;
        $f['link'] = $url;
        array_push($rst, $f);
    }
}
$frst = json_encode($rst, JSON_UNESCAPED_UNICODE);
echo $frst;

function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');
    curl_setopt($ch, CURLOPT_REFERER, 'http://donghua.agefans.com/');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}
function getSubstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr, $left);
    //echo '<br>右边:'.$right;
    if ($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}
?>