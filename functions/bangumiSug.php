<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
include("chttochs/convert.php");
require_once 'function.php';
    if ($_POST['code']=='') {
        $code=$_GET['code'];
    } else {
        $code=$_POST['code'];
    }
    
    $file="./data/bangumiS.json";
    $bca=file_get_contents($file);
    $bca=json_decode($bca, true);
    $bca=$bca['items'];
    if ($code=='run') {
        $turn=rand(0,count($bca)-1);
        $id=$bca[$turn]['sites'];
        for ($i=0; $i < count($id); $i++) { 
            if ($id[$i]['site']=='bangumi') {
                $id=$id[$i]['id'];
                break;
            }
        }
    } elseif ($code=='up') {
        $web=file_get_contents('http://loli.leanote.com/post/airAnime%E4%BB%8A%E6%97%A5%E6%8E%A8%E8%8D%90');
        $web=getSubstr($web,'<textarea>','</textarea>');
        $id=getSubstr($web,'bangumi_id=',';');
    } else {
        $myfile=fopen("./data/bangumiToday.json", "r") or die("Unable to open file!");
            $bgmC=fgets($myfile);
        fclose($myfile);
        $bgmC=json_decode($bgmC, true);
    }

    $flink='/subject/'.$id;

    if ($code=='up') {
        $APIurl='http://api.bgm.tv'.$flink.'?responseGroup=large';
        $webtext=curl_get_contents($APIurl);
        $myfile = fopen("./data/bangumiToday.json", "w") or die("Unable to open file!");
        $txt = $webtext;
        fwrite($myfile, $txt);
        fclose($myfile);
        die("done!");
    }
    
    if ($code=='run') {
        $APIurl='http://api.bgm.tv'.$flink.'?responseGroup=large';
        $webtext=curl_get_contents($APIurl);
        $bgmC=json_decode($webtext, true);
    }

    if ($bgmC['name_cn']=='') {
        $name=$bgmC['name'];
    } else {
        $name=$bgmC['name_cn'];
    }
    $des=$bgmC['summary'];
    $img=$bgmC['images']['large'];
    $img=str_replace('http://','https://',$img);
    $air_date=$bgmC['air_date'];

    if ($des=='') {
        $des='抱歉，暂无简介...';
    } else {
        $des=str_replace('　　','',$des);
        $des=str_replace(' ','',$des);
    }

    $url='http://bgm.tv/subject/'.$id;
    echo '<div class="barc-t"><div class="barc-tile">';
    echo '<div style="width:40%;max-height:300px;float:left;margin-right:10px;"><img src="'.$img.'" data-action="zoom" class="img-rounded img-responsive"></div>';
    echo '<div style="padding-top:10px;padding-bottom:10px;"><a target="_blank" href="'.$url.'">'.$name.'</a><br><span class="arc-date">&'.$bgmC['name'].'</span><br><span class="arc-date">首播: '.$air_date.'</span><br><span id="desb" class="arc-date">'.$des.'</span><br><span class="arc-date"><a id="translateB" href="javascript:void(0)">需要翻译?</a></span></div>';
    echo '</div></div>';
?>