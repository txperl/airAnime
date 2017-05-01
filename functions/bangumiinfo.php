<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
include("chttochs/convert.php");
require 'function.php';
    $val = $_POST['value'];
    $id = $_POST['id'];
    if ($id=='') {
        $web=curl_get_contents('http://bangumi.tv/subject_search/'.urlencode($val).'?cat=2');
    }
    $r_info=infoS($web,$id);

    if ($r_info!='') {
        $n_info=zhconversion_hans($r_info[0]);
        $des_info=zhconversion_hans($r_info[1]);
        $des_img=$r_info[2];
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
        $webtext=$web;

        $number=substr_count($webtext,'<h3>');
            for ($i=0; $i < $number; $i++) { 
                $f='<h3>'.getSubstr($webtext,'<h3>','</div>').'</div>';
                $date=getSubstr($f,'<p class="info tip">','日').'日';
                $date=str_replace(' ','',$date);
                $flink=getSubstr($f,'/subject/','" '); // /subject/18629
                $ftitle=getSubstr($f,' class="l">','</a> ').' -'.$date;
                array_push($link,$flink);
                array_push($title,$ftitle);
                $webtext=str_replace($f,'',$webtext);
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
    $name=unicode_decode(getSubstr($webtext,'"name_cn":"','","'));
    $des=getSubstr($webtext,'"summary":"','","');
    $des=str_replace('\r\n','<br><br>',$des);
    $des=unicode_decode($des);
    $img=unicode_decode(getSubstr($webtext,'"large":"','","'));

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