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
	$webtext=json_decode($title,true);
	$number=count($webtext['data']['result']['media_bangumi']);
	$rst_t=array("airAnime_title");
	$rst_l=array("airAnime_link");
	$bilibili=array();

	for ($n=0; $n<$number; $n++) {
		$l='https://www.bilibili.com/bangumi/media/md'.$webtext['data']['result']['media_bangumi'][$n]['media_id'];
		$t=$webtext['data']['result']['media_bangumi'][$n]['title'];
		$t=str_replace('<em class="keyword">','',$t);
		$t=str_replace('</em>','',$t);
		array_push($rst_l,$l);
		array_push($rst_t,$t);
	}

	array_push($bilibili,$rst_t);
	array_push($bilibili,$rst_l);
	array_push($bilibili,$number);
	return $bilibili;
}

// 嘀哩嘀哩搜索
function dilidiliS($title,$ori1,$ori2){
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
			$sinm1=howtextsimilar(strtoupper($t),strtoupper($ori1));
			$sinm2=howtextsimilar(strtoupper($t),strtoupper($ori2));
			$sinm=($sinm1+$sinm2) / 2;
			if ($sinm>=0.3) {
			array_push($rst_t,$t);
			$l=$rst[3][$i];
			array_push($rst_l,$l);
			}
		}

	array_push($dilidili,$rst_t);
	array_push($dilidili,$rst_l);
	array_push($dilidili,count($rst_t)-1);
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
function pptvS($title,$ori1,$ori2){
	$webtext=$title;
	$webtext=str_replace('title="详情"','',$webtext);
	$number=substr_count($webtext,'<div class="bd fr">');
	$rst_t=array("pptv标题");
	$rst_l=array("pptv链接");
	$pptv=array();

		for ($n=0; $n<$number; $n++) {
			$f='<i class="ico02"></i></a>'.getSubstr($webtext,'<i class="ico02"></i></a>','<dd class="pinfo pinfo2">').'<dd class="pinfo pinfo2">';
			$l=getSubstr($f,'<a href="','" target="_blank"');
			$t=getSubstr($f,' title="','">');
			$webtext=str_replace($f,'',$webtext);

			array_push($rst_t,$t);
			array_push($rst_l,$l);
		}

		array_push($pptv,$rst_t);
		array_push($pptv,$rst_l);
		array_push($pptv,count($rst_t)-1);
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
function baiduS($title,$zz,$page,$wst,$ori1,$ori2){
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
		$sinm1=howtextsimilar(strtoupper($t),strtoupper($ori1));
		$sinm2=howtextsimilar(strtoupper($t),strtoupper($ori2));
		$sinm=($sinm1+$sinm2) / 2;
		if ($sinm>=0.3) {
			array_push($rst_t,$t);
			$l=$rst[3][$i];
			array_push($rst_l,$l);
		}
    }
  }
  array_push($baiduS,$rst_t);
  array_push($baiduS,$rst_l);
  array_push($baiduS,count($rst_t)-1);
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

			if (substr_count($scode,'\x')==1) {
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

			if (substr_count($scode,'\x')==1) {
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

function picS($picurl){ //参考文档及API token获取:https://trace.moe
	if ($picurl!='') {
	$image_file = $picurl;
	$image_info = getimagesize($image_file);
	$type = pathinfo($image_file, PATHINFO_EXTENSION);
	if ($type=='gif') {
		$picurl = str_replace('.gif','',$picurl);
		$picurl = $picurl.'t.jpg';
		$image_file = $picurl;
		$image_info = getimagesize($image_file);
		$type = pathinfo($image_file, PATHINFO_EXTENSION);
	}
	if ($type=='jpg') {
		$type='jpeg';
	}
	$imgbase64 = 'data:image/'.$type.';base64,' . chunk_split(base64_encode(file_get_contents($image_file)));

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://trace.moe/api/search?token={your_token}');
    curl_setopt($curl, CURLOPT_HEADER, 0);
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
// Bing
function decode_Bing($data){
	libxml_disable_entity_loader(true); 
	$xmlstring = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA); 
	$val = json_decode(json_encode($xmlstring),true); 
	return $val; 
}
function getItem_Bing_XSJDM($data,$ori1,$ori2){
	$rst_t=array("新世界动漫标题");
	$rst_l=array("新世界动漫链接");
	$xsjdm=array();
	$data=$data['channel']['item'];
	$num=count($data);
	$pre='/{"title":"(.*?)_全集(.*?)","url":"(.*?)"}/';//JOJO的奇妙冒险动漫全集（日语 )高清在线观看 ...- 新世界动漫
	for ($i=0; $i < $num; $i++) { 
		$t=$data[$i]['title'];
		$if_t=strstr($t,'动漫全集');
		if (strstr($t,'动漫全集')!='') {
			$t=str_replace($if_t,'',$t);
		}
		$sinm1=howtextsimilar(strtoupper($t),strtoupper($ori1));
		$sinm2=howtextsimilar(strtoupper($t),strtoupper($ori2));
		$sinm=($sinm1+$sinm2) / 2;
		if ($sinm>=0.3) {
			array_push($rst_t,$t);
			array_push($rst_l,$data[$i]['link']);
		}
	}
	array_push($xsjdm, $rst_t);
	array_push($xsjdm, $rst_l);
	array_push($xsjdm, count($rst_t));
	return $xsjdm;
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
	$link='http://mikanani.me/RSS/Search?searchstr='.$title;
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
			$stitle='https://api.bilibili.com/x/web-interface/search/all?jsonp=jsonp&keyword='.urlencode($title);
		//}	else{
		//	$stitle=$none;
		//}
		array_push($urls,$stitle);//0
		if ($ifrun[1]=='true') {
			$stitle1='http://www.baidu.com/s?wd=site%3Awww.dilidili.wang%20'.urlencode($title).'&pn=0';
			$stitle2='http://www.baidu.com/s?wd=site%3Awww.dilidili.com%20'.urlencode($title).'&pn=0';
			$stitle3='http://www.baidu.com/s?wd=site%3Awww.dilidili.com%20'.urlencode($title).'&pn=10';
		}	else{
			$stitle1=$none;
			$stitle2=$none;
			$stitle3=$none;
		}
		array_push($urls,$stitle1);//1
		array_push($urls,$stitle2);//2
		array_push($urls,$stitle3);//3
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

		if ($ifrun[9]=='true') {
			$stitle='http://www.baidu.com/s?wd=site%3Ax4jdm.com%20'.urlencode($title.'动漫全集').'&pn=0';
		}	else{
			$stitle=$none;
		}
		array_push($urls,$stitle);//11
	//获取网页数据
		$frst=curl_multi($urls);
		$rst=array();
		array_push($rst,$frst[0]);//bilibili 0
		array_push($rst,$frst[1].$frst[2].$frst[3]);//dilidili 1
		array_push($rst,$frst[4]);//fcdm 2
		array_push($rst,$frst[5]);//pptv 3
		array_push($rst,$frst[6]);//letv 4
		array_push($rst,$frst[7]);//iqiyi 5
		array_push($rst,$frst[8]);//youku 6
		array_push($rst,$frst[9]);//baiduall 7
		array_push($rst,$frst[10]);//tencenttv 8
		array_push($rst,$frst[11]);//bangumi info 9
		array_push($rst,$frst[12]);//xsjdm 10
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
// 推测搜索内容
function whatstitle($title){
	// 取百度联想结果
	$rst=array(); //百度联想结果
	$f_rst=array(); //判断中的暂时数据
	$a_rst=array(); //最终数组1
	$b_rst=array(); //最终数组2
    $link=curl_get_contents('https://sp0.baidu.com/5a1Fazu8AA54nxGko9WTAnF6hhy/su?wd='.urlencode($title).'&json=1&p=3');
    $link=mb_convert_encoding($link, 'utf-8', 'gbk');
    $link=getSubstr($link,'s":[',']});');
    $num=substr_count($link,'","')+1;
    if ($num!=1) {
        for ($i=0; $i < $num; $i++) { 
            if (getSubstr($link,'"','",')=='') {
                $f=getSubstr($link,'"','"');
            } else{
                $f=getSubstr($link,'"','",');
            }
            $link=str_replace('"'.$f.'"','',$link);

            // 除杂
    		$f=str_replace('第一季','',$f);
    		$f=str_replace('第二季','',$f);
    		$f=str_replace('第三季','',$f);
    		$f=str_replace('第四季','',$f);
    		$f=str_replace('第五季','',$f);
    		$f=str_replace('第六季','',$f);
    		$f=str_replace('第一部','',$f);
    		$f=str_replace('第二部','',$f);
    		$f=str_replace('第三部','',$f);
    		$f=str_replace('第四部','',$f);
    		$f=str_replace('第五部','',$f);
    		$f=str_replace('第六部','',$f);
    		$n=mb_substr($f,mb_strlen($f)-1,mb_strlen($f),'utf-8');
			if ($n=='1' or $n=='2' or $n=='3' or $n=='4' or $n=='5' or $n=='6' or $n=='7' or $n=='8' or $n=='9') {
				$f=mb_substr($f,0,mb_strlen($f)-1);
			}
    		$n=mb_substr($f,mb_strlen($f)-2,mb_strlen($f),'utf-8');
			if ($n=='01' or $n=='02' or $n=='03' or $n=='04' or $n=='05' or $n=='06' or $n=='07' or $n=='08' or $n=='09') {
				$f=mb_substr($f,0,mb_strlen($f)-2);
			}

            array_push($rst,$f);
            $text=$text.$f;
        }
    }
    // 取出最长文本
    $max=0;
    for ($i=0; $i < count($rst); $i++) { 
    	$tlength=mb_strlen($rst[$i]);
    	if ($tlength>$max) {
    		$max=$tlength;
    		$turn=$i;
    	}
    }
    $ltitle=$rst[$turn];

    // 反复判断出现次数并筛选结果
    for ($i=0; $i < $max; $i++) { 
    	$iftitle=mb_substr($ltitle,0,$max-$i,'utf-8');
    	if (substr_count($text,$iftitle)>count($rst)/2) { // 大于一半就收手,233333.
    		$ftitle=$iftitle;
    		break;
    	}
    }

	// 判断是否存在完全相同文本
	for ($i=0; $i < count($rst); $i++) { 
		if ($title==$rst[$i]) {
			$ftitle=$title;
			$aexist=1;
			break;
		}
		if ($ftitle==$rst[$i]) {
			$aexist=1;
			break;
		}
	}
    // 若以上未判断出结果，则继续判断
    // 模拟筛选出出现次数最多的一个可用结果
    if ($aexist!=1) {
    	$ftitle='';
    	for ($i=0; $i < count($rst); $i++) { 
    	$t=$rst[$i];
    		for ($a=0; $a < mb_strlen($t)-mb_strlen($title); $a++) { 
    			$f=mb_substr($t,0,mb_strlen($title)+$a+1,'utf-8');
    			if (substr_count($text,$f)>1) {
    				array_push($f_rst,$f);
    				if ($a==mb_strlen($t)-mb_strlen($title)-1) {
    					if ($f_rst[0]!='') {
    						array_push($a_rst,$f_rst);
    						$f_rst=array();
    					}
    				}
    			} else {
    				if ($f_rst[0]!='') {
    					array_push($a_rst,$f_rst);
    					$f_rst=array();
    				}
    				break;
    			}
    		}
    	}
    }

    // 最终结果选择并再次筛选最多出现次数
    if ($ftitle!='') {
    	$ftitle=$ftitle;
    } elseif (count($a_rst)==0) {
    	$ftitle=$title;
    } elseif (count($a_rst)==1) {
    	$ftitle=$a_rst[0][count($a_rst[0])-1];
    } else {
    	for ($i=0; $i < count($a_rst); $i++) { 
    		array_push($b_rst,$a_rst[$i][count($a_rst[$i])-1]);
    		$ftext=$ftext.$a_rst[$i][count($a_rst[$i])-1];
    	}
    	$maxn=0;
    	for ($i=0; $i < count($b_rst); $i++) { 
    		$t=$b_rst[$i];
    		$num=substr_count($ftext,$t);
    		if ($num>$maxn) {
    			$ftitle=$t;
    		}
    	}
    }

    $ftitle=str_replace(' ','',$ftitle);
    return $ftitle;
}
//简单余弦函数判断短文本相似度
function howtextsimilar($text1,$text2){
	$gentext=$text1.$text2;
	$fword='';
	$word=array(); //文本单分割结果
	$num1=array(); //文本1向量构建
	$num2=array(); //文本2向量构建

	//取单个词并加入数组
	for ($i=0; $i < mb_strlen($gentext); $i++) { 
		$f=mb_substr($gentext,$i,1,'utf-8');
		if (substr_count($fword,$f)==0) {
			$fword=$fword.$f;
		}
	}
	for ($i=0; $i < mb_strlen($fword); $i++) { 
		$f=mb_substr($fword,$i,1,'utf-8');
		array_push($word,$f);
	}

	//判断词频
	for ($i=0; $i < mb_strlen($fword); $i++) { 
		$ntext1=substr_count($text1,$word[$i]);
		$ntext2=substr_count($text2,$word[$i]);
		array_push($num1,$ntext1);
		array_push($num2,$ntext2);
	}

	//计算余弦值
	$sum=0;
	$sumT1=0;
	$sumT2=0;
	for ($i=0; $i < mb_strlen($fword); $i++) { 
		$sum=$sum+$num1[$i]*$num2[$i];
		$sumT1=$sumT1+pow($num1[$i],2);
		$sumT2=$sumT2+pow($num2[$i],2);
	}
	$cos=$sum / (sqrt($sumT1 * $sumT2));
	
	return $cos;
}
//依据相似度筛选排列数组
function runtextsimilar($text,$ori1,$ori2){
	$rst=array();
	$nsimilar=array();

	$title=$text[0];
	for ($i=0; $i < count($title); $i++) { 
		$fn1=howtextsimilar($title[$i],$ori1);
		$fn2=howtextsimilar($title[$i],$ori2);
		$fn=($fn1+$fn2) / 2;
		array_push($nsimilar,$fn);
	}


}
?>