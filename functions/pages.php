<?php
//在此修改网页通用部分
function pagepart($name,$style=0){
////////////////////////////////
	//头部
	if ($name=='header') {
		if ($style==0) {
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
	if ($style==1) {
print <<<EOT
	<header class="header header-transparent header-waterfall ui-header">
		<ul class="nav nav-list pull-left">
			<li>
				<a data-toggle="menu" href="#ui_menu">
					<span class="icon icon-lg">menu</span>
				</a>
			</li>
		</ul>
		<a class="header-logo margin-left-no" href="../">airAnime</a>
		</header>

EOT;
	}
	}

////////////////////////////////
	//菜单
	if ($name=='menu') {
		if ($style==0) {
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
						<a class="collapsed waves-attach" href="./pages/about.php">About</a>
					</li>
					<li>
						<a class="collapsed waves-attach" href="./pages/public.php">Cup of Coffee?</a>
					</li>

					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#ui_menu_extras">--Docs</a>
						<ul class="menu-collapse collapse" id="ui_menu_extras">
							<li>
								<a class="waves-attach" href="./pages/start.php">使用说明</a>
							</li>
							<li>
								<a class="waves-attach" href="./pages/if.php">数据源可用性</a>
							</li>
							<li>
								<a class="waves-attach" href="./pages/srhcode.php">搜索指令</a>
							</li>
						</ul>
					</li>
EOT;
		}
		if ($style==1) {
print <<<EOT
		<nav aria-hidden="true" class="menu" id="ui_menu" tabindex="-1">
		<div class="menu-scroll">
			<div class="menu-content">
				<a class="menu-logo" href="../">airAnime</a>
				<ul class="nav">
					<li>
						<a class="collapsed waves-attach" href="../">Home</a>
					</li>
					<li>
						<a class="collapsed waves-attach" href="../pages/about.php">About</a>
					</li>
					<li>
						<a class="collapsed waves-attach" href="../pages/public.php">Cup of Coffee?</a>
					</li>

					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#ui_menu_extras">--Docs</a>
						<ul class="menu-collapse collapse" id="ui_menu_extras">
							<li>
								<a class="waves-attach" href="../pages/start.php">使用说明</a>
							</li>
							<li>
								<a class="waves-attach" href="../pages/if.php">数据源可用性</a>
							</li>
							<li>
								<a class="waves-attach" href="../pages/srhcode.php">搜索指令</a>
							</li>
						</ul>
					</li>
EOT;
		}

print <<<EOT
					<li>
						<a class="collapsed waves-attach" data-toggle="collapse" href="#ui_menu_xinfan">--新番</a>
						<ul class="menu-collapse collapse" id="ui_menu_xinfan">
							<li><a class="waves-attach" href="https://res.animen.com.tw/NewsArea/NewsItemDetail?NewsId=23836&categoryId=300&tagName=none&keyword=%E6%96%B0%E7%95%AA&realCategoryId=1&subCategoryId=9" target="_blank">2018年01月冬季</a></li>
							<li><a class="waves-attach" href="https://res.animen.com.tw/NewsArea/NewsItemDetail?NewsId=22223&categoryId=300&tagName=none&keyword=%E6%96%B0%E7%95%AA&realCategoryId=1&subCategoryId=9" target="_blank">2017年10月秋季</a></li>
							<li><a class="waves-attach" href="https://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=20746&categoryId=600&tagName=%E6%96%B0%E7%95%AA%E5%88%97%E8%A1%A8&realCategoryId=1&subCategoryId=9" target="_blank">2017年07月夏季</a></li>
							<li><a class="waves-attach" href="https://www.animen.com.tw/NewsArea/NewsItemDetail?NewsId=19811&categoryId=600&tagName=%E6%96%B0%E7%95%AA%E5%88%97%E8%A1%A8&realCategoryId=1&subCategoryId=5" target="_blank">2017年04月春季</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		</nav>

EOT;
	}

////////////////////////////////
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

////////////////////////////////
	//悬浮球
	if ($name=='ball') {
print <<<EOT
	<div class="fbtn-container">
		<div class="fbtn-inner">
			<a class="fbtn fbtn-lg fbtn-brand-accent waves-attach waves-circle waves-light" data-toggle="dropdown"><span class="fbtn-text fbtn-text-left">Links</span><span class="fbtn-ori icon">apps</span><span class="fbtn-sub icon">close</span></a>
			<div class="fbtn-dropup">
				<a class="fbtn waves-attach waves-circle" href="https://github.com/txperl/airAnime" target="_blank"><span class="fbtn-text fbtn-text-left">Star me on GitHub</span><span class="icon">code</span></a>
				<a class="fbtn fbtn-brand waves-attach waves-circle waves-light" href="https://twitter.com/txperl" target="_blank"><span class="fbtn-text fbtn-text-left">Follow my Twitter</span><span class="icon">share</span></a>
				<a class="fbtn fbtn-green waves-attach waves-circle" href="http://yumoe.com/" target="_blank"><span class="fbtn-text fbtn-text-left">Blog</span><span class="icon">link</span></a>
			</div>
		</div>
		</div>

EOT;
	}
}
function ads(){
print <<<EOT
           	<div class="card" id="ads">
               	<div class="card-main">
                   	<div class="card-header">
                       	<div class="card-header-side pull-left">
                       	</div>
                       	<div class="card-inner">
                           	<span class="card-heading">我的项目推广</span>
                       	</div>
                   	</div>
                   	<div class="card-img">
                       	<img alt="alt text" src="http://7vil9u.com1.z0.glb.clouddn.com/20170725-134813@2x.png" style="width: 100%;">
                       	<p class="card-img-heading">CRH Shadowsocks</p>
                   	</div>
                   	<div class="card-inner">
                       	<p>
                       	全球10大顶级数据中心，随时为您准备300Mbps充裕带宽
                       	<br>因为是本人参与的项目，所以提供专门针对来自airAnime用户的特殊优惠码
                       	<br>使用本优惠码也算是对本站的一种支持，感谢
                       	</p>
                   	</div>
                   	<div class="card-action">
                       	<div class="card-action-btn pull-left">
                           	<a class="btn btn-flat waves-attach" href="javascript:$('#price').fadeIn(400);">@体验&优惠码</a>
                       	</div>
                   	</div>
               	</div>
           	</div>
			<div class="card" id="price" style="display: none;">
    			<div class="card-main">
        			<nav class="tab-nav tab-nav-brand margin-top-no">
            			<ul class="nav nav-justified">
                			<li class="active">
                    			<a class="waves-attach" data-toggle="tab" href="#brand1">体验套餐</a>
                			</li>
                			<li>
                    			<a class="waves-attach" data-toggle="tab" href="#brand2">标准订阅</a>
                			</li>
                			<li>
                    			<a class="waves-attach" data-toggle="tab" href="#brand3">中级订阅(推荐)</a>
                			</li>
                			<li>
                    			<a class="waves-attach" data-toggle="tab" href="#brand4">高级订阅</a>
                			</li>
            			</ul>
        			</nav>
        			<div class="card-inner">
            			<div class="tab-content">
                			<div class="tab-pane fade active in" id="brand1">
                    			<p>优惠码: <code>753BIGBA8X</code> 结账时输入</p>
                    			<ul>
                        			<li>价格:<code>3天</code> <code>免费</code></li>
                        			<li><code>5GB</code>使用流量</li>
                        			<li>5组后端服务器节点</li>
                        			<li>专用下载节点</li>
                        			<li><code>google,twitter,instagram,facebook,youtube等</code> 毫秒加载</li>
                        			<li><code>技术支持</code></li>
                    			</ul>
                    			<blockquote style="font-size: 16px;">P.s.当前因为你们都懂的政策原因，推荐以月付方式购买SS代理<br>P.s.该优惠码仅适用于 CRH 试用订阅<br>P.s.优惠码仅可选择一项并且使用一次</blockquote>
                    			<a target="_blank" href="http://airanime.applinzi.com/functions/ads.php/?code=free" class="btn btn-flat btn-brand">CRH 试用订阅</a>
                			</div>
                			<div class="tab-pane fade" id="brand2">
                    			<p>15%OFF月付优惠码: <code>4N21C9ESKU</code> 结账时输入</p>
                    			<ul>
                        			<li>价格:<code>1个月</code> <code>RMB 6.8</code></li>
                        			<li><code>20GB</code>使用流量</li>
                        			<li>8组后端服务器节点</li>
                        			<li>专用下载节点</li>
                        			<li><code>google,twitter,instagram,facebook,youtube等</code> 毫秒加载</li>
                        			<li><code>技术支持</code></li>
                    			</ul>
                    			<blockquote style="font-size: 16px;">P.s.当前因为你们都懂的政策原因，推荐以月付方式购买SS代理<br>P.s.该优惠码仅适用于 CRH 标准订阅 月付<br>P.s.优惠码仅可选择一项并且使用一次</blockquote>
                    			<a target="_blank" href="http://airanime.applinzi.com/functions/ads.php/?code=cj" class="btn btn-flat btn-brand">CRH 标准订阅</a>
                			</div>
                			<div class="tab-pane fade" id="brand3">
                    			<p>25%OFF月付优惠码: <code>CYDMSE931E</code> 结账时输入</p>
                    			<ul>
                        			<li>价格:<code>1个月</code> <code>RMB 9</code></li>
                        			<li><code>40GB</code>使用流量</li>
                        			<li>10组后端服务器节点</li>
                        			<li>专用下载节点</li>
                        			<li><code>google,twitter,instagram,facebook,youtube等</code> 毫秒加载</li>
                        			<li><code>技术支持</code></li>
                    			</ul>
                    			<blockquote style="font-size: 16px;">P.s.当前因为你们都懂的政策原因，推荐以月付方式购买SS代理<br>P.s.该优惠码仅适用于 CRH 中级订阅 月付<br>P.s.优惠码仅可选择一项并且使用一次</blockquote>
                    			<a target="_blank" href="http://airanime.applinzi.com/functions/ads.php/?code=zj" class="btn btn-flat btn-brand">CRH 中级订阅</a>
                			</div>
                			<div class="tab-pane fade" id="brand4">
                    			<p>30%OFF月付优惠码: <code>NF6N0XBCLU</code> 结账时输入</p>
                    			<ul>
                        			<li>价格:<code>1个月</code> <code>RMB 17.5</code></li>
                        			<li><code>100GB</code>使用流量</li>
                        			<li>10组后端服务器节点</li>
                        			<li>专用下载节点</li>
                        			<li><code>google,twitter,instagram,facebook,youtube等</code> 毫秒加载</li>
                        			<li><code>技术支持</code></li>
                    			</ul>
                    			<blockquote style="font-size: 16px;">P.s.当前因为你们都懂的政策原因，推荐以月付方式购买SS代理<br>P.s.该优惠码仅适用于 CRH 高级订阅 月付<br>P.s.优惠码仅可选择一项并且使用一次</blockquote>
                    			<a target="_blank" href="http://airanime.applinzi.com/functions/ads.php/?code=gj" class="btn btn-flat btn-brand">CRH 高级订阅</a>
                			</div>
            			</div>
        			</div>
    			</div>
			</div>
                        
EOT;
}
?>