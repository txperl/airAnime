<?php
//在此修改网页通用部分
function pagepart($name){
	//头部
	if ($name=='header') {
print <<<EOT
	<header class="header header-transparent header-waterfall ui-header">
		<ul class="nav nav-list pull-left">
			<li>
				<a data-toggle="menu" href="#ui_menu">
					<span class="icon icon-lg">menu</span>
				</a>
			</li>
		</ul>
		<a class="header-logo margin-left-no" href="./">airAnime</a>
		</header>

EOT;
	}
	//菜单
	if ($name=='menu') {
print <<<EOT
		<nav aria-hidden="true" class="menu" id="ui_menu" tabindex="-1">
		<div class="menu-scroll">
			<div class="menu-content">
				<a class="menu-logo" href="./">airAnime</a>
				<ul class="nav">
					<li>
						<a class="collapsed waves-attach" href="./">Home</a>
					</li>
					<li>
						<a class="collapsed waves-attach" href="./about.php">About</a>
					</li>
					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#ui_menu_extras">Docs</a>
						<ul class="menu-collapse collapse" id="ui_menu_extras">
							<li>
								<a class="waves-attach" href="./start.php">使用说明</a>
							</li>
							<li>
								<a class="waves-attach" href="./if.php">数据源可用性</a>
							</li>
							<li>
								<a class="waves-attach" href="./srhcode.php">搜索指令</a>
							</li>
							<li>
								<a class="waves-attach" href="https://trello.com/b/8s4PQwAN/" target="_blank">其他</a>
							</li>
						</ul>
					</li>
					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#ui_menu_xinfan">新番</a>
						<ul class="menu-collapse collapse" id="ui_menu_xinfan">
							<li><a class="waves-attach" href="http://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=15697&categoryId=600&tagName=%E6%96%B0%E7%95%AA%E5%88%97%E8%A1%A8&realCategoryId=1&subCategoryId=5" target="_blank">2016年10月秋季</a></li>
							<li><a class="waves-attach" href="http://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=15445&categoryId=600&tagName=%E6%96%B0%E7%95%AA%E5%88%97%E8%A1%A8&realCategoryId=1&subCategoryId=5" target="_blank">2016年07月夏季</a></li>
							<li><a class="waves-attach" href="http://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=14444&categoryId=600&tagName=%E6%96%B0%E7%95%AA%E5%88%97%E8%A1%A8&realCategoryId=1&subCategoryId=5" target="_blank">2016年04月春季</a></li>
							<li><a class="waves-attach" href="http://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=12914&categoryId=600&tagName=%E6%96%B0%E7%95%AA%E5%88%97%E8%A1%A8&realCategoryId=1&subCategoryId=5" target="_blank">2016年01月冬季</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		</nav>

EOT;
	}
	//尾
	if ($name=='footer') {
print <<<EOT
	<footer class="ui-footer">
		<div class="container">
			<p>Created by Trii Hsia with ❤</p>
		</div>
		</footer>

EOT;
	}
	//悬浮球
	if ($name=='ball') {
print <<<EOT
	<div class="fbtn-container">
		<div class="fbtn-inner">
			<a class="fbtn fbtn-lg fbtn-brand-accent waves-attach waves-circle waves-light" data-toggle="dropdown"><span class="fbtn-text fbtn-text-left">Links</span><span class="fbtn-ori icon">apps</span><span class="fbtn-sub icon">close</span></a>
			<div class="fbtn-dropup">
				<a class="fbtn waves-attach waves-circle" href="#" target="_blank"><span class="fbtn-text fbtn-text-left">Fork me on GitHub</span><span class="icon">code</span></a>
				<a class="fbtn fbtn-brand waves-attach waves-circle waves-light" href="https://twitter.com/txperl" target="_blank"><span class="fbtn-text fbtn-text-left">Twitter</span><span class="icon">share</span></a>
				<a class="fbtn fbtn-green waves-attach waves-circle" href="https://loli.am/" target="_blank"><span class="fbtn-text fbtn-text-left">Blog</span><span class="icon">link</span></a>
			</div>
		</div>
		</div>

EOT;
	}
}
?>