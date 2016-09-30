<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>使用说明 -airAnime</title>

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
				<h1 class="content-heading">使用说明</h1>
			</div>
		</div>
		<div class="container">
			<section class="content-inner margin-top-no">
				<div class="row">
					<div class="col-lg-8 col-md-9">
						<div class="card margin-bottom-no">
							<div class="card-main">
								<div class="card-inner">
									<p>这是 airAnime Online 使用说明。</p>
									<p>在这里你可以更深入了解如何使用它，希望你能喜欢。</p>
									<p>请务必阅读数据源可用性。</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<h2 class="content-sub-heading">基础</h2>
				<div class="ui-card-wrap">
					<div class="row">
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">搜索</p>
										<p>在文本框中输入您所要搜索的番剧，按 Enter 或 点击SEARCH 即可开始搜索。</p>
										<p>这里有一点需要注明，因为是在线抓取网页数据并分析，暂未加入数据库，所以 每次搜索速度未知。</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">搜索指令</p>
										<p>搜索指令可以帮助您更快搜索到匹配结果，可以排出您不需要的数据源结果，带来更好的体验。</p>
										<p>具体请参考 搜索指令 页面。</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">Info Download</p>
										<p>将「|」放在两个或以上关键字之间，只要标题包含其中一个关键字，便会在搜寻结果中显示出来。(使用方法：番剧|BDRIP)</p>
										<p>将「 」(半形空格) 放在两个或以上关键字之间，标题包含所有关键字，便会在搜寻结果中显示出来。(使用方法：番剧 BDRIP)</p>
										<p>以上只适用于 动漫花园 数据源。</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-4 col-sm-6">

						</div>
					</div>
				</div>

				<h2 class="content-sub-heading">规则</h2>
				<div class="ui-card-wrap">
					<div class="row">
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">程序匹配&输出规则</p>
										<p>站内搜索: 输出结果第一页匹配项(不唯一)。</p>
										<p>集合搜索: 输出所有联想匹配项(唯一)。</p>
										<p>百度协助搜索: 输出搜索结果1或2页所有匹配项(将来用户可自定义)。</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">精度规则</p>
										<p>精度排序(推荐度): 站内搜索 ＞ 百度协助搜索 ≈ 集合搜索</p>
										<p>大型数据源番剧资源精度排序: 近几年正版版权番 ＞ 正常类目番 ＞＞ 强制下架番</p>
										<p>小型数据源番剧资源精度排序: 近几年正版版权番 ≈ 正常类目番 ≈ 强制下架番 ＞＞ 未收录或因正版版权问题下架番</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">搜索方式具体说明</p>
										<p>站内搜索: 抓取网站内提供的搜索信息，所以相对较准确。</p>
										<p>集合搜索: 抓取百度工具的集合番剧信息，不过匹配码只针对一些例子，所以时有时无。</p>
										<p>百度协助搜索: 在一些站内搜索速度慢或者不怎么推荐的数据源或者没办法直接抓取的数据源会使用这种方法，因为取百度搜索结果，并且用正则只匹配一种规则，所以匹配结果范围较大。</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<h2 class="content-sub-heading">P.s.</h2>
				<div class="ui-card-wrap">
					<div class="row">
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<aside class="card-side pull-left">
									<span class="card-heading"><i class="icon">info_outline</i></span>
								</aside>
								<div class="card-main">
									<div class="card-inner">
										<p class="card-heading">注意项</p>
										<p>请尽量参考此说明，可以解决一些疑问。</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-6">
							<div class="card">
								<div class="card-main">
									<div class="card-inner">
										<p>1.为提高搜索目中率，请尽量输入最精准的番剧名称。</p>
										<p>2.因使用的不是自已的数据库而是网站搜索功能及搜索引擎，所以结果有时会出现偏差或错误。因为匹配代码缘故，可能会引起样式漂移或者输出错误。</p>
										<p>3.若您发现搜索结果短期内有较大不同，可能是因为服务器网络导致。</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
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