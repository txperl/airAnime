<?php
include("../chttochs/convert.php");

// 配置项开始
// 抓取失败 >= X 次停止尝试
define('maxFailedNum', 6);
// AGEFANS 已被墙，若在墙内抓取数据需先配置代理
define('proxyURL', '127.0.0.1');
define('proxyPORT', '1080');
// 配置项结束

getAnime1();
getNewBgm();
getBimibimi();
getAgeFuns();

function getAnime1()
{
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

    if (saveData($frst, 'anime1')) {
        echo '[done] anime1<br>';
    } else {
        echo '[fail] anime1<br>';
    }
}

function getBimibimi()
{
    $oriData = file_get_contents('../bimibimi.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'http://www.bimibimi.tv/bangumi/bi/', '#end');
    $errorNum = 0;
    for ($i = $lastIndex + 1; $i != 0; $i++) {
        $f = array();
        $link = 'http://www.bimibimi.tv/bangumi/bi/' . $i;
        $data = curl_get_contents($link);
        if (substr_count($data, '跳转</a> 等待时间：') == 0) {
            $title = getSubstr($data, ' <a class="current" title="', '" href="');
            $url = 'http://www.bimibimi.tv/bangumi/bi/' . $i;
            $f['title'] = $title;
            $f['link'] = $url;
            array_push($rst, $f);
        } else {
            $errorNum++;
            if ($errorNum >= maxFailedNum) {
                break;
            }
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'bimibimi')) {
        echo '[done] bimibimi<br>';
    } else {
        echo '[fail] bimibimi<br>';
    }
}

function getAgeFuns()
{
    $oriData = file_get_contents('../agefans.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'http://donghua.agefans.com/detail/', '#end');
    $errorNum = 0;
    for ($i = $lastIndex + 1; $i != 0; $i++) {
        $f = array();
        $link = 'http://donghua.agefans.com/detail/' . $i;
        $data = curl_get_contents_proxy($link);

        if (substr_count($data, 'Server Error (500)') == 0) {
            $title = getSubstr($data, '<h4 class="detail_imform_name"> ', ' </h4>');
            $url = 'http://donghua.agefans.com/detail/' . $i;
            $f['title'] = $title;
            $f['link'] = $url;
            array_push($rst, $f);
        } else {
            $errorNum++;
            if ($errorNum >= maxFailedNum) {
                break;
            }
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'agefans')) {
        echo '[done] agefans<br>';
    } else {
        echo '[fail] agefans<br>';
    }
}

function getNewBgm()
{
    $frst = curl_get_contents('https://api.tls.moe/?app=bangumi&key=calendar');

    if (saveData($frst, '201904')) {
        echo '[done] newBgm<br>';
    } else {
        echo '[fail] newBgm<br>';
    }
}

function saveData($c, $type)
{
    $myfile = fopen($type . '.json', 'w') or die(0);
    fwrite($myfile, $c);
    fclose($myfile);
    return 1;
}

function curl_get_contents($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);            //设置访问的url地址
    //curl_setopt($ch,CURLOPT_HEADER,1);            //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);           //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER, $url);        //设置 referer
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);      //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}

function curl_get_contents_proxy($url)
{
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, 'https://donghua.agefans.com/');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($ch, CURLOPT_PROXY, proxyURL);
    curl_setopt($ch, CURLOPT_PROXYPORT, proxyPORT);

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
