<!DOCTYPE html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>索引 - airAnime</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />

    <link type="favicon" rel="shortcut icon" href="../favicon.ico">
    <link href="../assert/css/mdui.min.css" rel="stylesheet">
    <link href="../assert/css/n.css" rel="stylesheet">
</head>

<body class="mdui-appbar-with-toolbar mdui-theme-primary-indigo mdui-theme-accent-pink">

    <header mdui-headroom class="mdui-appbar mdui-appbar-fixed mdui-shadow-0 n-shadows">
        <div class="mdui-toolbar mdui-color-white">
            <a href="../" class="mdui-typo-headline lighter">airAnime v2</a>
            <div class="mdui-toolbar-spacer"></div>
            <a mdui-menu="{target: '#example-attr'}" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">menu</i></a>
            <ul class="mdui-menu" id="example-attr">
                <li class="mdui-menu-item">
                    <a href="../" class="mdui-ripple">首页</a>
                </li>
                <li class="mdui-menu-item">
                    <a href="./setting.php">设置</a>
                </li>
                <li class="mdui-menu-item">
                    <a href="./about.php">关于</a>
                </li>
                <li class="mdui-menu-item">
                    <a href="#" class="mdui-ripple">文档</a>
                </li>
                <li class="mdui-divider"></li>
                <li class="mdui-menu-item">
                    <a target="_blank" href="https://github.com/txperl/airAnime" class="mdui-ripple">GitHub</a>
                    <a target="_blank" href="https://yumoe.com/" class="mdui-ripple">TriiHsia</a>
                </li>
            </ul>
        </div>
    </header>

    <br><br><br>


    <div class="mdui-container main-card mdui-typo">
        <div class="mdui-card no-overflow mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">
            <div class="mdui-card-header">
                <div class="mdui-card-header-title">索引</div>
                <div class="mdui-card-header-subtitle">#哒哒哒～</div>
            </div>

            <div class="mdui-card-content">
                <select id="bgm_year" class="mdui-select">
                    <option value="2019" selected>2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                    <option value="2016">2016</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    <option value="2013">2013</option>
                    <option value="2012">2012</option>
                    <option value="2011">2011</option>
                    <option value="2010">2010</option>
                    <option value="2009">2009</option>
                    <option value="2008">2008</option>
                    <option value="2007">2007</option>
                    <option value="2006">2006</option>
                    <option value="2005">2005</option>
                    <option value="2004">2004</option>
                    <option value="2003">2003</option>
                    <option value="2002">2002</option>
                    <option value="2001">2001</option>
                    <option value="2000">2000</option>
                </select>
                <select id="bgm_season" class="mdui-select" style="margin-left: 10px;">
                    <option value="1">冬（1月）</option>
                    <option value="4" selected>春（4月）</option>
                    <option value="7">夏（7月）</option>
                    <option value="10">秋（10月）</option>
                </select>
                <a src="javascrip: getBgms();" class="mdui-btn mdui-color-theme-accent mdui-ripple" style="margin-left: 10px;">搜索</a>
            </div>
        </div>
    </div>

    <div class="mdui-container main-card mdui-typo">
        <div class="mdui-card no-overflow mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">
            <div class="mdui-card-header">
                <div class="mdui-card-header-title">QAQ</div>
                <div class="mdui-card-header-subtitle">#再来凑一块</div>
            </div>

            <div class="mdui-card-content">
                <p>airAnime 始终只会是个轻量工具，并不会拓展很多功能。</p>
                <p>在功能不变的前提下，个人认为 v2 版本相对于 v1 版本简化了很多操作逻辑，所以很易用，没什么可以更多说明的了。</p>
                <p>使用愉快。</p>
            </div>
        </div>
    </div>
</body>

<script src="../assert/js/mdui.min.js"></script>
<script src="../assert/js/jquery.min.js"></script>