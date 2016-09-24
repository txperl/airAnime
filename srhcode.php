<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>搜索指令 -airAnime</title>

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
						<h1 class="content-heading">搜索指令</h1>
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
									<p>这里是airAnime拓展功能「搜索指令」的说明文档。</p>
									<p>搜索指令的存在，可以更加快速、精确地查询到您需要的番剧。</p>
									<p>如要使用，请仔细阅读。</p>
								</div>
							</div>
						</div>
						<h2 class="content-sub-heading">指定搜索源代码（一次搜索中只可使用其一）</h2>
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
									<p>1. <span style="font-weight:bold;">only:\?/</span> -只搜索指定数据源<br><br>例子：'散华礼弥 only:\b\d/'（只在 哔哩哔哩和嘀哩嘀哩 数据源中搜索散华礼弥番剧）</p>
									<hr>
									<p>2. <span style="font-weight:bold;">exc:\?/</span> -只搜索除指定的数据源<br><br>例子：'物语系列 exc:\i\y/'（除 爱奇艺和优酷 的其他数据源中搜索物语系列）</p>
									<hr>
									<p>此代码必须<span style="font-weight:bold;">小写</span> <span style="font-weight:bold;">位于标题后</span> ，并且用 <span style="font-weight:bold;">空格</span> 隔开。开始:'only:',结束:'/' 或 开始:'exc:',结束:'/'.</p>
									<p>通用形式：title code</p> 
									<hr>
									<p><span style="font-weight:bold;">数据源代码：</span><br>bilibili(哔哩哔哩): \b<br>dilidili(嘀哩嘀哩): \d<br>Fcdm(风车动漫): \f<br>PPTV(聚力): \p<br>Letv(乐视): \l<br>iQiyi(爱奇艺): \i<br>Youku(优酷): \y<br>TencentTV(腾讯视频): \t<br>BaiduAll(百度集合搜索): \bda</p>
									<hr>
									<p>哔哩哔哩数据源为必搜索源，所以以上搜索指令对其无效</p>
								</div>
							</div>
						</div>
						<h2 class="content-sub-heading">指令集</h2>
						<div class="table-responsive">
							<table class="table" title="A basic table">
								<thead>
									<tr>
										<th>Name</th>
										<th></th>
										<th>Example</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>only:\?/</td>
										<td>只搜索指定数据源</td>
										<td>散华礼弥 only:\b\d/</td>
									</tr>
									<tr>
										<td>exc:\?/</td>
										<td>只搜索除指定的数据源</td>
										<td>物语系列 exc:\bda\i\p\l\f\y/</td>
									</tr>
									<tr>
										<td>将来添加更多搜索指令</td>
										<td>coming soon</td>
										<td>coming soon</td>
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
</html><script> throw new Error(""); //SAE未实名特殊处理