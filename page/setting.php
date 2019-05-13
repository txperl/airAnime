<?php
require_once '../function/class/search.class.php';
$isThisOff = false;

if (@$_GET["userid"] && !@$_GET["code"]) {
  $userid = $_GET["userid"];
  $ntime = time();
  setcookie("user_id", $userid, time() + 24 * 3600 * 7, "/");
  setcookie("user_ctime", $ntime, time() + 24 * 3600 * 7, "/");
  setcookie("noUserbgm", "false", time() + 24 * 3600 * 7, "/");

  $search = new allSearch();
  $search->dbStart();
  $uesrData = $search->__doSearch_Userbgm($userid);
  if (!$uesrData) {
    $data = file_get_contents('http://api.tls.moe/?app=bangumi&key=watching&name=' . $userid);
    $oriData = $search->__doInsert_Userbgm($userid, $ntime, $data);
  }
  $search->dbEnd();
} else {
  if (@$_COOKIE["user_id"]) {
    $userid = $_COOKIE["user_id"];
  } else {
    $userid = 'User ID';
  }
}

if (@$_GET["code"] && @$_COOKIE["user_id"]) {
  if ($_GET["code"] == 'off') {
    setCookie("user_id", "", time() - 60, "/");
    setCookie("user_ctime", "", time() - 60, "/");
    $isThisOff = true;
  }
}
?>
<!DOCTYPE html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>设置 - airAnime</title>
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
          <a href="#">设置</a>
        </li>
        <li class="mdui-menu-item">
          <a href="./about.php">关于</a>
        </li>
        <li class="mdui-menu-item">
          <a href="./doc.php" class="mdui-ripple">文档</a>
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

      <div class="mdui-card-content">
        <div>
          <h4>关联您的 Bangumi.tv 账号<br><small>#关联后，可将您的追番列表同步至 airAnime 主页</small><br><small>关联有效周期为 7 天，若连续 7 天未访问 airAnime，则需要重新关联。</small><br><small>关联后再进入 <b>设置</b> 界面，可点击 <b>OFF</b> 按钮取消关联。</small><br><small>请输入您的 User ID ，如 <code>https://bangumi.tv/user/txperl</code> 中 <code>/user/</code> 后的字符（<code>txperl</code>）即为您的用户ID 。</small><br><small>或在您的个人主页查看用户名后 <code>@</code> 后的字符（无需密码）。</small><br><small>默认每天定点更新您的追番数据，但您可以点击主页的<code>我的追番</code>标题来强制刷新。</small><br><small>若您已关闭我的追番，在此设置后即可重新开启。</small></h4>
        </div>
        <div class="mdui-textfield">
          <input id="stg_done" class="mdui-textfield-input" type="text" placeholder="<?php echo $userid; ?>" />
        </div>
        <br>
        <a href="javascript:stgDone();" class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple">Done</a>
        <?php
        if (@$_COOKIE["user_id"] && $isThisOff == false) {
          echo ' <a href="javascript:stgOff();" class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple">Off</a>';
        }
        ?>
      </div>

    </div>

  </div>

  </div>

</body>

<script src="../assert/js/mdui.min.js"></script>
<script src="../assert/js/jquery.min.js"></script>
<script>
  function stgDone() {
    var userid = $('#stg_done').val();
    if (userid != '') {
      window.location.href = "?userid=" + userid;
    }
  }

  function stgOff() {
    window.location.href = "?code=off";
  }
</script>