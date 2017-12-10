<?php
//取百度搜索联想结果
require 'function.php';
    $val = $_POST['value'];

    $sumscode='';
        if (substr_count($val,'type:')==1) {
            $scode=' type:'.getSubstr($val,' type:','/').'/';
            $sumscode=$sumscode.$scode;
            $val=str_replace($scode,'',$val);
        }
        if (substr_count($val,'only:')==1) {
            $scode=' only:'.getSubstr($val,' only:','/').'/';
            $sumscode=$sumscode.$scode;
            $val=str_replace($scode,'',$val);
        }
        if (substr_count($val,'exc:')==1) {
            $scode=' exc:'.getSubstr($val,' exc:','/').'/';
            $sumscode=$sumscode.$scode;
            $val=str_replace($scode,'',$val);
        }
        
    $link='https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd='.urlencode($val).'&json=1&p=3';
    $link=curl_get_contents($link);
    $link=mb_convert_encoding($link, 'utf-8', 'gbk');
    $link=getSubstr($link,'s":[',']});');
    $num=substr_count($link,'","')+1;
    $scr='';
        echo '<span class="label">联想结果:</span> ';
    if ($num!=1) {
        for ($i=0; $i < $num; $i++) { 
            if (getSubstr($link,'"','",')=='') {
                $f=getSubstr($link,'"','"');
            } else{
                $f=getSubstr($link,'"','",');
            }
            $link=str_replace('"'.$f.'"','',$link);

            echo ' <a style="text-decoration:none;" href="javascript:void(0)" id="srhauto'.$i.'" name="'.$f.$sumscode.'"><span class="label">'.$f.'</span></a> ';
            $scr=$scr.'$(function(){$("#srhauto'.$i.'").click(function(){var text=document.getElementById("srhauto'.$i.'").name;document.getElementById("title").value=text;});});';
        }
            echo '<script>'.$scr.'</script>';
    } else{
        echo '<span class="label">none</span>';
    }
?>