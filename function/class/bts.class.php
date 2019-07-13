<?php
require_once './functions.php';
/**
 * 
 */
class allSearchOnline
{
    function __doS($data, $keyTitle)
    {
        $rst = array();
        $autotitle = whatstitle($keyTitle);
        $this->autotitle = $autotitle;

        //整理数据
        // mgjh 结果
        $r_mgjh = $this->__mgjhS($data['mgjh']);
        $rst['mgjh'] = $r_mgjh;

        // agefans 结果
        $r_agefans = $this->__agefansS($keyTitle);
        $rst['agefans'] = $r_agefans;

        return $rst;
    }

    function __mgjhS($data)
    {
        $data = getSubstr($data, '<div class="central-container">', '</ul>');
        $number = substr_count($data, '<li>');
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $mgjh = array();

        for ($n = 0; $n < $number; $n++) {
            $f = '<li>' . getSubstr($data, '<li>', '</li>') . '</li>';
            $l = 'https://mikanani.me' . getSubstr($f, '<a href="', '" target="_blank">');
            $t = getSubstr($f, '<div class="an-text" title="', '" style="');
            $data = str_replace($f, '', $data);
            array_push($rst_l, $l);
            array_push($rst_t, $t);
        }

        array_push($mgjh, $rst_t);
        array_push($mgjh, $rst_l);
        array_push($mgjh, $number);

        return $mgjh;
    }

    function __agefansS($keytitle)
    {
        $agefans = $this->__localSearch('../data/agefans.json', $keytitle);

        return $agefans;
    }

    function __baiduS($title, $zz, $page, $wst, $ori1, $ori2)
    {
        $rst_t = array("标题");
        $rst_l = array("链接");
        $baiduS = array();
        $number = 0;

        for ($m = 0; $m < $page; $m++) { //百度协助搜索页数
            $webtext = $title;
            $webtext = str_replace(' ', '', $webtext);
            preg_match_all($zz, $webtext, $rst);
            $number = $number + count($rst[0]);
            // 载入 标题 链接
            for ($i = 0; $i < count($rst[0]); $i++) {
                $t = $rst[1][$i];
                $sinm1 = howtextsimilar(strtoupper($t), strtoupper($ori1));
                $sinm2 = howtextsimilar(strtoupper($t), strtoupper($ori2));
                $sinm = ($sinm1 + $sinm2) / 2;
                if ($sinm >= 0.3) {
                    array_push($rst_t, $t);
                    $l = $rst[3][$i];
                    array_push($rst_l, $l);
                }
            }
        }

        array_push($baiduS, $rst_t);
        array_push($baiduS, $rst_l);
        array_push($baiduS, count($rst_t) - 1);
        return $baiduS;
    }
    function __localSearch($uri, $keytitle)
    {
        $file = $uri;
        $bca = file_get_contents($file);
        $bca = json_decode($bca, true);
        $rst = array();
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $rst_f = array();
        $coss = array("airAnime_cos");

        for ($i = 0; $i < count($bca); $i++) {
            $cos = howtextsimilar(strtoupper($bca[$i]['title']), strtoupper($keytitle));
            if ($cos >= 0.5) {
                $cos2 = howtextsimilar(strtoupper($bca[$i]['title']), strtoupper($this->autotitle));
                array_push($rst, $bca[$i]);
                array_push($coss, $cos2 * 10);
            }
        }

        if (count($rst) >= 20) {
            $rst = ifExistinOnline($rst, $keytitle);
        }

        if (count($rst) == 0) {
            $rst = array();
            for ($i = 0; $i < count($bca); $i++) {
                similar_text(strtoupper($bca[$i]['title']), strtoupper($keytitle), $cos);
                if ($cos >= 45) {
                    similar_text(strtoupper($bca[$i]['title']), strtoupper($this->autotitle), $cos2);
                    array_push($rst, $bca[$i]);
                    array_push($coss, $cos2);
                }
            }
        }

        $number = count($rst);

        for ($i = 0; $i < count($rst); $i++) {
            array_push($rst_t, $rst[$i]['title']);
            array_push($rst_l, $rst[$i]['link']);
        }

        array_push($rst_f, $rst_t);
        array_push($rst_f, $rst_l);
        array_push($rst_f, $number);

        return $this->__sortCoss($rst_f, $coss);
    }

    function __sortCoss($c, $coss)
    {
        // 简单插排
        for ($i = 1; $i < count($coss); $i++) {
            $insertVal = $coss[$i];
            $insertVal2 = $c[0][$i];
            $insertVal3 = $c[1][$i];

            $insertIndex = $i - 1;
            while ($insertIndex >= 1 && $insertVal > $coss[$insertIndex]) {
                $coss[$insertIndex + 1] = $coss[$insertIndex];
                $c[0][$insertIndex + 1] = $c[0][$insertIndex];
                $c[1][$insertIndex + 1] = $c[1][$insertIndex];
                $insertIndex--;
            }
            $coss[$insertIndex + 1] = $insertVal;
            $c[0][$insertIndex + 1] = $insertVal2;
            $c[1][$insertIndex + 1] = $insertVal3;
        }

        return $c;
    }
    
    function __getSDdata($urls)
    {
        //获取网页数据
        $rst = curl_multi($urls);

        return $rst;
    }
}
