<?php
//将 bangumi.json 中的数据导入数据库
set_time_limit(0);
$file = "bangumi.json";
$bca = file_get_contents($file);
$bca = json_decode($bca, true);
$bca = $bca['items'];

for ($i = 0; $i < count($bca); $i++) {
    $bgm = $bca[$i];

    //name
    $name = addslashes($bgm['title']);

    //name_cn
    if ($bgm['titleTranslate']['zh-Hans']) {
        $name_cn = addslashes(json_encode($bgm['titleTranslate']['zh-Hans'], JSON_UNESCAPED_UNICODE));
    } else {
        $name_cn = addslashes('["' . $name . '"]');
    }
    if ($name_cn == '') {
        $name_cn = addslashes('["' . $name . '"]');
    }

    //name_en
    if ($bgm['titleTranslate']['en'][0]) {
        $name_en = addslashes(['titleTranslate']['en'][0]);
    } else {
        $name_en = addslashes($name);
    }
    if ($name_en == '') {
        $name_en = addslashes($name);
    }

    //time
    $begin = strtotime($bgm['begin']);
    $end = strtotime($bgm['end']);

    $type = $bgm['type'];

    $officialSite = $bgm['officialSite'];

    $site = addslashes(json_encode($bgm['sites'], JSON_UNESCAPED_UNICODE));

    $stitle = array();
    array_push($stitle, $name);
    if ($name_en != $name && $name_en != '') {
        array_push($stitle, $name_en);
    }
    for ($a = 0; $a < count($bgm['titleTranslate']['zh-Hans']); $a++) {
        array_push($stitle, $bgm['titleTranslate']['zh-Hans'][$a]);
    }
    $stitle = addslashes(json_encode($stitle, JSON_UNESCAPED_UNICODE));
    $id = $i + 1;

    $con = new mysqli('localhost', 'root', '', 'airanime');

    $con->query("set names utf8");
    $sql = "insert into bgm (id,title,zhtitle,entitle,type,officialSite,site,begin,end,stitle) values('$id','$name','$name_cn','$name_en','$type','$officialSite','$site','$begin','$end','$stitle')";
    $con->query($sql);
    $con->close();
}
