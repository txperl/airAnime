<?php
//取百度搜索联想结果
require 'function.php';
    $val = $_POST['value'];

    $sumscode='';
    $scr='';

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
    $web=curl_get_contents($link);
    $web=mb_convert_encoding($web, 'utf-8', 'gbk');
    $web=getSubstr($web, 'window.baidu.sug(', ');');
    $oriData=json_decode($web,true);
    $oriData=$oriData['s'];
    
    if ($oriData!='') {
        //除杂
        for ($i=0; $i < count($oriData); $i++) { 
            $oriData[$i]=str_replace('百度云','',$oriData[$i]);
    
            $lastni=mb_substr($oriData[$i],mb_strlen($oriData[$i])-2,mb_strlen($oriData[$i]),'utf-8');
            if ($lastni=='无修' || $lastni=='手游' || $lastni=='壁纸') {
                $oriData[$i]=mb_substr($oriData[$i],0,mb_strlen($oriData[$i])-2);
            }
    
            $lastn=mb_substr($oriData[$i],mb_strlen($oriData[$i])-3,mb_strlen($oriData[$i]),'utf-8');
            if ($lastn=='第一季' || $lastn=='第二季' || $lastn=='第三季' || $lastn=='第四季' || $lastn=='第五季' || $lastn=='第六季' || $lastn=='第一部' || $lastn=='第二部' || $lastn=='第三部' || $lastn=='第四部' || $lastn=='第五部' || $lastn=='第六部') {
                $oriData[$i]=mb_substr($oriData[$i],0,mb_strlen($oriData[$i])-3);
            }
        }
    
        $oriData=m_ArrayUnique($oriData);
    
        echo '<span class="label">联想结果:</span> ';

        for ($i=0; $i < count($oriData); $i++) { 
            $f=$oriData[$i];
            echo ' <a style="text-decoration:none;" href="javascript:void(0)" id="srhauto'.$i.'" name="'.$f.$sumscode.'"><span class="label">'.$f.'</span></a> ';

            $scr=$scr.'$(function(){$("#srhauto'.$i.'").click(function(){var text=document.getElementById("srhauto'.$i.'").name;document.getElementById("title").value=text;});});';
        }

        echo '<script>'.$scr.'</script>';
    } else {
        echo '<span class="label">none</span>';
    }
?>