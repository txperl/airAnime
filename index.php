<?php
require_once 'function/newbgm.php';
require_once 'config.php';
$newbgm=getTodays();
$isDisplay_userbgm='';
if (@$_COOKIE["noUserbgm"]=='true') {
  if (@$_COOKIE["user_id"] || @$_COOKIE["user_ctime"]) {
    setCookie("user_id","",time()-60,"/");
    setCookie("user_ctime","",time()-60,"/");
  }
  $isDisplay_userbgm='style="display:none;"';
}
?>
<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"> 
    <title>airAnime v2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />

    <link type="favicon" rel="shortcut icon" href="favicon.ico">
    <link href="assert/css/mdui.min.css" rel="stylesheet">
    <link href="assert/css/n.css" rel="stylesheet">
</head>
<body class="mdui-appbar-with-toolbar mdui-theme-primary-indigo mdui-theme-accent-pink">

<header mdui-headroom class="mdui-appbar mdui-appbar-fixed mdui-shadow-0 n-shadows">
  <div class="mdui-toolbar mdui-color-white">
    <a href="./" class="mdui-typo-headline lighter">airAnime v2</a>
    <div class="mdui-toolbar-spacer"></div>
    <a mdui-menu="{target: '#example-attr'}" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">menu</i></a>
      <ul class="mdui-menu" id="example-attr">
        <li class="mdui-menu-item">
          <a href="./" class="mdui-ripple">首页</a>
        </li>
        <li class="mdui-menu-item">
          <a href="./page/setting.php">设置</a>
        </li>
        <li class="mdui-menu-item">
          <a href="./page/about.php">关于</a>
        </li>
        <li class="mdui-menu-item">
          <a href="./page/doc.php" class="mdui-ripple">文档</a>
        </li>
        <li class="mdui-divider"></li>
        <li class="mdui-menu-item">
          <a target="_blank" href="https://github.com/txperl/airAnime" class="mdui-ripple">GitHub</a>
          <a target="_blank" href="https://yumoe.com/" class="mdui-ripple">TriiHsia</a>
        </li>
      </ul>
  </div>
</header>
  <div class="searchbar mdui-toolbar mdui-color-white mdui-col-xs-12 searchbar_active" style="margin-bottom: 0;">
    <select id="typeName" class="mdui-select" style="margin: 0 12px 0 16px;" onchange='typeChange(this[selectedIndex].value);'>
      <option value="a">Anime</option>
      <option value="c">Comic</option>
      <option value="n">Noval</option>
      <option value="o" disabled>-</option>
      <option value="i">Image</option>
    </select>
    <input id="keytitle" class="mdui-textfield-input" type="search" autocomplete="off" placeholder="Type something here~"/>
    <button id="btnUP" class="mdui-btn mdui-ripple" style="min-width: 56px;display: none;">UP</button>
    <button id="btnS" class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">search</i></button>
  </div>
  <div class="mdui-col-xs-12" style="margin-bottom: 40px;">
    <ul id="Sl" class="h" style="margin: 0;">
          <div id="rst_sact"></div>
    </ul>
  </div>

<br><br><br>

<!-- 番剧信息 -->
<div id="bgminfo_card" class="mdui-container main-card" style="display:none;">
  <div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">

  <div class="mdui-card-header">
    <div class="mdui-card-header-title">番剧信息</div>
    <div id="des_bgminfo_card" class="mdui-card-header-subtitle"></div>
  </div>

  </div>
</div>
<!-- end -->

<!-- 以图搜番 -->
<div id="rst_info_card" class="mdui-container main-card" style="display: none;">
  <div style="padding: 0;" class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">
    <div class="mdui-row" id="rst_info"></div>
  </div>
</div>
<!-- end -->

<!-- 数据库资源 -->
<div id="rst_res_card" class="mdui-container main-card" style="display: none;">
  <div class="mdui-card no-overflow mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">

  <div class="mdui-card-header">
    <div class="mdui-card-header-title">Resources</div>
    <div class="mdui-card-header-subtitle">#数据库中的可能结果</div>
  </div>
  
  <div class="mdui-card-content">
    <div id="rst_res"></div>
  </div>

  </div>

</div>
<!-- end -->

<!-- BT 搜索 -->
<div id="rst_bt_card" class="mdui-container main-card" style="display: none;">
  <div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">

    <div class="mdui-card-header">
      <div class="mdui-card-header-title">Btrst</div>
      <div class="mdui-card-header-subtitle">#下载源搜索结果</div>
    </div>

    <div class="mdui-row">
      <div id="rst_bt" class="mdui-panel mdui-panel-gapless" mdui-panel></div>
    </div>

  </div>
</div>
<!-- end -->

<!-- 搜索结果 -->
<div id="rst_s_card" class="mdui-container main-card" style="display: none;">
  <div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">

    <div class="mdui-card-header">
      <div class="mdui-card-header-title">Results</div>
      <div class="mdui-card-header-subtitle">#在线搜索结果</div>
    </div>
  
    <div class="mdui-row">
      <div id="rst_s" class="mdui-panel mdui-panel-gapless" mdui-panel></div>
    </div>

  </div>
</div>
<!-- end -->

<!-- 追番列表-->
<div id="userbgm_card" class="mdui-container main-card" <?php echo $isDisplay_userbgm; ?>>
  <div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">

  <div class="mdui-card-header">
    <div class="mdui-card-header-title"><a href="javascript:getUserbgm('refresh');" style="color:#000;text-decoration:none;">我的追番</a><a href="javascript:noUserbgm();" style="float:right;color:#a5a5a5;text-decoration:none;">x</a></div>
    <div class="mdui-card-header-subtitle"><span id="des_userbgm_card">#追番列表已经开启，可至 <a href="./page/setting.php" style="color:#333;">右上角-设置</a> 关联您的 Bangumi.tv 账号以同步追番表</span></div>
  </div>
  
  <div class="mdui-row">
    <div id="userbgm_pro" class="mdui-progress" style="display:none;"><div class="mdui-progress-indeterminate"></div></div>
    <div id="userbgm_show"></div>
  </div>

  </div>
</div>
<!-- end -->

<!-- 今日番剧 -->
<div id="newbgm_card" class="mdui-container main-card">
  <div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card">

  <div class="mdui-card-header">
    <div class="mdui-card-header-title">今日番剧</div>
    <div class="mdui-card-header-subtitle"><?php echo $newbgm[0] ?></div>
  </div>
  
  <div class="mdui-row">
    <?php
      for ($i=0; $i < count($newbgm[1]); $i++) { 
        echo $newbgm[1][$i];
      }
    ?>
  </div>

  </div>
</div>
<!-- end -->

<br><br><br>

    <!-- 图片上传表单 -->
    <div id='upimage' style="display:none;"> 
      <form enctype="multipart/form-data" method="post" action="https://sm.ms/api/upload" id="upform">
        <input type="file" name="smfile" id="btnFile">
        <input type="submit" id='btnUUP' value="Upload">
      </form>
    </div>
    <!-- end -->

<!-- 信息框 -->
<div class="mdui-dialog" id="airDialog"></div>
<!-- end -->

</body>

<script src="assert/js/mdui.min.js"></script>
<script src="assert/js/jquery.min.js"></script>
<script src="assert/js/jquery.lazyload.min.js"></script>
<script src="assert/js/jquery.form.js"></script>
<script src="assert/js/jquery.cookie.js"></script>
<script src="assert/js/embed.js"></script>

<script type="text/javascript">
  $(document).ready(function (){
    getUserbgm('none');
  });

  $("#btnS").click(function(){
    if ($('#keytitle').val()!='' && $('#keytitle').val()!='loading...') {
      var keytitle=document.getElementById("keytitle").value;
      var type=document.getElementById("typeName").value;
      var res_is="<?php echo $GLOBALS['res_is']; ?>";
      var bt_is="<?php echo $GLOBALS['bt_is']; ?>";
      $('#Sl').addClass("h");
      $('#rst_sact').html('');
      $('#rst_res_card').css('display','none');
      $('#rst_s_card').css('display','none');
      $('#userbgm_card').css('display','none');
      $('#newbgm_card').css('display','none');
      $('#bgminfo_card').css('display','none');
      $('#rst_info_card').css('display','none');

        // Anime
        if (type=='a') {
          if (keytitle!='') {
            if (res_is=='on') {
              $('#rst_res_card').css('display','');
              $('#rst_res').html('loading...');
            } else {
              $('#rst_res_card').css('display','none');
            }
            if (bt_is=='on') {
              $('#rst_bt_card').css('display','');
              $('#rst_bt').html('<div class="mdui-panel-item"><div class="mdui-panel-item-header">loading...</div><div class="mdui-panel-item-body"></div></div>');
            } else {
              $('#rst_bt_card').css('display','none');
            }
            $('#rst_s_card').css('display','');
            $('#btnUP').css('display','none');
            $('#rst_s').html('<div class="mdui-panel-item"><div class="mdui-panel-item-header">loading...</div><div class="mdui-panel-item-body"></div></div>');
            $('#bgminfo_card').html('<div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card"><div class="mdui-card-header"><div class="mdui-card-header-title">番剧信息</div><div id="des_bgminfo_card" class="mdui-card-header-subtitle"></div></div></div>');
            $('#des_bgminfo_card').html('(ฅ´ω`ฅ) 想知道 <a style="color: #333;" href="javascript:searchBmgInfo(\'a\',\''+keytitle+'\');">'+keytitle+'</a> 的番剧信息嘛？');
            $('#bgminfo_card').css('display','');
            $.post( './function/sonline.php', { 'kt' : keytitle },function(data){
              if (data) {
                $('#rst_s').html(data);
              } else {
                $('#rst_s').html('<div class="mdui-panel-item"><div class="mdui-panel-item-header">error</div><div class="mdui-panel-item-body"></div></div>');
              }
            });
            if (bt_is=='on') {
              $.post( './function/bts.php', { 'kt' : keytitle },function(data){
                if (data) {
                  $('#rst_bt').html(data);
                } else {
                  $('#rst_bt').html('抱歉，没找到这部番呢...');
                }
              });
            }
            if (res_is=='on') {
              $.post( './function/resources.php', { 'kt' : keytitle },function(data){
                if (data) {
                  $('#rst_res').html(data);
                } else {
                  $('#rst_res').html('抱歉，没找到这部番呢...');
                }
              });
            }
          }
        }

        // Comic
        if (type=='c') {
          if (keytitle!='') {
            $('#rst_res_card').css('display','none');
            $('#rst_s_card').css('display','');
            $('#btnUP').css('display','none');
            $('#rst_s').html('<div class="mdui-panel-item"><div class="mdui-panel-item-header">loading...</div><div class="mdui-panel-item-body"></div></div>');
            $.post( './function/conline.php', { 'kt' : keytitle },function(data){
                if (data) {
                  $('#rst_s').html(data);
                } else {
                  $('#rst_s').html('<div class="mdui-panel-item"><div class="mdui-panel-item-header">error</div><div class="mdui-panel-item-body"></div></div>');
                }
            });
          }
        }

        // Image
        if (type=='i') {
          $('#btnUP').css('display','none');
          $('#keytitle').val('loading...');
          $.post( './function/picsearch.php', { 'picurl' : keytitle },function(data){
              var picData=JSON.parse(data);
              $('#keytitle').val('');
              $('#rst_info').html('<div class="mdui-col-xs-8 mdui-col-md-10" style="padding-right:0;"><div class="bgm-item" data-title="'+picData.anime+'" style="background-color:#D4C7DE;width: 100%;" plan="black"><img style="height:420px;" src="'+keytitle+'" class=""><h3 style="background:linear-gradient(rgba(233,227,238,0),rgba(233,227,238,.6),rgba(233,227,238,.8));">'+picData.anime+'</h3><b>PicSearch</b></div></div><div class="mdui-col-xs-4 mdui-col-md-2" style="padding:0;"><div class="mdui-list bgm-info-list"><a href="./?kt='+picData.anime+'&type=a" class="mdui-list-item mdui-ripple">Search</a><a target="_blank" href="https://trace.moe/?url='+picData.picurl+'" class="mdui-list-item mdui-ripple">Trace.moe</a></div></div>');
              $('#rst_info_card').css('display','block');
          });
        }
    }
  });

  // 获取番剧信息
  function searchBmgInfo(type,keytitle){
    if (type=='a') { type=2; }
    $('#des_bgminfo_card').html('少女祈愿中...');
      $.post( './function/bgminfo.php', { 'type' : type, 'keytitle' : keytitle },function(data){
          if (data!='') {
            var infoData=JSON.parse(data);
            $('#bgminfo_card').html('<div style="padding: 0;" class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card"><div class="mdui-row"><div class="mdui-col-xs-12"><div class="bgm-item" data-title="'+infoData.name_cn+'" style="background-color:#D4C7DE;width: 100%;" plan="black"><a href="'+infoData.url+'" target="_blank"><img src="'+infoData.images.large+'"><h3 style="background:linear-gradient(rgba(233,227,238,0),rgba(233,227,238,.6),rgba(233,227,238,.8));">'+infoData.summary+'</h3><b>'+infoData.name_cn+'</b></a></div></div></div></div>');
          } else {
            $('#bgminfo_card').html('<div class="mdui-card mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1 sites-card"><div class="mdui-card-header"><div class="mdui-card-header-title">番剧信息</div><div id="des_bgminfo_card" class="mdui-card-header-subtitle">抱歉没有找到唉...</div></div></div>');
          }
      });
  }

  // 获取追番列表
  function getUserbgm(type){
    var if_userbgm='<?php echo $_COOKIE["user_id"]; ?>';
    var if_run=$('#userbgm_pro').css("display");
    if (if_userbgm!='' && if_run=='none') {
      $('#userbgm_pro').css('display','block');
      $("#des_userbgm_card").text('#Bangumi');
      $.post( './function/userbgm.php', { 'type' : type },function(data){
        if (data) {
          var oriData=JSON.parse(data);
          var userbmgData=oriData[0]['data'];
          var f='';
          for (let i = 0; i < userbmgData.length; i++) {
            f=f+'<div class="bgm-item" data-title="'+userbmgData[i]['name']+'" style="background-color:#D4C7DE;width:20%;" plan="black"><a href="javascript:newBgmS(\''+userbmgData[i]['subject']['name_cn']+'\');"><img src="'+userbmgData[i]['subject']['images']['large']+'" class=""><h3 style="background:linear-gradient(rgba(233,227,238,0),rgba(233,227,238,.6),rgba(233,227,238,.8));">'+userbmgData[i]['subject']['name_cn']+'</h3></a></div>';;
          }
          $('#userbgm_pro').css('display','none');
          $('#userbgm_show').html(f);
        } else {
          $('#userbgm_show').html('抱歉，出错了呢...');
        }
      });
    }
  }

  //下拉框选择改变事件
  function typeChange(values) {
    if (values == "a") {
      $('#keytitle').attr('placeholder','Type your anime\'s name here~');
      $('#btnUP').css('display','none');
    }
    if (values == "c") {
      $('#keytitle').attr('placeholder','Type your comic\'s name here~');
      $('#btnUP').css('display','none');
    }
    if (values == "n") {
      $('#keytitle').attr('placeholder','Nothing here...');
      $('#btnUP').css('display','none');
    }
    if (values == "i") {
      $('#keytitle').attr('placeholder','Type your image\'s link here~');
      $('#btnUP').css('display','block');
    }
  }

  $('#keytitle').bind('keypress', function(event) {
    if ($('#keytitle').val()=='') {
      $('#Sl').addClass("h");
    } 
  });

  $('#keytitle').keyup(function(event){
    if (event.keyCode=='13') {              
      event.preventDefault();   
      //回车执行
      $("#btnS").click();
    }

    if (event.keyCode!='13') {
      var keytitle=document.getElementById("keytitle").value;
      if (keytitle=='') {
        $('#Sl').addClass("h");
      } else {
        $.post( './function/sact.php', { 'kt' : keytitle },function(data){
          if (data) {
            $('#Sl').removeClass("h");
            $('#rst_sact').html(data);
          } else {
            $('#Sl').addClass("h");
          }
        });
      }
    }
  });

  $(document).click(function (e){ 
    var v_id=e.target.id;  
    if (v_id=='AASS' || v_id=='keytitle') {
      if ($('#keytitle').val()=='') {
        $('#Sl').addClass("h");
      } else {
        $('#Sl').removeClass("h");
      }
    } else {
      $('#Sl').addClass("h");
    }
  });

  $("#btnUP").click(function(){
    $('#btnFile').click();
  });

  $("#btnFile").change(function(){
    if($(this).val()){
      $('#btnUUP').click();
    }
  });

  function addInputKT(keytitle){
    document.getElementById("keytitle").value=keytitle;
  }

  function newBgmS(keytitle){
    $("#keytitle").val(keytitle);
    document.getElementById('typeName').value='a';
    $("#btnS").click();
    window.scrollTo(0,0);
  }

  function noUserbgm(){
    $.cookie('noUserbgm', 'true', { expires: 30, path: '/' });
    mdui.alert('我的追番已关闭。<br>您可以在 右上角-设置 中关联后重新开启。');
    $('#userbgm_card').css('display','none');
  }

  function AGEgetBaiduYunUrl(url){
    var bt_is="<?php echo $GLOBALS['bt_is']; ?>";
    if (bt_is=='on') {
      $.post( './function/small/AGEgetPanUrl.php', { 'url' : url },function(data){
          if (data) {
            var panData=JSON.parse(data);
            $('#airDialog').html('<div class="mdui-dialog-title">'+panData.title+'</div><div class="mdui-dialog-content">来自 AGEFans 的百度云盘<br>链接: <a style="color:#FD5B78;" href="'+panData.link+'" target="_blank">'+panData.link+'</a><br>提取码：'+panData.psw+'</div><div class="mdui-dialog-actions"><button class="mdui-btn mdui-ripple" mdui-dialog-close>知道了</button></div>');
            var inst = new mdui.Dialog('#airDialog',{modal: true});
            inst.open();
          } else {
            $('#airDialog').html('抱歉，没找到这部番呢...');
          }
      });
    }
  }

  // '/?kt={...}&type={...}'
  var sotitle='<?php echo @$_GET["kt"]; ?>';
  var type='<?php echo @$_GET["type"]; ?>';
  if (type) {
    if (type!='a' && type!='i' && type!='c' && type!='n') {type='a';}
    document.getElementById('typeName').value=type;
  }
  if (sotitle) {
    $('#keytitle').val(sotitle);
    $("#btnS").click();
  }
</script>