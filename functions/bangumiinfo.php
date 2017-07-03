<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
include("chttochs/convert.php");
require 'function.php';
    $val = $_POST['value'];
    $id = $_POST['id'];
    if ($id=='') {
        $web=curl_get_contents('http://api.bgm.tv/search/subject/'.urlencode($val).'?responseGroup=simple&type=2');
    }
    $r_info=infoS($web,$id);

    if ($r_info!='') {
        $n_info=zhconversion_hans($r_info[0]);
        $des_info=zhconversion_hans($r_info[1]);
        $des_img=$r_info[2];
        $des_img=str_replace('http://','https://',$des_img);
        $list=$r_info[3];

        $listcode='';
        $js='';
        for ($i=0; $i < count($list[0]); $i++) {   
            $listcode=$listcode.'<a class="label" style="text-decoration:none;color:#fff" href="javascript:void(0)" id="rest'.$i.'" name="'.$list[1][$i].'">'.$list[0][$i].'</a> ';
            $js=$js."$(function(){
                        $('#rest".$i."').click(function(){
                        var t=document.getElementById('rest".$i."').name;
                        $('#inforst').html('<br>少女祈愿中...').css('display','block');
                            $.post( './functions/bangumiinfo.php', { 'id' : t },function(data){
                            if( t == '' ) 
                                $('#inforst').html('').css('display','none');
                            else
                                $('#inforst').html(data).css('display','block');
                            });
                        });
                    });";
        }

        echo '<div id="inforst"><hr>';
        echo '<img src="'.$des_img.'" style="float:right;width:176px;height:250px;padding-left:4px;" />'; //封面
        echo '<span style="font-size:18px;">《'.$n_info.'》</span><br>'; //标题
        echo $des_info; //内容
        echo '<br><br><span style="font-size:10px;">番剧信息数据基于Bangumi番组计划<br></div>'; //版权

        if (count($list[0])>1) {
        echo '<a style="width:100%;margin-top:6px;margin-bottom:6px;" class="btn btn-flat waves-attach" data-toggle="collapse" href="#selector"> 更多结果 </a>
<div class="collapse collapsible-region" id="selector">'.$listcode.'</div>';
        //echo '<script src="js/jquery.min2.20.js"></script>';
        echo '<script>'.$js.'</script>';
        }

        echo '</div>';
    } else {
        echo '<br>抱歉未找到相关内容，可能是网络速度问题，您可以重试看看...';
    }
    
//Bangumi Info
function infoS($web='',$id=''){
    $link=array();
    $title=array();
    $rst=array();
    $list=array();

    if ($id=='') {
        $bgmS=json_decode($web, true);

        $number=count($bgmS['list']);
            for ($i=0; $i < $number; $i++) { 
                if ($bgmS['list'][$i]['name_cn']=='') {
                    $ftitle=$bgmS['list'][$i]['name'];
                } else {
                    $ftitle=$bgmS['list'][$i]['name_cn'];
                }
                array_push($link,$bgmS['list'][$i]['id']);
                array_push($title,$ftitle);
            }
            array_push($list,$title);
            array_push($list,$link);
        $flink='/subject/'.$link[0];
    } else {
        $number=1;
        $flink='/subject/'.$id;
    }

    $APIurl='http://api.bgm.tv'.$flink.'?responseGroup=simple';
    $webtext=curl_get_contents($APIurl);
    $bgmC=json_decode($webtext, true);
    if ($bgmC['name_cn']=='') {
        $name=$bgmC['name'];
    } else {
        $name=$bgmC['name_cn'];
    }
    $des=$bgmC['summary'];
    $img=$bgmC['images']['large'];

    array_push($rst,$name);//0
    array_push($rst,$des); //1
    array_push($rst,$img); //2
    array_push($rst,$list); //3

    if ($number!=0) {
        return $rst;
    } else {
        return '';
    }
}
?>