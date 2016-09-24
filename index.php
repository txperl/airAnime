<!DOCTYPE HTML> 
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>airAnime Online</title>

	<link href="css/base.min.css" rel="stylesheet">
	<link href="css/project.min.css" rel="stylesheet">
</head>
<body class="page-brand">

	<?php
	require "./functions/pages.php";
	pagepart('header');
	pagepart('menu');
	?>

	<!-- 主 -->
	<main class="content">
		<div class="content-header ui-content-header">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-lg-push-3 col-sm-10 col-sm-push-1">
						<h1 class="content-heading">airAnime Online</h1>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-push-3 col-sm-10 col-sm-push-1">
					<section class="content-inner margin-top-no">
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
								<form id='titleA' action='' method='get' onsubmit="return validation();">
										<div class="form-group form-group-label">
											<label class="floating-label" for="ui_floating_label_example">Title</label>
											<div id="search">
											<input class="form-control" id="title" type="text" name='title' autocomplete="off">
											</div>
											<br>
											搜索指令(点击添加): <code><a href="#" id="addsconly">only:\?/</a></code> <code><a href="#" id="addscexc">exc:\?/</a></code>
											<div style="text-align:right;">
											<a class="btn btn-brand waves-attach waves-light" href="#" id="btnS"> Search </a>
											</div>
											<div class="progress" style="display:none;" id="loading">
    										<div class="progress-bar-indeterminate"></div>
											</div>
										</div>
								</form>
								</div>
							</div>
						</div>
					</section>
					<div class="card">
							<div class="card-main">
								<div class="card-inner" id="srhauto" style="display:none;">
									<div id="search_auto"></div>
								</div>
							</div>
					</div>
<?php
require "./functions/mains.php";
if(is_array($_GET)&&count($_GET)>0){ 
	if(isset($_GET["title"])){
		$title=$_GET["title"];
		if ($title!='') {
		$ifrun=ifcode($title);
		if (substr_count($title,'only:')==1) {
			$title=getSubstr($title,'',' only:');
		}
		if (substr_count($title,'exc:')==1) {
			$title=getSubstr($title,'',' exc:');
		}
		echo '<h2 class="content-sub-heading">'.$title.'</h2>';
		// 抓取网页
		$webd=asrh($title,$ifrun);
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
			$r_letv=baiduS($webd[4],'/{"title":"(.*?)_全集在线观看-乐视网(.*?)","url":"(.*?)"}/',1,'www.le.com');// 1 参数暂时无用，下同
			$n_letv=$r_letv[2];
			$t_letv=$r_letv[0];
			$l_letv=$r_letv[1];
		}
		// iqiyi 结果
		if ($ifrun[5]=='true'){
			$r_iqiyi=baiduS($webd[5],'/{"title":"(.*?)-动漫动画-全集高清在线观看-爱奇艺(.*?)","url":"(.*?)"}/',1,'www.iqiyi.com');
			$n_iqiyi=$r_iqiyi[2];
			$t_iqiyi=$r_iqiyi[0];
			$l_iqiyi=$r_iqiyi[1];
		}
		// youku 结果
		if ($ifrun[6]=='true'){
			$r_youku=baiduS($webd[6],'/{"title":"(.*?)—日本—动漫—优酷网,视频高清在线观看(.*?)","url":"(.*?)"}/',1,'www.youku.com');
			$n_youku=$r_youku[2];
			$t_youku=$r_youku[0];
			$l_youku=$r_youku[1];
		}
		// 百度集合搜索 结果
		if ($ifrun[7]=='true'){
			$r_baiduall=baiduallS($webd[7]);
			$n_baiduall=$r_baiduall[2];
			$a_baiduall=$r_baiduall[1];
		}
		// 腾讯视频 结果
		if ($ifrun[8]=='true'){
			$r_tencenttv=baiduS($webd[8],'/{"title":"(.*?)-高清在线观看-腾讯视频(.*?)","url":"(.*?)"}/',1,'v.qq.com');
			$n_tencenttv=$r_tencenttv[2];
			$t_tencenttv=$r_tencenttv[0];
			$l_tencenttv=$r_tencenttv[1];
		}

		$statol=$n_bilibili+$n_dilidili+$n_baiduall+$n_letv+$n_iqiyi+$n_pptv+$n_fcdm+$n_youku+$n_tencenttv;
		// 简要 数量
		echo '<div class="tile-wrap"><div class="tile">';
    		echo '<div class="tile-inner">在已有数据源中找到 '.$statol.' 项匹配结果.</div>';
		echo '</div></div>';

	// 结果
		$nowout='';
		echo '<h2 class="content-sub-heading">Results</h2>';
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
	// 结束
		}
	} 
}
?>
		<div id='ifhomea'>
			<div class="card">
				<aside class="card-side card-side-img pull-left">
						<img alt="alt text" src="images/samples/portrait.jpg">
				</aside>
			<div class="card-main">
				<div class="card-inner">
					<p class="card-heading">Welcome!</p>
					<p class="margin-bottom-lg">参考 Menu-Docs 中的文档可以更熟悉它哦.<br>(´・ω・`)</p>
				</div>
			<div class="card-action">
				<div class="card-action-btn pull-left">
					<a class="btn btn-flat waves-attach" href="./start.html">开始</a>
					<a class="btn btn-flat waves-attach" href="./if.html">数据源</a>
					<a class="btn btn-flat waves-attach" href="./srhcode.html">搜索指令</a>
				</div>
			</div></div></div>
		</div>

	</div></div></div>

	<?php
	pagepart('footer');
	pagepart('ball');
	?>

	<!-- js -->
	<script src="js/jquery.min2.20.js"></script>
	<script src="js/base.min.js"></script>
	<script src="js/project.min.js"></script>
	<script type="text/javascript">
        $(function(){
            $("#btnS").click(function(){
                $("#titleA").submit();
            });
        });
        $(function(){
            $("#addsconly").click(function(){
                var obj=document.getElementById("title");
				var val=obj.value;
				if (val != '')
					obj.value=val+" only:\\/";
            });
        });
        $(function(){
            $("#addscexc").click(function(){
                var obj=document.getElementById("title");
				var val=obj.value;
				if (val != '')
					obj.value=val+" exc:\\/";
            });
        });
        $(function(){
            $("#sgtn").click(function(){
                var obj=document.getElementById("title");
				var val=obj.value;
				if (val != '')
					obj.value=val+" exc:\\i\\y/";
            });
        });
        var a = location.href;if(a=='http://airanime.applinzi.com/'){$('#ifhomea').show();}else $('#ifhomea').hide();
    </script>
    <script type="text/javascript">//表单提交时加载动画
    function getId(id) {
        return document.getElementById(id);
    }
    function validation() {
        getId("btnS").style.display="none";
        getId("loading").style.display="";
        return true;
    }
 	</script> 
 	<script>
    $(function(){
            $('#search input[name="title"]').keyup(function(){
                $.post( './functions/srhauto.php', { 'value' : $(this).val() },function(data){
                    if( document.getElementById("title").value == '' ) 
                        $('#srhauto').html('').css('display','none');
                    else
                        $('#srhauto').html(data).css('display','block');
            });
        });
    });
	</script>
</body>
</html><script> throw new Error(""); //SAE未实名特殊处理