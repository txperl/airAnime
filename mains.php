<?php
//添加数据源思路：
//1.写 ifsrh 中代码
//2.添加搜索指令 ifrun
//3.index中整合数据(站内搜索还需写匹配代码)
//4.结果总数
//5.输出结果
//6.检查
ini_set("error_reporting","E_ALL & ~E_NOTICE");
require "function.php";
// r_bilibili
//		0:title
//		1:link
//		2:number
// r_baiduall
//		0:none
//		1:anime
//		2:number
// file_get_contents
$timeout = stream_context_create(array(    
   'http' => array(    
       'timeout' => 5 //设置一个超时时间，单位为秒    
       )    
   )    
);
// 哔哩哔哩集合搜索
function bilibiliS($title){
	$webtext=$title;
	$number=substr_count($webtext,'<div class="left-img">');
	$rst_t=array("bilibili标题");
	$rst_l=array("bilibili链接");
	$bilibili=array();

		for ($n=0; $n<$number; $n++) {
			$f='<div class="left-img">'.getSubstr($webtext,'<div class="left-img">','</div>').'</div>';
			$l='http://bangumi.bilibili.com/anime/'.getSubstr($f,'http://bangumi.bilibili.com/anime/','" ');
			$t=getSubstr($f,'title="','" ');
			$webtext=str_replace($f,'',$webtext);
			array_push($rst_l,$l);
			array_push($rst_t,$t);
		}
/*
		for ($n=0; $n<$number; $n++) {
			echo 'bilibili('.$number.'):'.$rst_t[$n+1].'('.$rst_l[$n+1].')'.'<br>';
		}
*/
		array_push($bilibili,$rst_t);
		array_push($bilibili,$rst_l);
		array_push($bilibili,$number);
		return $bilibili;
}

// 嘀哩嘀哩搜索
function dilidiliS($title){
	$rst_t=array("dilidili标题");
	$rst_l=array("dilidili链接");
	$dilidili=array();

	$webtext=$title;
	$webtext=str_replace(' ','',$webtext);
	preg_match_all('/>(.*?)在线&amp;下载(.+?)嘀哩嘀哩/',$webtext,$rst);
	$number=$number+count($rst[1])/2;
		// 载入 标题 链接
		for ($i=0; $i<count($rst[1])/2; $i++) { 
			$t=str_replace('-','',$rst[1][$i*2]);
			$t=str_replace('<em>','',$t);
			$t=str_replace('</em>','',$t);
			array_push($rst_t,$t);

			$l=getSubstr($rst[1][($i*2)+1],'_blank"href="','"class=');
			array_push($rst_l,$l);

/*
		for ($n=0; $n<$number;$n++) { 
			echo 'dilidili('.$number.'):'.$rst_t[$n+1].'('.$rst_l[$n+1].')'.'<br>';
		}
*/

	}
	array_push($dilidili,$rst_t);
	array_push($dilidili,$rst_l);
	array_push($dilidili,$number);
	return $dilidili;
}
// 风车动漫
function fcdmS($title){
	$webtext=$title;
	$webtext=getSubstr($webtext,'<div class="imgs">','</div>');
	$number=substr_count($webtext,'title="');
	$rst_t=array("风车动漫标题");
	$rst_l=array("风车动漫链接");
	$fcdm=array();

		for ($n=0; $n<$number; $n++) {
			$f='<img'.getSubstr($webtext,'<img','</a></p>').'</a></p>';
			$l='http://www.fengchedm.com'.getSubstr($f,'href="','" ');
			$t=getSubstr($f,'title="','">');
			$webtext=str_replace($f,'',$webtext);
			array_push($rst_l,$l);
			array_push($rst_t,$t);
		}
		array_push($fcdm,$rst_t);
		array_push($fcdm,$rst_l);
		array_push($fcdm,$number);
		return $fcdm;
}
// PPTV搜索
function pptvS($title){
	$webtext=$title;
	$webtext=str_replace('title="详情"','',$webtext);
	$number=substr_count($webtext,'<div class="bd fr">');
	$rst_t=array("pptv标题");
	$rst_l=array("pptv链接");
	$pptv=array();

		for ($n=0; $n<$number; $n++) {
			$f='class="ico02"'.getSubstr($webtext,'class="ico02"','</span></dt>').'</span></dt>';
			$l=getSubstr($f,'<a href="','" target="_blank"');
			$t=getSubstr($f,' title="','">');
			$webtext=str_replace($f,'',$webtext);
			array_push($rst_l,$l);
			array_push($rst_t,$t);
		}
		array_push($pptv,$rst_t);
		array_push($pptv,$rst_l);
		array_push($pptv,$number);
		return $pptv;
}

//百度集合搜索
function baiduallS($title)
{
	$webtext=$title;
	$rst=array("百度集合搜索");
	$rstz=array("百度集合搜索_暂时");
	$rstf=array();
	$number=0;
	$season=substr_count($webtext,'<div class="op-zx-new-tvideo-stitle op-zx-new-tvideo-more-hid">');

	for ($i=0; $i<$season; $i++) { 
	$f=getSubstr($webtext,'<div class="op-zx-new-tvideo-stitle op-zx-new-tvideo-more-hid">','</div>');
	array_push($rstz,$f);
	$f='<div class="op-zx-new-tvideo-stitle op-zx-new-tvideo-more-hid">'.$f.'</div>';
	$webtext=str_replace($f,'',$webtext);
	$number=$number+substr_count($f,'op-zx-new-tvideo-tbs');
	}

	for ($i=0; $i<count($rstz); $i++) { 
		if ($rstz[$i]!='百度集合搜索_暂时') {
			$finalrst=$finalrst.$rstz[$i];
		}
	}

	for ($i=0; $i<$number; $i++) { 
			$f=getSubstr($finalrst,"<p class='op-zx-new-tvideo-tbs'>",'</p>');
			$a=str_replace('<em>','',$f);
			$a=str_replace('</em>','',$a);
			array_push($rstf,$a);
			$f="<p class='op-zx-new-tvideo-tbs'>".$f.'</p>';
			$finalrst=str_replace($f,'',$finalrst);
	}
	array_push($rst,$rstf);
	array_push($rst,$number);
	return $rst;
}
//百度协助搜索通用代码
function baiduS($title,$zz,$page,$wst){
  $rst_t=array("标题");
  $rst_l=array("链接");
  $baiduS=array();
  for ($m=0;$m<$page;$m++){ //百度协助搜索页数
  $webtext=$title;
  $webtext=str_replace(' ','',$webtext);
  preg_match_all($zz,$webtext,$rst);
  $number=$number+count($rst[1])/2;
    // 载入 标题 链接
    for ($i=0; $i<count($rst[1])/2; $i++) { 
    	$t=str_replace('','',$rst[1][$i*2]);
      	$t=str_replace('<em>','',$t);
      	$t=str_replace('</em>','',$t);
      	array_push($rst_t,$t);

      	$l=getSubstr($rst[1][($i*2)+1],'_blank"href="','"class=');
      	array_push($rst_l,$l);
    }

  }
  array_push($baiduS,$rst_t);
  array_push($baiduS,$rst_l);
  array_push($baiduS,$number);
  return $baiduS;
}
//输出百度协助搜索通用代码
function baiduSS($title,$nowout,$name,$cname,$n_name,$l_name,$t_name){
		echo '<div class="tile tile-collapse"><div data-target="#'.$name.'" data-toggle="tile"><div class="tile-inner"><div class="text-overflow">'.$cname.'<div style="display:block;float: right;">'.$n_name.'</div></div></div></div><div class="tile-active-show collapse" id="'.$name.'"><div class="tile-sub">';

			for ($i=0; $i<$n_name; $i++) { 
				echo '<div class="tile"><div class="tile-inner">';
					echo '<a href="'.$l_name[$i+1].'" target="_blank">'.$t_name[$i+1].'</a>';
				echo '</div></div>';
			}
		
		echo '<div class="tile"><div class="tile-footer-btn pull-left">';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://'.$nowout.'/">'.$name.'</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://www.baidu.com/s?wd=site%3A'.$nowout.'%20'.$title.'">百度</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://bing.com/search?q=site%3A'.$nowout.'%20'.$title.'">必应</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="https://www.google.com/#q=site%3A'.$nowout.'%20'.$title.'">谷歌</a>';
		echo '</div></div></div></div></div>';
}
//输出站内搜索通用代码
function loaclSS($title,$nowout,$name,$cname,$n_name,$l_name,$t_name){
		echo '<div class="tile tile-collapse"><div data-target="#'.$name.'" data-toggle="tile"><div class="tile-inner"><div class="text-overflow">'.$cname.'<div style="display:block;float: right;">'.$n_name.'</div></div></div></div><div class="tile-active-show collapse" id="'.$name.'"><div class="tile-sub">';

			for ($i=0; $i<$n_name; $i++) { 
				echo '<div class="tile"><div class="tile-inner">';
					echo '<a href="'.$l_name[$i+1].'" target="_blank">'.$t_name[$i+1].'</a>';
				echo '</div></div>';
			}
		
		echo '<div class="tile"><div class="tile-footer-btn pull-left">';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://'.$nowout.'/">'.$name.'</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://www.baidu.com/s?wd=site%3A'.$nowout.'%20'.$title.'">百度</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://bing.com/search?q=site%3A'.$nowout.'%20'.$title.'">必应</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="https://www.google.com/#q=site%3A'.$nowout.'%20'.$title.'">谷歌</a>';
		echo '</div></div></div></div></div>';
}
//特殊代码功能
//only:\b\d\f\p\l\i\y\bda/
//exc:\b\d\f\p\l\i\y\bda/
//0.0.bilibili
//0.1.dilidili
//0.2.fcdm
//0.3.pptv
//0.4.letv
//0.5.iqiyi
//0.6.youku
//0.7.baiduall
function ifcode($title){
	$ifrun=array();
	if (substr_count($title,'only:')==1) {
		$scode=getSubstr($title,'only:','/');
			if (substr_count($scode,'\b')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\d')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\f')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\p')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\l')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\i')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\y')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\bda')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}
	} elseif (substr_count($title,'exc:')==1) {
		$scode=getSubstr($title,'exc:','/');
			if (substr_count($scode,'\b')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\d')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\f')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\p')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\l')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\i')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\y')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\bda')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}
	} else{
		for ($i=0; $i < 8; $i++) { 
			array_push($ifrun,'true');
		}
	}
	return $ifrun;
}
// Download Info
// 0.标题
// 1.链接
// 2.时间
// 3.BT
// 4.分类
// 5.数量
function DMHY($title){
  	$rst_t=array("标题");
  	$rst_l=array("链接");
  	$rst_d=array("时间");
  	$rst_bt=array("BT");
  	$rst_cat=array("分类");
  	$dmhy=array();
	$link='http://share.dmhy.org/topics/rss/rss.xml?keyword='.$title;
	$link=curl_get_contents($link);
	$number=substr_count($link,'<item>');

	for ($i=0; $i<$number; $i++) { 
		$f='<item>'.getSubstr($link,'<item>','</item>').'</item>';
		$t=getSubstr($f,'<title><![CDATA[',']]></title>');
		$l=getSubstr($f,'<link>','</link>');
		$d=getSubstr($f,'<pubDate>','</pubDate>');
		$bt=getSubstr($f,'<enclosure url="','" ');
		$cat=getSubstr($f,'" ><![CDATA[',']]></category>');
		$link=str_replace($f,'',$link);

		array_push($rst_t,$t);
		array_push($rst_l,$l);
		array_push($rst_d,$d);
		array_push($rst_bt,$bt);
		array_push($rst_cat,$cat);
	}

	array_push($dmhy,$rst_t);
	array_push($dmhy,$rst_l);
	array_push($dmhy,$rst_d);
	array_push($dmhy,$rst_bt);
	array_push($dmhy,$rst_cat);
	array_push($dmhy,$number);

	return $dmhy;
}
//判断
function asrh($title,$ifrun){
	$urls=array();
		$none='http://7vzp04.com1.z0.glb.clouddn.com/none.txt';
		//if ($ifrun[0]=='true') {
			$stitle='http://search.bilibili.com/all?keyword='.urlencode($title);
		//}	else{
		//	$stitle=$none;
		//}
		array_push($urls,$stitle);//0
		if ($ifrun[1]=='true') {
			$stitle1='http://www.baidu.com/s?wd=site%3Awww.dilidili.com%20'.urlencode($title).'&pn=0';
			$stitle2='http://www.baidu.com/s?wd=site%3Awww.dilidili.com%20'.urlencode($title).'&pn=10';
		}	else{
			$stitle1=$none;
			$stitle2=$none;
		}
		array_push($urls,$stitle1);//1
		array_push($urls,$stitle2);//2
		if ($ifrun[2]=='true') {
			$stitle='http://www.fengchedm.com/common/search.aspx?key='.urlencode($title);
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//3
		if ($ifrun[3]=='true') {
			$stitle='http://search.pptv.com/result?search_query='.urlencode($title).'&result_type=3';
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//4
		if ($ifrun[4]=='true') {
			$stitle='http://www.baidu.com/s?wd=site%3Awww.le.com%20'.urlencode($title).'&pn=0';
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//5
		if ($ifrun[5]=='true') {
			$stitle='http://www.baidu.com/s?wd=site%3Awww.iqiyi.com%20'.urlencode($title).'&pn=0';
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//6
		if ($ifrun[6]=='true') {
			$stitle='http://www.baidu.com/s?wd=site%3Awww.youku.com%20'.urlencode($title).'&pn=0';
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//7
		if ($ifrun[7]=='true') {
			$stitle='http://www.baidu.com/s?wd='.urlencode($title);
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//8
	//获取网页数据
		$frst=curl_multi($urls);
		$rst=array();
		array_push($rst,$frst[0]);//bilibili
		array_push($rst,$frst[1].$frst[2]);//dilidili
		array_push($rst,$frst[3]);//fcdm
		array_push($rst,$frst[4]);//pptv
		array_push($rst,$frst[5]);//letv
		array_push($rst,$frst[6]);//iqiyi
		array_push($rst,$frst[7]);//youku
		array_push($rst,$frst[8]);//baiduall
	return $rst;
}
?>