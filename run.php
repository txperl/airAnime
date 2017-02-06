<?php
include("./functions/chttochs/convert.php");
require "./functions/mains.php";
header("Content-type: text/html; charset=utf-8");
if(is_array($_GET)&&count($_GET)>0){ 
	if(isset($_GET["title"])){
		$title=$_GET["title"];
	 if ($title!='') {
		//!image
		if (substr_count($title,'!image:')==1) {
			$picurl=getSubstr($title,'!image:',';');
			if (strlen(file_get_contents($picurl))<=1048869) { //判断图像大小
				$rst=picS($picurl);
				$name=unicode_decode(getSubstr($rst,'title_chinese":"','","')); //繁体
				if ($name!='') {
					$name=zhconversion_hans($name);  //简体
					$title=str_replace('!image:'.$picurl.';',$name,$title);
				} else {
					echo '<h3>图像搜索失败，请重试。可能原因包含：<br><h4>1.搜索次数达到限制，请稍候尝试(几率很大)。<br>2.目标服务器正在维护或无法访问。<br>3.本服务器网络速度问题。<br>或者是，<span style="color:#FD5B78;">请正确输入图像链接(几率也很大)！！</span>[具体参考 搜索指令 页面]</h4><br><br><br><br>';
					exit();
				}
			} else {
				echo '<center><h3>图像大小超出1MB限制，请压缩后搜索</h3></center>';
				exit();
			}
		}
		//iftype
		$iftype=iftype($title);
		if (substr_count($title,'type:')==1) {
			$scode=' type:'.getSubstr($title,' type:','/').'/';
			$title=str_replace($scode,'',$title);
		}
		//ifrun
		$ifrun=ifcode($title);
		if (substr_count($title,'only:')==1) {
			$scode=' only:'.getSubstr($title,' only:','/').'/';
			$title=str_replace($scode,'',$title);
		}
		if (substr_count($title,'exc:')==1) {
			$scode=' exc:'.getSubstr($title,' exc:','/').'/';
			$title=str_replace($scode,'',$title);
		}
		if ($iftype=='c') {
			echo '<h2 class="content-sub-heading">'.$title.' [Comic]</h2>';
		} elseif ($iftype=='n') {
			echo '<h2 class="content-sub-heading">'.$title.' [Novel]</h2>';
		} elseif ($iftype=='d') {
			echo '<h2 class="content-sub-heading">'.$title.' [InfoDownload] Searching...</h2>';
		} else{
			echo '<h2 class="content-sub-heading">'.$title.'</h2>';
		}
		if ($picurl!='') {
			echo '<div style="text-align:center;"><img style="max-width:100%;height:auto;" src="'.$picurl.'"></div>';
		}
		// 抓取网页
			//动画
		if ($iftype=='a') {
			$webd=asrh($title,$ifrun);
		// bangumi info
			//$r_info=infoS($webd[9]);
			//$n_info=$r_info[0];
			//$des_info=$r_info[1];
			//if ($des_info=='') {
				$des_info='(ฅ´ω`ฅ) 番剧信息未完成唔...';
			//}
		// bilibili 结果
		//if ($ifrun[0]=='true') {
			$r_bilibili=bilibiliS($webd[0]);
			$n_bilibili=$r_bilibili[2];
			$t_bilibili=$r_bilibili[0];
			$l_bilibili=$r_bilibili[1];
		//}
		// dilidili 结果
		if ($ifrun[1]=='true'){
			$r_dilidili=dilidiliS($webd[1]);
			$n_dilidili=$r_dilidili[2];
			$t_dilidili=$r_dilidili[0];
			$l_dilidili=$r_dilidili[1];
		}
		// fcdm 结果
		if ($ifrun[2]=='true'){
			$r_fcdm=fcdmS($webd[2]);
			$n_fcdm=$r_fcdm[2];
			$t_fcdm=$r_fcdm[0];
			$l_fcdm=$r_fcdm[1];
		}
		// pptv 结果
		if ($ifrun[3]=='true'){
			$r_pptv=pptvS($webd[3]);
			$n_pptv=$r_pptv[2];
			$t_pptv=$r_pptv[0];
			$l_pptv=$r_pptv[1];
		}
		// letv 结果
		if ($ifrun[4]=='true'){
			$r_letv=baiduS($webd[4],'/{"title":"(.*?)_全集(.*?)","url":"(.*?)"}/',1,'www.le.com');// 1 参数暂时无用，下同
			$n_letv=$r_letv[2];
			$t_letv=$r_letv[0];
			$l_letv=$r_letv[1];
			if ($n_letv=='') {
				$r_letv=baiduS($webd[4],'/{"title":"(.*?)-在线观看-动漫(.*?)","url":"(.*?)"}/',1,'www.le.com');// 1 参数暂时无用，下同
				$n_letv=$r_letv[2];
				$t_letv=$r_letv[0];
				$l_letv=$r_letv[1];
			}
		}
		// iqiyi 结果
		if ($ifrun[5]=='true'){
			$r_iqiyi=baiduS($webd[5],'/{"title":"(.*?)-动漫动画-全集(.*?)","url":"(.*?)"}/',1,'www.iqiyi.com');
			$n_iqiyi=$r_iqiyi[2];
			$t_iqiyi=$r_iqiyi[0];
			$l_iqiyi=$r_iqiyi[1];
			if ($n_iqiyi==0) {
				$r_iqiyi=baiduS($webd[5],'/{"title":"(.*?)-全集在线观看-动漫(.*?)","url":"(.*?)"}/',1,'www.iqiyi.com');
				$n_iqiyi=$r_iqiyi[2];
				$t_iqiyi=$r_iqiyi[0];
				$l_iqiyi=$r_iqiyi[1];
			}
		}
		// youku 结果
		if ($ifrun[6]=='true'){
			$r_youku=baiduS($webd[6],'/{"title":"(.*?)—日本—动漫—优酷(.*?)","url":"(.*?)"}/',1,'www.youku.com');
			$n_youku=$r_youku[2];
			$t_youku=$r_youku[0];
			$l_youku=$r_youku[1];
			if ($n_youku==0) {
				$r_youku=baiduS($webd[6],'/{"title":"(.*?)—日本—动漫(.*?)","url":"(.*?)"}/',1,'www.youku.com');
				$n_youku=$r_youku[2];
				$t_youku=$r_youku[0];
				$l_youku=$r_youku[1];
			}
		}
		// 百度集合搜索 结果
		if ($ifrun[7]=='true'){
			$r_baiduall=baiduallS($webd[7]);
			$n_baiduall=$r_baiduall[2];
			$a_baiduall=$r_baiduall[1];
		}
		// 腾讯视频 结果
		if ($ifrun[8]=='true'){
			$r_tencenttv=baiduS($webd[8],'/{"title":"(.*?)-高清(.*?)","url":"(.*?)"}/',1,'v.qq.com');
			$n_tencenttv=$r_tencenttv[2];
			$t_tencenttv=$r_tencenttv[0];
			$l_tencenttv=$r_tencenttv[1];
			if ($n_tencenttv==0) {
				$r_tencenttv=baiduS($webd[8],'/{"title":"(.*?)-动漫(.*?)","url":"(.*?)"}/',1,'v.qq.com');
				$n_tencenttv=$r_tencenttv[2];
				$t_tencenttv=$r_tencenttv[0];
				$l_tencenttv=$r_tencenttv[1];
			}
		}
		// 无限动漫 结果
		//if ($ifrun[9]=='true'){
		//	$r_wxdm=wxdmS($webd[10]);
		//	$n_wxdm=$r_wxdm[2];
		//	$t_wxdm=$r_wxdm[0];
		//	$l_wxdm=$r_wxdm[1];
		//}
		$statol=$n_bilibili+$n_dilidili+$n_baiduall+$n_letv+$n_iqiyi+$n_pptv+$n_fcdm+$n_youku+$n_tencenttv;
		// 简要 数量
		echo '<div class="tile-wrap"><div class="tile"><div class="tile-inner">';
			echo $des_info;
		echo '</div></div>';

		// 结果
		$nowout='';
		echo '<h2 class="content-sub-heading">Results ('.$statol.')</h2>';
		//bilibili 保留示范
		//if ($ifrun[0]=='true') {
		$nowout='www.bilibili.com';
		echo '<div class="tile-wrap"><div class="tile tile-collapse"><div data-target="#bilibili" data-toggle="tile"><div class="tile-inner"><div class="text-overflow">Bilibili<div style="display:block;float: right;">'.$n_bilibili.'</div></div></div></div><div class="tile-active-show collapse" id="bilibili"><div class="tile-sub">';

			for ($i=0; $i<$n_bilibili; $i++) { 
				echo '<div class="tile"><div class="tile-inner">';
					echo '<a href="'.$l_bilibili[$i+1].'" target="_blank">'.$t_bilibili[$i+1].'</a>';
				echo '</div></div>';
			}
		
		echo '<div class="tile"><div class="tile-footer-btn pull-left">';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://'.$nowout.'/">Bilibili</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://www.baidu.com/s?wd=site%3A'.$nowout.'%20'.$title.'">百度</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://bing.com/search?q=site%3A'.$nowout.'%20'.$title.'">必应</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="https://www.google.com/#q=site%3A'.$nowout.'%20'.$title.'">谷歌</a>';
		echo '</div></div></div></div>';
		//}
		//dilidili 保留示范
		if ($ifrun[1]=='true') {
		$nowout='www.dilidili.com';
		echo '<div class="tile tile-collapse"><div data-target="#dilidili" data-toggle="tile"><div class="tile-inner"><div class="text-overflow">Dilidili<div style="display:block;float: right;">'.$n_dilidili.'</div></div></div></div><div class="tile-active-show collapse" id="dilidili"><div class="tile-sub">';

			for ($i=0; $i<$n_dilidili; $i++) { 
				echo '<div class="tile"><div class="tile-inner">';
					echo '<a href="'.$l_dilidili[$i+1].'" target="_blank">'.$t_dilidili[$i+1].'</a>';
				echo '</div></div>';
			}
		
		echo '<div class="tile"><div class="tile-footer-btn pull-left">';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://'.$nowout.'/">Dilidili</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://www.baidu.com/s?wd=site%3A'.$nowout.'%20'.$title.'">百度</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://bing.com/search?q=site%3A'.$nowout.'%20'.$title.'">必应</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="https://www.google.com/#q=site%3A'.$nowout.'%20'.$title.'">谷歌</a>';
		echo '</div></div></div></div></div>';
		}
		//fcdm
		if ($ifrun[2]=='true') {
		loaclSS($title,'www.fengchedm.com','FCDM','风车动漫',$n_fcdm,$l_fcdm,$t_fcdm);
		}
		//pptv
		if ($ifrun[3]=='true') {
		loaclSS($title,'www.pptv.com','PPTV','PPTV聚力',$n_pptv,$l_pptv,$t_pptv);
		}
		//letv
		if ($ifrun[4]=='true') {
		baiduSS($title,'www.letv.com','Letv','乐视TV',$n_letv,$l_letv,$t_letv);
		}
		//iqiyi
		if ($ifrun[5]=='true') {
		baiduSS($title,'www.iqiyi.com','iQiyi','爱奇艺',$n_iqiyi,$l_iqiyi,$t_iqiyi);
		}
		//youku
		if ($ifrun[6]=='true') {
		baiduSS($title,'www.youku.com','Youku','优酷',$n_youku,$l_youku,$t_youku);
		}
		//tencenttv
		if ($ifrun[8]=='true') {
		baiduSS($title,'v.qq.com','TencentTV','腾讯视频',$n_tencenttv,$l_tencenttv,$t_tencenttv);
		}
		//if ($ifrun[9]=='true') {
		//loaclSS($title,'www.hkdm173.com','WXDM','无限动漫',$n_wxdm,$l_wxdm,$t_wxdm);
		//}
		//baiduall
		if ($ifrun[7]=='true') {
		$nowout='www.baidu.com';
		echo '<div class="tile tile-collapse"><div data-target="#baiduall" data-toggle="tile"><div class="tile-inner"><div class="text-overflow">BaiduAll<div style="display:block;float: right;">'.$n_baiduall.'</div></div></div></div><div class="tile-active-show collapse" id="baiduall"><div class="tile-sub">';

			for ($i=0; $i<$n_baiduall; $i++) { 
				echo '<div class="tile"><div class="tile-inner">';
					echo $a_baiduall[$i];
				echo '</div></div>';
			}
		
		echo '<div class="tile"><div class="tile-footer-btn pull-left">';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://'.$nowout.'/">Baidu</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://www.baidu.com/s?wd='.$title.'">百度</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="http://bing.com/search?q='.$title.'">必应</a>';
			echo '<a target="_blank" class="btn btn-flat waves-attach" href="https://www.google.com/#q='.$title.'">谷歌</a>';
		echo '</div></div></div></div></div>';
		}
		//下载源地址测试
		echo '<div class="tile tile-collapse"><div data-target="#infodownload" data-toggle="tile"><div class="tile-inner"><div class="text-overflow">InfoDownload</div></div></div><div class="tile-active-show collapse" id="infodownload"><div class="tile-sub">';
				echo '<div class="tile"><div class="tile-inner">';
					echo '<a href="./d/?'.$title.'" target="_blank">蜜柑计划</a>';
				echo '</div></div>';
				echo '<div class="tile"><div class="tile-inner">';
					echo '<a href="#" target="_blank">动漫花园BDRIP[未开放]</a>';
				echo '</div></div>';
		echo '</div></div></div>';
		// 结束
		}
			//漫画
		if ($iftype=='c') {
			$webd=csrh($title);
			// bangumi info
			//$r_info=infoS($webd[9]);
			//$n_info=$r_info[0];
			//$des_info=$r_info[1];
			//if ($des_info=='') {
				$des_info='(ฅ´ω`ฅ) 番剧信息未完成唔...';
			//}
			//动漫之家
			$r_dmzj=baiduS($webd[0],'/{"title":"(.*?)_动漫之家(.*?)","url":"(.*?)"}/',1,'manhua.dmzj.com');
			$n_dmzj=$r_dmzj[2];
			$t_dmzj=$r_dmzj[0];
			$l_dmzj=$r_dmzj[1];
			if ($n_dmzj==0) {
				$r_dmzj=baiduS($webd[0],'/{"title":"(.*?)-动漫之家(.*?)","url":"(.*?)"}/',1,'manhua.dmzj.com');
				$n_dmzj=$r_dmzj[2];
				$t_dmzj=$r_dmzj[0];
				$l_dmzj=$r_dmzj[1];
			}
			//布卡漫画
			$r_bkmh=baiduS($webd[1],'/{"title":"(.*?)-布卡漫画(.*?)","url":"(.*?)"}/',1,'www.buka.cn');
			$n_bkmh=$r_bkmh[2];
			$t_bkmh=$r_bkmh[0];
			$l_bkmh=$r_bkmh[1];
			//动漫屋
			$r_dmw=baiduS($webd[2],'/{"title":"(.*?)-动漫屋(.*?)","url":"(.*?)"}/',1,'www.dm5.com');
			$n_dmw=$r_dmw[2];
			$t_dmw=$r_dmw[0];
			$l_dmw=$r_dmw[1];
			if ($n_dmw==0) {
				$r_dmw=baiduS($webd[2],'/{"title":"(.*?)漫画_(.*?)","url":"(.*?)"}/',1,'www.dm5.com');
				$n_dmw=$r_dmw[2];
				$t_dmw=$r_dmw[0];
				$l_dmw=$r_dmw[1];
			}
			//图库漫画
			$r_tkmh=tkmhS($webd[3]);
			$n_tkmh=$r_tkmh[2];
			$t_tkmh=$r_tkmh[0];
			$l_tkmh=$r_tkmh[1];
			//腾讯动漫_漫画
			$r_txdm_mh=txdm_mhS($webd[5]);
			$n_txdm_mh=$r_txdm_mh[2];
			$t_txdm_mh=$r_txdm_mh[0];
			$l_txdm_mh=$r_txdm_mh[1];

			$statol=$n_dmzj+$n_bkmh+$n_dmw+$n_tkmh+$n_txdm_mh;
			// 简要 数量
			echo '<div class="tile-wrap"><div class="tile"><div class="tile-inner">';
				echo $des_info;
			echo '</div></div>';

			// 结果
			echo '<h2 class="content-sub-heading">Results ('.$statol.')</h2>';
				//动漫之家
				echo '<div class="tile-wrap">';
				baiduSS($title,'manhua.dmzj.com','DMZJ','动漫之家',$n_dmzj,$l_dmzj,$t_dmzj);
				//布卡漫画
				baiduSS($title,'www.buka.cn','BKMH','布卡漫画',$n_bkmh,$l_bkmh,$t_bkmh);
				//动漫屋
				baiduSS($title,'www.dm5.com','DMW','动漫屋',$n_dmw,$l_dmw,$t_dmw);
				//图库漫画
				loaclSS($title,'m.tuku.cc','TKMH','图库漫画',$n_tkmh,$l_tkmh,$t_tkmh);
				//腾讯动漫_漫画
				loaclSS($title,'m.ac.qq.com','TXDM_MH','腾讯动漫',$n_txdm_mh,$l_txdm_mh,$t_txdm_mh);
				echo '</div>';
		//结束
		}
			//小说
		if ($iftype=='n') {
			$webd=nsrh($title);
			// bangumi info
			//$r_info=infoS($webd[9]);
			//$n_info=$r_info[0];
			//$des_info=$r_info[1];
			//if ($des_info=='') {
				$des_info='(ฅ´ω`ฅ) 番剧信息未完成唔...';
			//}
			//腾讯动漫
			$r_txdm=baiduS($webd[0],'/{"title":"(.*?)-在线漫画(.*?)","url":"(.*?)"}/',1,'ac.qq.com');
			$n_txdm=$r_txdm[2];
			$t_txdm=$r_txdm[0];
			$l_txdm=$r_txdm[1];
			if ($n_txdm==0) {
				$r_txdm=baiduS($webd[0],'/{"title":"(.*?)_腾讯动漫(.*?)","url":"(.*?)"}/',1,'ac.qq.com');
				$n_txdm=$r_txdm[2];
				$t_txdm=$r_txdm[0];
				$l_txdm=$r_txdm[1];
			}
			//动漫之家
			$r_dmzjn=baiduS($webd[1],'/{"title":"(.*?)\|(.*?)","url":"(.*?)"}/',1,'xs.dmzj.com');
			$n_dmzjn=$r_dmzjn[2];
			$t_dmzjn=$r_dmzjn[0];
			$l_dmzjn=$r_dmzjn[1];

			$statol=$n_dmzjn+$n_txdm;
			// 简要 数量
			echo '<div class="tile-wrap"><div class="tile"><div class="tile-inner">';
				echo $des_info;
			echo '</div></div>';

			// 结果
			echo '<h2 class="content-sub-heading">Results ('.$statol.')</h2>';
				//动漫之家
				echo '<div class="tile-wrap">';
				baiduSS($title,'xs.dmzj.com','DMZJ','动漫之家',$n_dmzjn,$l_dmzjn,$t_dmzjn);
				//腾讯动漫
				baiduSS($title,'ac.qq.com','TXDM','腾讯动漫',$n_txdm,$l_txdm,$t_txdm);
				echo '</div>';
			//结束
		}
	 }
	} 
}
?>