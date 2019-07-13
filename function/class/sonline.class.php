<?php
require_once './functions.php';
/**
 * 
 */
class allSearchOnline
{
    function __doS($data, $keyTitle, $isLocal = true)
    {
        $rst = array();
        $autotitle = whatstitle($keyTitle);
        $this->autotitle = $autotitle;

        //整理数据
        // bilibili 结果
        $r_bilibili = $this->__bilibiliS($data['bilibili']);
        $rst['bilibili'] = $r_bilibili;

        // dilidili 结果
        $r_dilidili = $this->__dilidiliS($data['dilidili'], $autotitle, $keyTitle);
        $rst['dilidili'] = $r_dilidili;

        // fcdm 结果
        $r_fcdm = $this->__fcdmS($data['fcdm']);
        $rst['fcdm'] = $r_fcdm;

        // pptv 结果
        $r_pptv = $this->__pptvS($data['pptv'], $autotitle, $keyTitle);
        $rst['pptv'] = $r_pptv;

        // letv 结果
        $r_letv = $this->__baiduS($data['letv'], '/{"title":"(.*?)_全集(.*?)","url":"(.*?)"}/', 1, 'www.le.com', $autotitle, $keyTitle);
        $rst['letv'] = $r_letv;
        if ($r_letv[2] == 0) {
            $r_letv = $this->__baiduS($data['letv'], '/{"title":"(.*?)-在线观看-动漫(.*?)","url":"(.*?)"}/', 1, 'www.le.com', $autotitle, $keyTitle);
            $rst['letv'] = $r_letv;
        }

        // iqiyi 结果
        $r_iqiyi = $this->__baiduS($data['iqiyi'], '/{"title":"(.*?)-动漫动画-(.*?)","url":"(.*?)"}/', 1, 'www.iqiyi.com', $autotitle, $keyTitle);
        $rst['iqiyi'] = $r_iqiyi;
        if ($r_iqiyi[2] == 0) {
            $r_iqiyi = $this->__baiduS($data['iqiyi'], '/{"title":"(.*?)-动漫-(.*?)","url":"(.*?)"}/', 1, 'www.iqiyi.com', $autotitle, $keyTitle);
            $rst['iqiyi'] = $r_iqiyi;
        }
        if ($r_iqiyi[2] == 0) {
            $r_iqiyi = $this->__baiduS($data['iqiyi'], '/{"title":"(.*?)-全集在线观看-动漫(.*?)","url":"(.*?)"}/', 1, 'www.iqiyi.com', $autotitle, $keyTitle);
            $rst['iqiyi'] = $r_iqiyi;
        }

        // youku 结果
        $r_youku = $this->__baiduS($data['youku'], '/{"title":"(.*?)—日本—动漫—优酷(.*?)","url":"(.*?)"}/', 1, 'www.youku.com', $autotitle, $keyTitle);
        $rst['youku'] = $r_youku;
        if ($r_youku[2] == 0) {
            $r_youku = $this->__baiduS($data['youku'], '/{"title":"(.*?)—日本—动漫(.*?)","url":"(.*?)"}/', 1, 'www.youku.com', $autotitle, $keyTitle);
            $rst['youku'] = $r_youku;
        }

        // tencenttv 结果
        $r_tencenttv = $this->__baiduS($data['tencenttv'], '/{"title":"(.*?)-高清(.*?)","url":"(.*?)"}/', 1, 'v.qq.com', $autotitle, $keyTitle);
        $rst['tencenttv'] = $r_tencenttv;
        if ($r_tencenttv[2] == 0) {
            $r_tencenttv = $this->__baiduS($data['tencenttv'], '/{"title":"(.*?)-动漫(.*?)","url":"(.*?)"}/', 1, 'v.qq.com', $autotitle, $keyTitle);
            $rst['tencenttv'] = $r_tencenttv;
        }

        // qinmei 结果
        $r_qinmei = $this->__qinmeiS($data['qinmei']);
        $rst['qinmei'] = $r_qinmei;

        // nicotv 结果
        $r_nicotv = $this->__nicotvS($data['nicotv']);
        $rst['nicotv'] = $r_nicotv;

        if ($isLocal) {
            // anime1.me 结果
            $r_anime1 = $this->__anime1S($keyTitle);
            $rst['anime1'] = $r_anime1;

            // bimibimi.cc 结果
            $r_bimibimi = $this->__bimibimiS($keyTitle);
            $rst['bimibimi'] = $r_bimibimi;

            // 8maple.ru 结果
            $r_8maple = $this->__8mapleS($keyTitle);
            $rst['8maple'] = $r_8maple;

            // calibur.tv 结果，感谢站长付出
            //$r_calibur = $this->__caliburS($keyTitle);
            //$rst['calibur'] = $r_calibur;
        }

        if ($isLocal) {
            $statol = $r_bilibili[2] + $r_dilidili[2] + $r_letv[2] + $r_iqiyi[2] + $r_pptv[2] + $r_fcdm[2] + $r_youku[2] + $r_tencenttv[2] + $r_qinmei[2] + $r_nicotv[2] + $r_anime1[2] + $r_bimibimi[2] + $r_8maple[2];
        } else {
            $statol = $r_bilibili[2] + $r_dilidili[2] + $r_letv[2] + $r_iqiyi[2] + $r_pptv[2] + $r_fcdm[2] + $r_youku[2] + $r_tencenttv[2] + $r_qinmei[2] + $r_nicotv[2];
        }

        $rst['allNum'] = $statol;
        return $rst;
    }

    function __bilibiliS($data)
    {
        $webtext = json_decode($data, true);
        $number = count($webtext['data']['result']['media_bangumi']);
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $bilibili = array();

        for ($n = 0; $n < $number; $n++) {
            $l = 'https://www.bilibili.com/bangumi/media/md' . $webtext['data']['result']['media_bangumi'][$n]['media_id'];
            $t = $webtext['data']['result']['media_bangumi'][$n]['title'];
            $t = str_replace('<em class="keyword">', '', $t);
            $t = str_replace('</em>', '', $t);
            array_push($rst_l, $l);
            array_push($rst_t, $t);
        }

        array_push($bilibili, $rst_t);
        array_push($bilibili, $rst_l);
        array_push($bilibili, $number);
        return $bilibili;
    }

    function __dilidiliS($data, $ori1, $ori2)
    {
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $dilidili = array();
        $number = 0;

        $webtext = $data;
        $webtext = str_replace(' ', '', $webtext);
        preg_match_all('/{"title":"(.*?)在线&amp;下载(.+?)嘀哩嘀哩","url":"(.*?)"}/', $webtext, $rst);
        if (count($rst[0]) == 0) {
            unset($rst);
            preg_match_all('/{"title":"(.*?)丨(.*?)嘀哩嘀哩","url":"(.*?)"}/', $webtext, $rst);
        }
        $number = $number + count($rst[0]);
        // 载入 标题 链接
        for ($i = 0; $i < count($rst[0]); $i++) {
            $t = str_replace('-', '', $rst[1][$i]);
            $sinm1 = howtextsimilar(strtoupper($t), strtoupper($ori1));
            $sinm2 = howtextsimilar(strtoupper($t), strtoupper($ori2));
            $sinm = ($sinm1 + $sinm2) / 2;
            if ($sinm >= 0.3) {
                array_push($rst_t, $t);
                $l = $rst[3][$i];
                array_push($rst_l, $l);
            }
        }

        array_push($dilidili, $rst_t);
        array_push($dilidili, $rst_l);
        array_push($dilidili, count($rst_t) - 1);
        return $dilidili;
    }

    function __fcdmS($data)
    {
        $webtext = $data;
        $webtext = getSubstr($webtext, '<div class="imgs">', '</div>');
        $number = substr_count($webtext, 'title="');
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $fcdm = array();

        for ($n = 0; $n < $number; $n++) {
            $f = '<img' . getSubstr($webtext, '<img', '</a></p>') . '</a></p>';
            $l = 'http://www.fengchedm.com' . getSubstr($f, 'href="', '" ');
            $t = getSubstr($f, 'title="', '">');
            $webtext = str_replace($f, '', $webtext);
            array_push($rst_l, $l);
            array_push($rst_t, $t);
        }

        array_push($fcdm, $rst_t);
        array_push($fcdm, $rst_l);
        array_push($fcdm, $number);
        return $fcdm;
    }

    function __pptvS($data, $ori1, $ori2)
    {
        $webtext = $data;
        $webtext = str_replace('title="详情"', '', $webtext);
        $number = substr_count($webtext, '<div class="bd fr">');
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $pptv = array();

        for ($n = 0; $n < $number; $n++) {
            $f = '<i class="ico02"></i></a>' . getSubstr($webtext, '<i class="ico02"></i></a>', '<dd class="pinfo pinfo2">') . '<dd class="pinfo pinfo2">';
            $l = getSubstr($f, '<a href="', '" target="_blank"');
            $t = getSubstr($f, ' title="', '">');
            $webtext = str_replace($f, '', $webtext);

            array_push($rst_t, $t);
            array_push($rst_l, $l);
        }

        array_push($pptv, $rst_t);
        array_push($pptv, $rst_l);
        array_push($pptv, count($rst_t) - 1);
        return $pptv;
    }

    function __anime1S($keytitle)
    {
        $anime1 = $this->__localSearch('../data/anime1.json', $keytitle);

        return $anime1;
    }

    function __bimibimiS($keytitle)
    {
        $bimibimi = $this->__localSearch('../data/bimibimi.json', $keytitle);

        return $bimibimi;
    }

    function __8mapleS($keytitle)
    {
        $f8maple = $this->__localSearch('../data/8maple.json', $keytitle);

        return $f8maple;
    }

    function __qinmeiS($data)
    {
        $webtext = json_decode($data, true);
        $number = count($webtext);
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $qinmei = array();

        for ($n = 0; $n < $number; $n++) {
            $l = 'https://qinmei.video/animate/' . $webtext[$n]['slug'];
            $l = str_replace('qinmei.org', 'qinmei.video', $l);
            $t = $webtext[$n]['title']['rendered'];
            array_push($rst_l, $l);
            array_push($rst_t, $t);
        }

        array_push($qinmei, $rst_t);
        array_push($qinmei, $rst_l);
        array_push($qinmei, $number);

        return $qinmei;
    }

    function __nicotvS($data)
    {
        $webtext = $data;
        $number = substr_count($webtext, '<h2 class="text-nowrap ff-text-right">');
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
        $nicotv = array();

        for ($n = 0; $n < $number; $n++) {
            $f = '<h2 class="text-nowrap ff-text-right">' . getSubstr($webtext, '<h2 class="text-nowrap ff-text-right">', '</h2>') . '</h2>';
            $l = 'http://www.nicotv.me' . getSubstr($f, '<a href="', '" title="');
            $t = getSubstr($f, ' title="', '">');
            $webtext = str_replace($f, '', $webtext);

            array_push($rst_t, $t);
            array_push($rst_l, $l);
        }

        array_push($nicotv, $rst_t);
        array_push($nicotv, $rst_l);
        array_push($nicotv, count($rst_t) - 1);
        return $nicotv;
    }

    function __baiduS($title, $zz, $page, $wst, $ori1, $ori2)
    {
        $rst_t = array("airAnime_title");
        $rst_l = array("airAnime_link");
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
