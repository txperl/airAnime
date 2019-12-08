<?php
require_once 'functions.php';
$keytitle = $_POST["kt"];

$val = $keytitle;
$f = '';
$link = 'https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd=' . urlencode($val) . '&json=1&p=3';
$web = curl_get_contents($link);
$web = mb_convert_encoding($web, 'utf-8', 'gbk');
$web = getSubstr($web, ',"s":', '})');
$oriData = json_decode($web, true);

if ($oriData != '') {
    //除杂
    for ($i = 0; $i < count($oriData); $i++) {
        $oriData[$i] = strtolower($oriData[$i]);

        $oriData[$i] = str_replace('百度云', '', $oriData[$i]);

        for ($j = 0; $j < 3; $j++) {
            $lastni = mb_substr($oriData[$i], mb_strlen($oriData[$i]) - 2, mb_strlen($oriData[$i]), 'utf-8');
            if ($lastni == '无修' || $lastni == '手游' || $lastni == '壁纸' || $lastni == '在线' || $lastni == '观看' || $lastni == 'bd' || $lastni == '小说' || $lastni == '漫画' || $lastni == '动漫' || $lastni == '免费') {
                $oriData[$i] = mb_substr($oriData[$i], 0, mb_strlen($oriData[$i]) - 2);
            }
        }

        $lastn = mb_substr($oriData[$i], mb_strlen($oriData[$i]) - 3, mb_strlen($oriData[$i]), 'utf-8');
        if ($lastn == '第一季' || $lastn == '第二季' || $lastn == '第三季' || $lastn == '第四季' || $lastn == '第五季' || $lastn == '第六季' || $lastn == '第七季' || $lastn == '第一部' || $lastn == '第二部' || $lastn == '第三部' || $lastn == '第四部' || $lastn == '第五部' || $lastn == '第六部' || $lastn == '第七部') {
            $oriData[$i] = mb_substr($oriData[$i], 0, mb_strlen($oriData[$i]) - 3);
        }
    }

    $oriData = m_ArrayUnique($oriData);

    for ($i = 0; $i < count($oriData); $i++) {
        $f = $f . '<li><a href="javascript:addInputKT(\'' . $oriData[$i] . '\');"><h3 id="AASS">' . $oriData[$i] . '</h3></a></li>';
    }
    echo $f;
}
