<?php
include("../chttochs/convert.php");

// 配置项开始
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 3600);
set_time_limit(3600);
// 抓取失败 >= X 次停止尝试
define('maxFailedNum', 6);
// define('proxyURL', '192.168.1.122');
// define('proxyPORT', '1087');
// 配置项结束

getNewBgm();
getBimibimi();
getAgeFuns();
getOPAcg();
getYHDM();
getHalitv();
getMoeTV();

// 一直抓直到错误 x 次
function getBimibimi()
{
    $sum = 0;
    $oriData = file_get_contents('../bimibimi.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'http://www.bimibimi.me/bangumi/bi/', '#end');
    $errorNum = 0;
    for ($i = $lastIndex + 1; $i != 0; $i++) {
        $f = array();
        $link = 'http://www.bimibimi.me/bangumi/bi/' . $i;
        $data = curl_get_contents($link);
        if (substr_count($data, '跳转</a> 等待时间：') == 0) {
            $title = getSubstr($data, ' <a class="current" title="', '" href="');
            $url = 'http://www.bimibimi.me/bangumi/bi/' . $i;
            $f['title'] = $title;
            $f['link'] = $url;
            array_push($rst, $f);
            $sum++;
        } else {
            $errorNum++;
            if ($errorNum >= maxFailedNum) {
                break;
            }
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'bimibimi')) {
        echo '[done] bimibimi +' . $sum . '<br>';
    } else {
        echo '[fail] bimibimi<br>';
    }
}

// 一直抓直到错误 x 次
function getAgeFuns()
{
    $sum = 0;
    $oriData = file_get_contents('../agefans.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'https://www.agefans.tv/detail/', '#end');
    $errorNum = 0;

    for ($i = $lastIndex + 1; $i != 0; $i++) {
        $f = array();
        $link = 'https://www.agefans.tv/detail/' . $i;
        $data = curl_get_contents($link);

        if ($data) {
            $title = getSubstr($data, '<h4 class="detail_imform_name">', '</h4>');
            $url = 'https://www.agefans.tv/detail/' . $i;
            $f['title'] = $title;
            $f['link'] = $url;
            array_push($rst, $f);
            $sum++;
        } else {
            $errorNum++;
            if ($errorNum >= maxFailedNum) {
                break;
            }
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'agefans')) {
        echo '[done] agefans +' . $sum . '<br>';
    } else {
        echo '[fail] agefans<br>';
    }
}

// 一次抓 100 个
function getOPAcg()
{
    $sum = 0;
    $oriData = file_get_contents('../opacg.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'https://www.opacg.com/voddetail/', '#end');

    $tmpURL = array();
    $tmpURLIndex = array();
    for ($i = $lastIndex + 1; $i <= $lastIndex + 100; $i++) {
        array_push($tmpURL, 'https://www.opacg.com/voddetail/' . $i);
        array_push($tmpURLIndex, $i);
        if ($i % 50 == 0 || $i == $lastIndex + 100) {
            $webDatas = curl_multi($tmpURL);
            foreach ($webDatas as $key => $data) {
                if (substr_count($data, '<a href="/voddetail/') != 0) {
                    $title = getSubstr($data, '<a href="/voddetail/' . $tmpURLIndex[$key] . '/">', '</a></h1><ul ');
                    $url = 'https://www.opacg.com/voddetail/' . $tmpURLIndex[$key];
                    $f['title'] = $title;
                    $f['link'] = $url;
                    array_push($rst, $f);
                    $sum++;
                }
            }
            $tmpURL = array();
            $tmpURLIndex = array();
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'opacg')) {
        echo '[done] opacg +' . $sum . '<br>';
    } else {
        echo '[fail] opacg<br>';
    }
}

// 一次抓 100 个
function getYHDM()
{
    $sum = 0;
    $oriData = file_get_contents('../yhdm.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'http://www.imomoe.in/view/', '.html#end');

    $tmpURL = array();
    $tmpURLIndex = array();
    for ($i = $lastIndex + 1; $i <= $lastIndex + 100; $i++) {
        array_push($tmpURL, 'http://www.imomoe.in/view/' . $i . '.html');
        array_push($tmpURLIndex, $i);
        if ($i % 50 == 0 || $i == $lastIndex + 100) {
            $webDatas = curl_multi($tmpURL);
            foreach ($webDatas as $key => $data) {
                $data = mb_convert_encoding($data, 'utf-8', 'gbk');
                if (substr_count($data, '<span class="names">') != 0) {
                    $title = getSubstr($data, '<span class="names">', '</span></h1>');
                    $url = 'http://www.imomoe.in/view/' . $tmpURLIndex[$key] . '.html';
                    $f['title'] = $title;
                    $f['link'] = $url;
                    array_push($rst, $f);
                    $sum++;
                }
            }
            $tmpURL = array();
            $tmpURLIndex = array();
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'yhdm')) {
        echo '[done] yhdm +' . $sum . '<br>';
    } else {
        echo '[fail] yhdm<br>';
    }
}

// 一次抓 100 个
function getHalitv()
{
    $sum = 0;
    $oriData = file_get_contents('../halitv.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'https://www.halitv.com/detail/', '.html#end');

    $tmpURL = array();
    $tmpURLIndex = array();
    for ($i = $lastIndex + 1; $i <= $lastIndex + 100; $i++) {
        array_push($tmpURL, 'https://www.halitv.com/detail/' . $i . '.html');
        array_push($tmpURLIndex, $i);
        if ($i % 50 == 0 || $i == $lastIndex + 100) {
            $webDatas = curl_multi($tmpURL);
            foreach ($webDatas as $key => $data) {
                if (substr_count($data, '<div class="txt-title"><h1>') != 0) {
                    $title = getSubstr($data, '<div class="txt-title"><h1>', '</h1><span>');
                    $url = 'https://www.halitv.com/detail/' . $tmpURLIndex[$key] . '.html';
                    $f['title'] = $title;
                    $f['link'] = $url;
                    array_push($rst, $f);
                    $sum++;
                }
            }
            $tmpURL = array();
            $tmpURLIndex = array();
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'halitv')) {
        echo '[done] halitv +' . $sum . '<br>';
    } else {
        echo '[fail] halitv<br>';
    }
}

// 一次抓 100 个
function getMoeTV()
{
    $sum = 0;
    $oriData = file_get_contents('../moetv.json');
    $rst = json_decode($oriData, true);
    $lastIndex = getSubstr($rst[count($rst) - 1]['link'] . '#end', 'https://moetv.live/detail/?', '#end');

    $tmpURL = array();
    $tmpURLIndex = array();
    for ($i = $lastIndex + 1; $i <= $lastIndex + 100; $i++) {
        array_push($tmpURL, 'https://moetv.live/detail/?' . $i . '.html');
        array_push($tmpURLIndex, $i);
        if ($i % 10 == 0 || $i == $lastIndex + 100) {
            $webDatas = curl_multi($tmpURL);
            foreach ($webDatas as $key => $data) {
                if (substr_count($data, 'href="/detail/?') != 0) {
                    $title = getSubstr($data, 'href="/detail/?' . $tmpURLIndex[$key] . '.html" title="', '">');
                    $title = str_replace('"', '', $title);
                    $url = 'https://moetv.live/detail/?' . $tmpURLIndex[$key] . '.html';
                    $f['title'] = $title;
                    $f['link'] = $url;
                    array_push($rst, $f);
                    $sum++;
                }
            }
            $tmpURL = array();
            $tmpURLIndex = array();
        }
    }
    $frst = json_encode($rst, JSON_UNESCAPED_UNICODE);

    if (saveData($frst, 'moetv')) {
        echo '[done] moetv +' . $sum . '<br>';
    } else {
        echo '[fail] moetv<br>';
    }
}

// 新番信息，名称需要随季度更新
function getNewBgm()
{
    $frst = curl_get_contents('https://api.tls.moe/?app=bangumi&key=calendar');

    if (saveData($frst, '202002')) {
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
    // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    // curl_setopt($ch, CURLOPT_PROXY, proxyURL);
    // curl_setopt($ch, CURLOPT_PROXYPORT, proxyPORT);

    $r = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode != 404) {
        return $r;
    } else {
        return 0;
    }
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

function createCh($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko');
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.opacg.com/');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    return $ch;
}

function curl_multi($urls)
{
    if (!is_array($urls) or count($urls) == 0) {
        return false;
    }
    $num = count($urls);
    $curl = $curl2 = $text = array();
    $handle = curl_multi_init();
    foreach ($urls as $k => $v) {
        $url = $urls[$k];
        $curl[$k] = createCh($url);
        curl_multi_add_handle($handle, $curl[$k]);
    }
    $active = null;
    do {
        $mrc = curl_multi_exec($handle, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($handle) != -1) {
            usleep(100);
        }
        do {
            $mrc = curl_multi_exec($handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }

    foreach ($curl as $k => $v) {
        if (curl_error($curl[$k]) == "") {
            $text[$k] = (string) curl_multi_getcontent($curl[$k]);
        }
        curl_multi_remove_handle($handle, $curl[$k]);
        curl_close($curl[$k]);
    }
    curl_multi_close($handle);
    return $text;
}
