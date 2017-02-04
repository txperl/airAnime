<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>数据源可用性 -airAnime</title>

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
					<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
						<h1 class="content-heading">数据源可用性</h1>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
					<section class="content-inner margin-top-no">
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
									<p>因为本程序是获取网页源代码后分析网页，所以如果网页代码更改，部分功能即会失效。</p>
									<p>你可以在此查看本程序的数据源是否处于可用状态。</p>
									<p>如果处于可用状态，网页仍异常，应该是用户本地网络或浏览器问题。</p>
									<hr>
									<p>TRUE:拥有针对性匹配代码，搜索匹配率高。</p>
									<p>uTRUE:拥有半针对性匹配代码，搜索匹配率高，但内容相关度未知。</p>
									<p>EXTRA:可有可无。</p>
									<p>?:因特殊原因(如目标服务器或百度收录问题)暂时无法确定稳定性。</p>
									<p>!:暂不可用。</p>
								</div>
							</div>
						</div>
						<h2 class="content-sub-heading">Here~</h2>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>数据源</th>
										<th>可用性</th>
										<th>方式</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Bilibili(哔哩哔哩)</td>
										<td>TRUE</td>
										<td>站内搜索</td>
									</tr>
									<tr>
										<td>Dilidili(嘀哩嘀哩)</td>
										<td>?TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>Fcdm(风车动漫)</td>
										<td>TRUE</td>
										<td>站内搜索</td>
									</tr>
									<tr>
										<td>PPTV(聚力)</td>
										<td>TRUE</td>
										<td>站内搜索</td>
									</tr>
									<tr>
										<td>Letv(乐视)</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>iQiyi(爱奇艺)</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>Youku(优酷)</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>TencentTV(腾讯视频)</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>WXDM(无限动漫)</td>
										<td>TRUE</td>
										<td>站内搜索</td>
									</tr>
									<tr>
										<td>BaiduAll(百度集合搜索)</td>
										<td>EXTRA</td>
										<td>百度</td>
									</tr>
									<tr>
										<td>MGJH(蜜柑计划)_下载源</td>
										<td>TRUE</td>
										<td>站内搜索(RSS)</td>
									</tr>
									<tr>
										<td>DMZJ(动漫之家)_漫画&小说源</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>BKMH(布卡漫画)_漫画源</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>DMW(动漫屋)_漫画源</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
									<tr>
										<td>TKMH(图库漫画)_漫画源</td>
										<td>TRUE</td>
										<td>站内搜索</td>
									</tr>
									<tr>
										<td>TXDM(腾讯动漫)_小说源</td>
										<td>TRUE</td>
										<td>百度协助搜索</td>
									</tr>
								</tbody>
							</table>
						</div>
	</main>

	<?php
	pagepart('footer');
	pagepart('ball');
	?>

	<!-- js -->
	<script src="js/jquery.min2.20.js"></script>
	<script src="js/base.min.js"></script>
	<script src="js/project.min.js"></script>
</body>
</html>