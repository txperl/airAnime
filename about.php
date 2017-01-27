<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>关于 -airAnime</title>

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
						<h1 class="content-heading">关于</h1>
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
									<p>airAnime是一款集合番剧搜索程序,借助于各网站的数据及各网站的搜索功能进行指定搜索,以减少搜索番剧的时间。</p>
									<hr>
									<p>前端基于 <a href="https://github.com/Daemonite/material" target="_blank">Material框架</a> ，后端PHP。</p>
									<p>这是一个开源程序，您可以到我的GitHub主页下载。</p>
									<p>请使用现代浏览器以达到最佳体验效果。</p>
									<p>第一次使用务必阅读Docs中各文档。</p>
									<p>背景图来自 <a href="http://www.pixiv.net/member.php?id=1565632" target="_blank">@Kantoku</a> 。
								</div>
							</div>
						</div>
						<h2 class="content-sub-heading">反馈</h2>
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
									<p>如有BUG欢迎反馈交流。</p>
									<p>我的博客: yumoe.com</p>
									<p>我的邮箱: txperl#gmail.com</p>
									<p>感谢。</p>
								</div>
							</div>
						</div>
						<h2 class="content-sub-heading">More info.</h2>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Function</th>
										<th>Version</th>
										<th>Release date</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>airAnime Online</td>
										<td>1.0 beta4</td>
										<td>2017/01/27</td>
									</tr>
									<tr>
										<td>搜索指令</td>
										<td>1.0</td>
										<td>2017/01/27</td>
									</tr>
									<tr>
										<td>网页样式</td>
										<td>1.1</td>
										<td>2017/01/27</td>
									</tr>
									<tr>
										<td>托管服务器</td>
										<td>SAE</td>
										<td>NONE</td>
									</tr>
								</tbody>
							</table>
						</div>
						<h2 class="content-sub-heading">更新日志</h2>
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
									<p>1.0 beta4 -新年快乐</p>
									<p>1.优化 百度协助搜索-漫画(精度提高约至92%)</p>
									<p>2.优化 InfoDownload(数据基于蜜柑计划)[beta]</p>
									<p>3.新增 小说源(动漫之家,腾讯动漫)</p>
									<p>4.新增 漫画源(动漫屋)</p>
									<p>5.新增 背景图与毛玻璃效果</p>
									<p>6.待定 以图搜番测试版[未开放]</p>
									<hr>
									<p>1.0 beta3 -假期愉快</p>
									<p>1.优化 指令搜索代码结构</p>
									<p>2.优化 百度协助搜索(精度提高约至90%)</p>
									<p>3.新增 image[未开放],type指令</p>
									<p>5.新增 漫画搜索(动漫之家,布卡漫画)</p>
									<p>6.新增 以图搜番测试版[未开放]</p>
									<hr>
									<p>1.0 beta2</p>
									<p>1.优化 内部代码结构</p>
									<p>2.优化 百度协助搜索规则(更加精准稳定)</p>
									<p>3.优化 页面载入速度</p>
									<p>4.新增 腾讯视频数据源</p>
									<p>5.开启 动漫花园下载数据源测试</p>
									<p>6.开启 短网址: <span style="font-weight:bold;">airs.im</span></p>
									<hr>
									<p>1.0 beta1</p>
									<p>All</p>
								</div>
							</div>
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