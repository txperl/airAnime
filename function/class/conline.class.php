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

        //整理数据
        // acqq 结果
        $r_acqq = $this->__acqqS($data['acqq']);
        $rst['acqq'] = $r_acqq;

        $r_manhuagui = $this->__manhuaguiS($data['manhuagui']);
        $rst['manhuagui'] = $r_manhuagui;

        $r_dm5 = $this->__dm5S($data['dm5']);
        $rst['dm5'] = $r_dm5;

        $r_manhuatai = $this->__manhuataiS($data['manhuatai']);
        $rst['manhuatai'] = $r_manhuatai;

        $r_dmzj = $this->__dmzjS($data['dmzj']);
        $rst['dmzj'] = $r_dmzj;

        return $rst;
    }

    function __acqqS($data)
    {
        $data = getSubstr($data, '<!-- 结果列表 ST -->', '<!-- 结果列表 ED -->');
        $number = substr_count($data, '<li class="comic-item">');
        $rst_t = array("腾讯动漫_漫画标题");
        $rst_l = array("腾讯动漫_漫画链接");
        $txdm_mh = array();

        for ($n = 0; $n < $number; $n++) {
            $f = '<li class="comic-item">' . getSubstr($data, '<li class="comic-item">', '</li>') . '</li>';
            $l = 'http://m.ac.qq.com' . getSubstr($f, ' href="', '">');
            $t = getSubstr($f, '<strong class="comic-title">', '</strong>');
            $data = str_replace($f, '', $data);
            array_push($rst_l, $l);
            array_push($rst_t, $t);
        }

        array_push($txdm_mh, $rst_t);
        array_push($txdm_mh, $rst_l);
        array_push($txdm_mh, $number);

        return $txdm_mh;
    }

    function __manhuaguiS($data)
    {
        $rst_t = array("漫画柜_漫画标题");
        $rst_l = array("漫画柜_漫画链接");
        $manhuagui = array();

        $preg = '/<div class="book-cover"><a class="bcover" href="(.*?)" title="(.*?)"><img/';
        preg_match_all($preg, $data, $rst);

        for ($i = 0; $i < count($rst[2]); $i++) {
            array_push($rst_t, $rst[2][$i]);
        }
        for ($i = 0; $i < count($rst[1]); $i++) {
            array_push($rst_l, 'https://www.manhuagui.com' . $rst[1][$i]);
        }

        array_push($manhuagui, $rst_t);
        array_push($manhuagui, $rst_l);
        array_push($manhuagui, count($rst_t) - 1);

        return $manhuagui;
    }

    function __dm5S($data)
    {
        $data = str_replace(' ', '', $data);
        $rst_t = array("动漫屋_漫画标题");
        $rst_l = array("动漫屋_漫画链接");
        $dm5 = array();

        $preg = '/<divclass="book-list-cover"><ahref="(.*?)"title="(.*?)"><imgclass="book-list-cover-img"/';
        preg_match_all($preg, $data, $rst);

        for ($i = 0; $i < count($rst[2]); $i++) {
            array_push($rst_t, $rst[2][$i]);
        }
        for ($i = 0; $i < count($rst[1]); $i++) {
            array_push($rst_l, 'http://www.dm5.com' . $rst[1][$i]);
        }

        array_push($dm5, $rst_t);
        array_push($dm5, $rst_l);
        array_push($dm5, count($rst_t) - 1);

        return $dm5;
    }

    function __dmzjS($data)
    {
        $data = getSubstr($data, '<ul class="update_con autoHeight">', '</ul>');
        $rst_t = array("动漫之家_漫画标题");
        $rst_l = array("动漫之家_漫画链接");
        $dmzj = array();

        $preg = '/<li><a   target="_blank" title="(.*?)"href="(.*?)"><img/';
        preg_match_all($preg, $data, $rst);

        for ($i = 0; $i < count($rst[1]); $i++) {
            array_push($rst_t, $rst[1][$i]);
        }
        for ($i = 0; $i < count($rst[2]); $i++) {
            array_push($rst_l, $rst[2][$i]);
        }

        array_push($dmzj, $rst_t);
        array_push($dmzj, $rst_l);
        array_push($dmzj, count($rst_t) - 1);

        return $dmzj;
    }

    function __manhuataiS($data)
    {
        $data = json_decode($data, true);
        $rst_t = array("漫画台_漫画标题");
        $rst_l = array("漫画台_漫画链接");
        $manhuatai = array();

        for ($i = 0; $i < count($data); $i++) {
            array_push($rst_t, $data[$i]['cartoon_name']);
            array_push($rst_l, 'http://www.manhuatai.com/' . $data[$i]['cartoon_id']);
        }

        array_push($manhuatai, $rst_t);
        array_push($manhuatai, $rst_l);
        array_push($manhuatai, count($rst_t) - 1);

        return $manhuatai;
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

    function __getSDdata($urls)
    {
        //获取网页数据
        $rst = curl_multi($urls);

        return $rst;
    }
}
?>