<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>airAnime Online</title>

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
					<div class="col-lg-6 col-lg-push-3 col-sm-10 col-sm-push-1">
						<h1 class="content-heading">airAnime Online</h1>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-lg-push-3 col-sm-10 col-sm-push-1">
					<section class="content-inner margin-top-no">
						<div class="card">
							<div class="card-main">
								<div class="card-inner">
								<form action='run.php' method='' onsubmit="return validation();">
										<div class="form-group form-group-label">
											<label class="floating-label" for="ui_floating_label_example">Title</label>
											<div id="search">
											<input class="form-control" id="title" type="text" name='title' autocomplete="off">
											</div>
											<br>
											搜索指令(点击添加): <code><a href="#" id="addsctp">!image</a></code> <code><a href="#" id="addscmh">&comic</a></code> <code><a href="#" id="addscxs">&novel</a></code>
											<div style="text-align:right;">
											<a class="btn btn-flat waves-attach" id="btnUP">Upload</a> <a class="btn btn-brand waves-attach waves-light" href="#" id="btnS"> Search </a> 
											</div>
											<div class="progress" style="display:none;" id="loading">
    										<div class="progress-bar-indeterminate"></div>
											</div>
										</div>
								</form>
								</div>
							</div>
						</div>
					</section>

                    <!-- 自动联想结果 -->
					<div class="card">
							<div class="card-main">
								<div class="card-inner" id="srhauto" style="display:none;">
									<div id="search_auto"></div>
								</div>
							</div>
					</div>

        <!-- 以图搜番说明 -->
        <div id='picstip' style="display:none;">
            <div class="card">
            <div class="card-main">
                <div class="card-inner">
                    <p class="card-heading">「PicSearch」以图搜番说明</p>
                    <p class="margin-bottom-lg">1.只可搜索出自日本动画(动漫)中的图片<br>2.图像大小 <= 1MB<br>3.若搜索本地图片，请点击UPLOAD上传图像再搜索<br>更多搜索指令使用说明请参考 <a href="./pages/srhcode.php">搜索指令</a>&<a href="./pages/start.php">使用说明</a> 页面</p>
                </div>
            </div></div>
        </div>

			<div id="rst_show"></div>

        <!-- 图片上传表单 -->
        <div id='upimage' style="display:none;"> 
            <form enctype="multipart/form-data" method="post" action="http://up.imgapi.com/" id="upform">
            <input name="Token" id="token" value="{your_token}" type="hidden"> <!-- 贴图库 http://www.tietuku.com/ 获取 -->
            <input type="hidden" name="from" value="file">
            <input type="hidden" name="httptype" value="1">
            <input type="file" name="file" id="file" style="display:none">
            <input type="submit" id='btnU' value="上传" class="btn">
            </form>
        </div>

        <!-- 首页公示 -->
        <div id='ifhomea'>
            <div class="card">
                <aside class="card-side card-side-img pull-left">
                        <img alt="alt text" src="images/samples/portrait.jpg">
                </aside>
            <div class="card-main">
                <div class="card-inner">
                    <p class="card-heading">Welcome</p>
                    <p class="margin-bottom-lg">(´・ω・`) airAnimeOnline v1 RC1.2,<br><span style="font-weight:bold;">呼呼呼</span>~学习&工作加油.</p>
                </div>
            <div class="card-action">
                <div class="card-action-btn pull-left">
                    <a class="btn btn-flat waves-attach" href="./pages/start.php">开始</a>
                    <a class="btn btn-flat waves-attach" href="./pages/if.php">数据源</a>
                    <a class="btn btn-flat waves-attach" href="./pages/srhcode.php">搜索指令</a>
                </div>
            </div></div></div>
        </div>

	</div></div></div>

	<?php
	pagepart('footer');
	pagepart('ball');
	?>

	<!-- js -->
	<script src="js/jquery.min2.20.js"></script>
	<script src="js/base.min.js"></script>
	<script src="js/project.min.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/embed.js"></script>
	<script type="text/javascript">
        //UPLOAD被点击
        $(function(){
            $("#btnUP").click(function(){
                $('#file').click();
                getId("picstip").style.display="";
                getId("srhauto").style.display="none";
            });
        });
        //选择文件后
        $("#file").change(function(){
            if($(this).val()){
                 $('#btnU').click();
            }
        });
        //快捷插入
        $(function(){
            $("#addsctp").click(function(){
                var obj=document.getElementById("title");
                var val=obj.value;
                obj.value="!image:;"+val;
                $('#title').focus();
                getId("picstip").style.display="";
                getId("srhauto").style.display="none";
            });
        });
        $(function(){
            $("#addscxz").click(function(){
                var obj=document.getElementById("title");
				var val=obj.value;
				if (val != '')
					obj.value=val+" type:\\d/";
            });
        });
        $(function(){
            $("#addscxs").click(function(){
                var obj=document.getElementById("title");
				var val=obj.value;
				if (val != '')
					obj.value=val+" type:\\n/";
            });
        });
        $(function(){
            $("#addscmh").click(function(){
                var obj=document.getElementById("title");
				var val=obj.value;
				if (val != '')
					obj.value=val+" type:\\c/";
            });
        });
        //判断首页
        var a = location.href;if(a=='http://airanime.applinzi.com/'){$('#ifhomea').show();}else $('#ifhomea').hide();
    </script>
    <script type="text/javascript">
        //表单提交时加载动画
        function getId(id) {
            return document.getElementById(id);
        }
        function validation() {
            getId("btnS").style.display="none";
            getId("loading").style.display="";
            return true;
        }
 	</script> 
 	<script>
        //自动联想及PicSearch判断
        $(function(){
            $('#search input[name="title"]').keyup(function(){
                var text=document.getElementById("title");
                var i=text.value.indexOf('!image:')
                if (i != '-1') {
                    getId("picstip").style.display="";
                    getId("srhauto").style.display="none";
                } else {
                    getId("picstip").style.display="none";
                $.post( './functions/srhauto.php', { 'value' : $(this).val() },function(data){
                    if( document.getElementById("title").value == '' ) 
                        $('#srhauto').html('').css('display','none');
                    else
                        $('#srhauto').html(data).css('display','block');
                });
                }
            });
        });
	</script>
	<script>
        //判断类型与AJAX提交
	   window.onload=function() {
            var oBtn1=document.getElementById('btnS');
            var oInput=document.getElementById("title");
            var oDiv=document.getElementById("rst_show");
        oBtn1.onclick=function() {
            var i=oInput.value.indexOf('type:\\d/');
            if (i != '-1'){
                getId("btnS").style.display="none";
                getId("btnUP").style.display="none";
                getId("loading").style.display="";
                var name = oInput.value.substring(0,oInput.value.indexOf("type:\\d/"));
                window.location.href='./d/?'+name;
            } else {
        	   getId("srhauto").style.display="none";
        	   getId("btnS").style.display="none";
               getId("btnUP").style.display="none";
               getId("btnUP").style.display="none";
        	   getId("loading").style.display="";
                var sValue=oInput.value;
                //1.创建Ajax对象
                if(window.XMLHttpRequest) {
                    var oAjax=new XMLHttpRequest();
                } else {
                    var oAjax=new ActiveXObject('Microsoft.XMLHTTP');
                }
                //2.连接服务器
                oAjax.open('get','run.php?title='+sValue,true);
                //3.发送请求
                oAjax.send();
                //4.接收返回
                oAjax.onreadystatechange=function()
                {
                    //oAjax.readyState  记录步骤
                    if(oAjax.readyState == 4) {    
                        if(oAjax.status == 200) {
                            oDiv.innerHTML = oAjax.responseText;
                            document.getElementById("title").value='';
                            getId("ifhomea").style.display="none";
                            getId("srhauto").style.display="none";
    	         			getId("btnS").style.display="";
    	          			getId("loading").style.display="none";
                            getId("picstip").style.display="none";
                                //获取番剧信息
                                    //联想标题
                                $(function(){
                                    $("#infoget").click(function(){
                                        $('#banguim_info').html('<br>少女祈愿中...').css('display','block');
                                        var t=document.getElementById("infoget").name;
                                        $.post( './functions/bangumiinfo.php', { 'value' : t },function(data){
                                            if( t == '' ) 
                                                $('#banguim_info').html('').css('display','none');
                                            else
                                                $('#banguim_info').html(data).css('display','block');
                                        });
                                    });
                                });
                                    //原标题
                                $(function(){
                                    $("#infogeto").click(function(){
                                        $('#banguim_info').html('<br>少女祈愿中...').css('display','block');
                                        var t=document.getElementById("infogeto").name;
                                        $.post( './functions/bangumiinfo.php', { 'value' : t },function(data){
                                            if( t == '' ) 
                                                $('#banguim_info').html('').css('display','none');
                                            else
                                                $('#banguim_info').html(data).css('display','block');
                                        });
                                    });
                                });
                                //
                        } else {
                            alert("搜索失败，请刷新重试");
                        }
                    }
                }
            }
        };
       };
	    //回车事件绑定
        $('#title').bind('keypress', function(event) {  
            if (event.keyCode == "13") {              
                event.preventDefault();   
                //回车执行
                $('#btnS').click();  
            }  
        });
    </script>
</body>
</html>