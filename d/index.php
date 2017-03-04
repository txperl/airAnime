<!DOCTYPE html>
<!--列表搜索功能基于开源的Lisi.js(http://www.listjs.com/).-->
<style>
.list {
  margin:0;
  padding:20px 0 0;
}
.list > li {
  display:block;
  background-color: #eee;
  padding:10px;
  box-shadow: inset 0 1px 0 #fff;
}
.ddbb{
  display: block;
  float: right;
  font-size: 10px;
}
</style>
<html>
<head>
  <meta charset="UTF-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
  <title>InfoDownload -airAnime</title>

  <link href="../css/base.min.css" rel="stylesheet">
  <link href="../css/project.min.css" rel="stylesheet">
</head>
<body class="page-brand">

  <?php
  require "../functions/pages.php";
  ?>

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
    <nav aria-hidden="true" class="menu" id="ui_menu" tabindex="-1">
    <div class="menu-scroll">
      <div class="menu-content">
        <a class="menu-logo" href="../">airAnime</a>
        <ul class="nav">
          <li>
            <a class="collapsed waves-attach" href="../">Home</a>
          </li>
          <li>
            <a class="collapsed waves-attach" href="../about.php">About</a>
          </li>
          <li>
            <a class="collapsed waves-attach" data-toggle="collapse" href="#ui_menu_extras">Docs</a>
            <ul class="menu-collapse collapse" id="ui_menu_extras">
              <li>
                <a class="waves-attach" href="../start.php">使用说明</a>
              </li>
              <li>
                <a class="waves-attach" href="../if.php">数据源可用性</a>
              </li>
              <li>
                <a class="waves-attach" href="../srhcode.php">搜索指令</a>
              </li>
              <li>
                <a class="waves-attach" href="https://trello.com/b/8s4PQwAN/" target="_blank">其他</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    </nav>
  <!-- 主 -->
  <main class="content">
    <div class="content-header ui-content-header">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
            <h1 class="content-heading">InfoDownload</h1>
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
                  <p>这是InfoDownload页面，</p>
                  <p>此页面处于测试阶段，数据基于蜜柑计划。</p>
                  <p>若结果为空，多数情况是获取数据超时，请刷新重试。</p>
                </div>
              </div>
            </div>
  <?php
  $title = $_SERVER['QUERY_STRING'];
    echo '<h2 class="content-sub-heading">'.urldecode($title).'</h2>';
  ?>

<div id="users">
  <div class="form-group form-group-label">
    <?php
    require "../functions/mains.php";
    $title = $_SERVER['QUERY_STRING'];
    $rst=MGJH($title);
    echo '<label class="floating-label">列表检索('.$rst[4].')</label>'
    ?>
    <input id="title" type="text" name='title' autocomplete="off" class="form-control" placeholder="Search" />
  </div>

<ul class="list">
<?php
  echo '<div class="card"><div class="card-main"><div class="card-inner">';
    echo '<span class="label">建议检索(需要回车):</span> ';
  for ($i=0; $i < count($rst[3])-1; $i++) { 
    echo ' <a style="text-decoration:none;" href="#" id="ori'.$i.'" name="'.$rst[3][$i+1].'"><span class="label">'.$rst[3][$i+1].'</span></a> ';
    $scr=$scr.'$(function(){$("#ori'.$i.'").click(function(){var text=document.getElementById("ori'.$i.'").name;document.getElementById("title").value=text;document.getElementById("title").focus();});});';
  }
  echo '</div></div></div>';

  for ($i=0; $i < $rst[4]; $i++) { 
    echo '<a target="_blank" href="'.$rst[1][$i+1].'"><li class="in">'.$rst[0][$i+1].'<div class="ddbb">'.$rst[2][$i+1].'</div></li></a><hr>';
  }
  //<a href="{dmhy_地址}"><li class="in">{dmhy_标题}<div class="ddbb">{dmhy_info}</div></li></a> $rst[2][$i+1]
?>
</ul>

</div>
  </main>

  <?php
  pagepart('footer');
  pagepart('ball');
  ?>

  <!-- js -->
  <script src="../js/jquery.min2.20.js"></script>
  <script src="../js/base.min.js"></script>
  <script src="../js/project.min.js"></script>
  <script src="list.js"></script>
  <script>
  var options = {
  valueNames: [ 'in', 'ddbb' ]
  };
  var userList = new List('users', options);
  </script>
  <?php echo '<script>'.$scr.'</script>'; ?>
</body>
</html>