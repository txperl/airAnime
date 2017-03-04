<?php
//取百度搜索联想结果
include("chttochs/convert.php");
require 'function.php';
    $val = $_POST['value'];
    $web=curl_get_contents('http://bgm.tv/subject_search/'.urlencode($val).'?cat=2');
    $r_info=infoS($web);

    if ($r_info!='') {
        $n_info=zhconversion_hans($r_info[0]);
        $des_info=zhconversion_hans($r_info[1]);
        $des_img=$r_info[2];
        echo '<hr>';
        echo '<img src="'.$des_img.'" style="float:right;width:176px;height:250px;padding-left:4px;" />';
        echo '<div><span style="font-size:18px;">《'.$n_info.'》</span><br>';
        echo $des_info;
        echo '<br><br>番剧信息数据基于Bangumi番组计划';
        echo '</div>';
    } else {
        echo '<br>抱歉未找到相关内容，可能是网络速度问题，您可以重试看看...';
    }
    
//Bangumi Info
function infoS($title){
    $rst=array();
    $webtext=$title;
    $number=substr_count($webtext,'<h3>');
    if ($number!=0) {
        $flink=getSubstr($webtext,'<h3>','</h3>');
        $flink=getSubstr($flink,'<a href="','" '); // /subject/18629

    $APIurl='http://api.bgm.tv'.$flink.'?responseGroup=simple';
    $webtext=curl_get_contents($APIurl);
    $name=unicode_decode(getSubstr($webtext,'"name_cn":"','","'));
    $des=getSubstr($webtext,'"summary":"','","');
    $des=str_replace('\r\n','<br><br>',$des);
    $des=unicode_decode($des);
    $img=unicode_decode(getSubstr($webtext,'"large":"','","'));

    array_push($rst,$name);//0
    array_push($rst,$des); //1
    array_push($rst,$img); //2
    return $rst;
    } else {
        return '';
    }
}
?>