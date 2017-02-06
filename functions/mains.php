<?php
//添加番剧数据源步骤：(漫画小说步骤相同)
//1.在 asrh 中添加搜索链接 (站内搜索或百度协助搜索或其他的)
//2.添加搜索指令 ifrun (若有)
//3.run.php 中执行数据处理
//4.输出结果
//5.细节修补
//6.完善
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
       'timeout' => 6 //设置一个超时时间，单位为秒    
       )    
   )    
);
//////////////////////////////////////////////////
// 动画站内搜索
// 哔哩哔哩集合搜索
function bilibiliS($title){
	$webtext=$title;
	$number=substr_count($webtext,'<div class="left-img">');
	$rst_t=array("bilibili标题");
	$rst_l=array("bilibili链接");
	$bilibili=array();

		for ($n=0; $n<$number; $n++) {
			$f='<div class="left-img">'.getSubstr($webtext,'<div class="left-img">','</div>').'</div>';
			$l='http://bangumi.bilibili.com/anime/'.getSubstr($f,'//bangumi.bilibili.com/anime/','" ');
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
	preg_match_all('/{"title":"(.*?)在线&amp;下载(.+?)嘀哩嘀哩","url":"(.*?)"}/',$webtext,$rst);
	if (count($rst[0])==0) {
		unset($rst);
		preg_match_all('/{"title":"(.*?)丨(.*?)嘀哩嘀哩","url":"(.*?)"}/',$webtext,$rst);
	}
	$number=$number+count($rst[0]);
		// 载入 标题 链接
		for ($i=0; $i<count($rst[0]); $i++) { 
			$t=str_replace('-','',$rst[1][$i]);
			array_push($rst_t,$t);
			$l=$rst[3][$i];
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
// 无限动漫搜索
function wxdmS($title){
	$webtext=$title;
	$webtext=getSubstr($webtext,'<div class="le15 weight f14 s1">','</script></body></html>');
	$webtext=iconv("gb2312", "utf-8",$webtext);
	$number=substr_count($webtext,'<li>');
	$rst_t=array("无限动漫标题");
	$rst_l=array("无限动漫链接");
	$wxdm=array();

		for ($n=0; $n<$number; $n++) {
			$f='<li>'.getSubstr($webtext,'<li>','</li>').'</li>';
			$l='http://www.hkdm173.com'.getSubstr($f,'<a href="','" ');
			$t=getSubstr($f,'title="','"><');
			$webtext=str_replace($f,'',$webtext);
			array_push($rst_l,$l);
			array_push($rst_t,$t);
		}

		array_push($wxdm,$rst_t);
		array_push($wxdm,$rst_l);
		array_push($wxdm,$number);
		return $wxdm;
}
//////////////////////////////////////////////////
// 漫画站内搜索
// 图库漫画
function tkmhS($title){
	$webtext=$title;
	$number=substr_count($webtext,'<div class="book-list">');
	$rst_t=array("图库漫画标题");
	$rst_l=array("图库漫画链接");
	$tkmh=array();

		for ($n=0; $n<$number; $n++) {
			$f='<div class="book-list">'.getSubstr($webtext,'<div class="book-list">','</div>').'</div>';
			$l=getSubstr($f,'<a href="','">');
			$t=getSubstr($f,$l.'">','</a>');
			$l='http://m.tuku.cc'.$l;
			$webtext=str_replace($f,'',$webtext);
			array_push($rst_l,$l);
			array_push($rst_t,$t);
		}

		array_push($tkmh,$rst_t);
		array_push($tkmh,$rst_l);
		array_push($tkmh,$number);
		return $tkmh;
}
// 腾讯动漫
function txdm_mhS($title){
	$webtext=$title;
	$webtext=getSubstr($webtext,'<!-- 结果列表 ST -->','<!-- 结果列表 ED -->');
	$number=substr_count($webtext,'<li class="comic-item">');
	$rst_t=array("腾讯动漫_漫画标题");
	$rst_l=array("腾讯动漫_漫画链接");
	$txdm_mh=array();

		for ($n=0; $n<$number; $n++) {
			$f='<li class="comic-item">'.getSubstr($webtext,'<li class="comic-item">','</li>').'</li>';
			$l='http://m.ac.qq.com'.getSubstr($f,' href="','">');
			$t=getSubstr($f,'<strong class="comic-title">','</strong>');
			$webtext=str_replace($f,'',$webtext);
			array_push($rst_l,$l);
			array_push($rst_t,$t);
		}

		array_push($txdm_mh,$rst_t);
		array_push($txdm_mh,$rst_l);
		array_push($txdm_mh,$number);
		return $txdm_mh;
}
//////////////////////////////////////////////////
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
  	$number=$number+count($rst[0]);
    // 载入 标题 链接
    for ($i=0; $i<count($rst[0]); $i++) { 
    	$t=$rst[1][$i];
      	array_push($rst_t,$t);
      	$l=$rst[3][$i];
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
//////////////////////////////////////////////////
//特殊代码功能
//only:\b\d\f\p\l\i\y\bda\t/
//exc:\b\d\f\p\l\i\y\bda\t/
//0.0.bilibili
//0.1.dilidili
//0.2.fcdm
//0.3.pptv
//0.4.letv
//0.5.iqiyi
//0.6.youku
//0.7.baiduall
//0.8.tencenttv
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

			if (substr_count($scode,'\t')==1) {
				array_push($ifrun,'true');
			} else {
				array_push($ifrun,'false');
			}

			if (substr_count($scode,'\w')==1) {
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

			if (substr_count($scode,'\t')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}

			if (substr_count($scode,'\w')==1) {
				array_push($ifrun,'false');
			} else {
				array_push($ifrun,'true');
			}
	} else {
		for ($i=0; $i < 10; $i++) { 
			array_push($ifrun,'true');
		}
	}
	return $ifrun;
}

function iftype($title){
	$iftype='a';
	if (substr_count($title,'type:')==1) {
		$scode=getSubstr($title,'type:','/');
			if ($scode=='\c') {
				$iftype='c';
			} elseif ($scode=='\n') {
				$iftype='n';
			} elseif ($scode=='\d') {
				$iftype='d';
			}
	}
	return $iftype;
}

function picS($picurl){ //参考文档及API token获取:https://soruly.github.io/whatanime.ga/
	if ($picurl!='') {
	$image_file = $picurl;
	$image_info = getimagesize($image_file);
	$type = pathinfo($image_file, PATHINFO_EXTENSION);
	if ($type=='jpg') {
		$type='jpeg';
	}
	$imgbase64 = 'data:image/'.$type.';base64,' . chunk_split(base64_encode(file_get_contents($image_file)));

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://whatanime.ga/api/search?token={your_token}');
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    $post_data = array(
        "image" => $imgbase64
        );
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
	}
}
//////////////////////////////////////////////////
// Download Info old
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
	$link='http://air.yumoe.com/?'.$title;
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
// Download Info
// 0.标题
// 1.BT
// 2.Size
// 3.字幕组
// 4.数量
function MGJH($title){
  	$rst_t=array("标题");
  	$rst_bt=array("BT");
  	$rst_size=array("Size");
  	$rst_ori=array("字幕组");
  	$mgjh=array();
	$link='http://airanime.host.smartgslb.com/d/?'.$title;
	$link=curl_get_contents($link);
	$number=substr_count($link,'<item>');

	for ($i=0; $i<$number; $i++) { 
		// 暂时数据
		$f='<item>'.getSubstr($link,'<item>','</item>').'</item>';
		// 取标题
		$t=getSubstr($f,'<title>','</title>');
		// 取BT
		$bt=getSubstr($f,'<link>','</link>');
		// 取描述
		$des=getSubstr($f,'<description>','</description>');
		// 取大小
		$size=str_replace($t,'',$des);
		// 取字幕组
		$fori=getNeedBetween($des,"/\【(.*?)\】(.*?)/");
		if ($fori=='') {
			$fori=getNeedBetween($des,"/\[(.*?)\](.*?)/");
		}
		if (substr_count($ori,$fori)==0) {
			$ori=$ori.','.$fori;
			if (substr_count($fori,'&amp;')!=0) {
				$fori=str_replace('&amp;','&',$fori);
			}
			array_push($rst_ori,$fori);
		}
		// 替换
		$link=str_replace($f,'',$link);

		array_push($rst_t,$t);
		array_push($rst_bt,$bt);
		array_push($rst_size,$size);
	}
	array_push($rst_ori,'720');
	array_push($rst_ori,'1080');
	array_push($mgjh,$rst_t);
	array_push($mgjh,$rst_bt);
	array_push($mgjh,$rst_size);
	array_push($mgjh,$rst_ori);
	array_push($mgjh,$number);

	return $mgjh;
}
//////////////////////////////////////////////////
//抓取数据
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
		if ($ifrun[8]=='true') {
			$stitle='http://www.baidu.com/s?wd=site%3Av.qq.com%20'.urlencode($title).'&pn=0';
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//9

		$stitle=$none;
		//$stitle='http://bgm.tv/subject_search/'.urlencode($title).'?cat=2';
		array_push($urls,$stitle);//10

		//if ($ifrun[9]=='true') {
		//	$stitle='http://www.hkdm173.com/search.asp?searchword='.iconv("utf-8","gb2312",$title);
		//}	else{
		//	$stitle=$none;
		//}
		//array_push($urls,$stitle);//11
	//获取网页数据
		$frst=curl_multi($urls);
		$rst=array();
		array_push($rst,$frst[0]);//bilibili 0
		array_push($rst,$frst[1].$frst[2]);//dilidili 1
		array_push($rst,$frst[3]);//fcdm 2
		array_push($rst,$frst[4]);//pptv 3
		array_push($rst,$frst[5]);//letv 4
		array_push($rst,$frst[6]);//iqiyi 5
		array_push($rst,$frst[7]);//youku 6
		array_push($rst,$frst[8]);//baiduall 7
		array_push($rst,$frst[9]);//tencenttv 8
		array_push($rst,$frst[10]);//bangumi info 9
		//array_push($rst,$frst[11]);//wxdm 10
	return $rst;
}
function csrh($title){
	$urls=array();
		$none='http://7vzp04.com1.z0.glb.clouddn.com/none.txt';

		$stitle='http://www.baidu.com/s?wd=site%3Amanhua.dmzj.com%20'.urlencode($title).'&pn=0'; //动漫之家
		array_push($urls,$stitle);//0

		$stitle='http://www.baidu.com/s?wd=site%3Awww.buka.cn%20'.urlencode($title).'&pn=0'; //布卡漫画
		array_push($urls,$stitle);//1

		$stitle='http://www.baidu.com/s?wd=site%3Awww.dm5.com%20'.urlencode($title).'&pn=0'; //动漫屋
		array_push($urls,$stitle);//2

		$stitle='http://m.tuku.cc/comic/search?word='.urlencode($title); //图库漫画
		array_push($urls,$stitle);//3

		$stitle=$none;
		//$stitle='http://bgm.tv/subject_search/'.urlencode($title).'?cat=1';
		array_push($urls,$stitle);//4

		$stitle='http://m.ac.qq.com/search/result?word='.urlencode($title); //腾讯动漫_漫画
		array_push($urls,$stitle);//5
	//获取网页数据
		$frst=curl_multi($urls);
		$rst=array();
		array_push($rst,$frst[0]);//动漫之家 0
		array_push($rst,$frst[1]);//布卡漫画 1
		array_push($rst,$frst[2]);//动漫屋 2
		array_push($rst,$frst[3]);//图库漫画 3
		array_push($rst,$frst[4]);//bangumi info 4
		array_push($rst,$frst[5]);//腾讯动漫_漫画 5
	return $rst;
}
function nsrh($title){
	$urls=array();
		$none='http://7vzp04.com1.z0.glb.clouddn.com/none.txt';

		$stitle='http://www.baidu.com/s?wd=site%3Aac.qq.com%20'.urlencode($title).'&pn=0'; //腾讯动漫
		array_push($urls,$stitle);//0

		$stitle='http://www.baidu.com/s?wd=site%3Axs.dmzj.com%20'.urlencode($title).'&pn=0'; //动漫之家
		array_push($urls,$stitle);//1

		$stitle=$none;
		//$stitle='http://bgm.tv/subject_search/'.urlencode($title).'?cat=1';
		array_push($urls,$stitle);//2
	//获取网页数据
		$frst=curl_multi($urls);
		$rst=array();
		array_push($rst,$frst[0]);//腾讯动漫 0
		array_push($rst,$frst[1]);//动漫之家 1
		array_push($rst,$frst[2]);//bangumi info 2
	return $rst;
}
//////////////////////////////////////////////////
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
	$des=unicode_decode(getSubstr($webtext,'"summary":"','","'));
	$des=str_replace('rn','<br>',$des);
	$des=str_replace('　　','　',$des);

	array_push($rst,$name);//0
	array_push($rst,$des); //1
	}
	return $rst;
}
?>