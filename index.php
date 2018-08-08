<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
    <meta name="keywords" content="airAnime,Anime Search Engine,动漫搜索,Trii Hsia">
    <meta name="description" content="一款不错的聚合动漫&番剧搜索程序">
	<title>airAnime Online - Polymeric Anime Search Engine</title>

    <link href="./css/mori.css" rel="stylesheet">
	<link href="./css/base.min.css" rel="stylesheet">
	<link href="./css/project.min.css" rel="stylesheet">
    <link href="./js/zoom-js/css/zoom.css" rel="stylesheet">
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
											搜索指令: <code><a href="javascript:void(0)" id="addsctp">!image</a></code> <code><a href="javascript:void(0)" id="addscmh">&comic</a></code> <code><a href="javascript:void(0)" id="addscxs">&novel</a></code>
											<div style="text-align:right;">
											<a class="btn btn-flat waves-attach" id="btnUP">Upload</a> <a class="btn btn-brand waves-attach waves-light" href="javascript:void(0)" id="btnS"> Search </a> 
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
					<div class="card" style="margin-top:-15px;margin-bottom:45px;">
							<div class="card-main">
								<div class="card-inner" id="srhauto" style="display:none;">
									<div id="search_auto"></div>
								</div>
							</div>
					</div>

        <!-- 以图搜番说明 -->
        <div id='picstip' style="display:none;">
            <div class="barc-t"><div class="barc-tile" style="height: 100%;">
                <div style="padding-left:10px;padding-top:10px;padding-bottom:10px;">PicSearch</a><span class="arc-date">&以图搜番说明</span><br>
                    <div style="padding-left:5px;">
                        <span id="desb" class="arc-date">1.只可搜索出自日本动画(动漫)中的图片<br>2.图像大小 <= 1MB<br>3.若搜索本地图片，请点击UPLOAD上传图像再搜索<br>4.若图片为GIF格式，必须上传后才可正常搜索<br></span>
                    </div>
                <span id="desb" class="arc-date">更多搜索指令使用说明请参考 <a href="./pages/srhcode.php">搜索指令</a>&<a href="./pages/start.php">使用说明</a> 页面</span>
                </div>
            </div></div>
        </div>

        <?php
            if (isset($_GET["title"])) {
                $sotitle=$_GET["title"];
            } else {
                $sotitle='';
            }
        ?>

			<div id="rst_show"></div>

        <!-- 图片上传表单 -->
        <div id='upimage' style="display:none;"> 
            <form enctype="multipart/form-data" method="post" action="http://up.imgapi.com/" id="upform">
            <input name="Token" id="token" value="{your_token}" type="hidden">
            <input type="hidden" name="from" value="file">
            <input type="hidden" name="httptype" value="1">
            <input type="file" name="file" id="file" style="display:none">
            <input type="submit" id='btnU' value="上传" class="btn">
            </form>
        </div>

        <!-- 首页公示 -->
        <div id='ifhomea' style="display:none;">
            <div class="logo" id="logo" data-tilt data-tilt-glare="true" data-tilt-max-glare="0.3" data-tilt-speed="200" data-tilt-scale="1" data-tilt-max="12" data-tilt-perspective="900">
                <span>Welcome</span>
                <div class="homelink"><a href="./pages/start.php">&开始</a> <a href="./pages/about.php">&关于</a> <a href="./pages/srhcode.php">&搜索指令</a><br><span style="opacity: 0.7;font-size: 14px;"><a href="./pages/public.php">捐赠本项目?</a></span></div>
            </div>
        </div>

        <!-- 随机推荐 -->
        <div id='ifhomeaii' class="post-content" style="display:none;">
            <h4><a style="color:#78909c;text-decoration:none;" id="todayB" href="javascript:void(0)">番剧推荐</a> <span style="font-size:10px;">(′・ω・`) 点点看?</span></h4>
            <div id="sugB_show">
                <?php
                    $myfile=fopen("./functions/data/bangumiToday.json", "r") or die("Unable to open file!");
                        $bgmC=fgets($myfile);
                    fclose($myfile);
                    $bgmC=json_decode($bgmC, true);
                    $id=$bgmC['id'];
                    if ($bgmC['name_cn']=='') {
                        $name=$bgmC['name'];
                    } else {
                        $name=$bgmC['name_cn'];
                    }
                    $des=$bgmC['summary'];
                    $img=$bgmC['images']['large'];
                    $air_date=$bgmC['air_date'];

                    if ($des=='') {
                        $des='抱歉，暂无简介...';
                    } else {
                        $des=str_replace('　　','',$des);
                        $des=str_replace(' ','',$des);
                    }

                    $url='http://bgm.tv/subject/'.$id;
                    echo '<div class="barc-t"><div class="barc-tile">';
                    echo '<div style="width:40%;max-height:300px;float:left;margin-right:10px;"><img src="'.$img.'" data-action="zoom" class="img-rounded img-responsive"></div>';
                    echo '<div style="padding-top:10px;padding-bottom:10px;"><a target="_blank" href="'.$url.'">'.$name.'</a><br><span class="arc-date">&'.$bgmC['name'].'</span><br><span class="arc-date">首播: '.$air_date.'</span><br><span id="desb" class="arc-date">'.$des.'</span><br><span class="arc-date">数据来源于Bangumi番组计划.</span></div>';
                    echo '</div></div>';
                ?>
            </div>
        </div>
        
        <!-- 今日新番 -->
        <div id='ifhomeai' class="post-content" style="display:none;">
            <?php
                $file="./functions/data/bangumiS2017.json";
                $bcon=file_get_contents($file);
                $bcon=json_decode($bcon, true);
                $today=date("N")-1;
                $bcon=$bcon[$today];
                $num=count($bcon['items']);
                echo '<h4>今日 - 快乐'.$bcon['weekday']['cn'].'</h4>';
                for ($i=0; $i < $num; $i++) { 
                    if ($bcon['items'][$i]['name_cn']=='') {
                        $name_cn=$bcon['items'][$i]['name'];
                    } else {
                        $name_cn=$bcon['items'][$i]['name_cn'];
                    }
                    $img=$bcon['items'][$i]['images']['large'];
                    $img=str_replace('http://lain.bgm.tv','http://7xkwzy.com1.z0.glb.clouddn.com',$img);
                    echo '<div class="arc-t"><div class="arc-tile"><div style="box-shadow: 0 2px 15px 1px rgba(0,0,0,0.15);width:46%;max-height:150px;float:left;margin-right:6px;"><img src="'.$img.'" data-action="zoom" class="img-rounded img-responsive"></div><small><a target="_blank" href="'.$bcon['items'][$i]['url'].'">'.$name_cn.'</a></small><br><span class="arc-date">&'.$bcon['items'][$i]['name'].'</span><br><span class="arc-date">首播: '.$bcon['items'][$i]['air_date'].'</span></div></div>';
                }
            ?>
        </div>   
	</div>
    </div>
    </div>
    
	<?php
	pagepart('footer');
	pagepart('ball');
	?>

	<!-- js -->
	<script src="./js/jquery.min2.20.js"></script>
	<script src="./js/base.min.js"></script>
	<script src="./js/project.min.js"></script>
    <script src="./js/jquery.form.js"></script>
    <script src="./js/embed.js"></script>
    <script src="./js/zoom-js/js/zoom.js"></script>
    <script src="./js/vanilla-tilt.min.js"></script>
    <script src="./js/md5.js"></script>
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
        $(document).ready(function() {
            var a = location.href;
                if(a=='http://airanime.applinzi.com/'){
                    $("#ifhomea").fadeIn(300);
                    $("#ifhomeaii").fadeIn(500);
                    $("#ifhomeai").fadeIn(600);
                    $("#adshow").fadeIn(800);
                } else {
                    $('#ifhomea').hide();
                    $('#ifhomeai').hide();
                    $('#ifhomeai').hide();
                    $('#adshow').hide();
                }
        });
        </script>
        <script type="text/javascript">
            //随机推荐
            $("#todayB").click(function(){
                todayB('run');
            });

           function todayB(code){ //today:个人推荐 run:随机推荐 up:更新缓存
            if (code!='today') {$('#sugB_show').html('少女祈愿中...').css('display','block');}
            var t=code;
            $.post( './functions/bangumiSug.php', { 'code' : t },function(data){
            if( data == '' ) 
                $('#sugB_show').html('获取失败').css('display','none');
            else
                $('#sugB_show').html(data).css('display','block');
                $("#translateB").click(function(){
                    var text=document.getElementById("desb").innerHTML;
                    baiduTr(text);
                });

                function baiduTr(text){
                    var appid = '{...}';
                    var key = '{...}';
                    var salt = (new Date).getTime();
                    var query = text;
                    var from = 'auto';
                    var to = 'zh';
                    var str1 = appid + query + salt +key;
                    var sign = MD5(str1);
                    $.ajax({
                        url: 'http://api.fanyi.baidu.com/api/trans/vip/translate',
                        type: 'get',
                        dataType: 'jsonp',
                        data: {
                            q: query,
                            appid: appid,
                            salt: salt,
                            from: from,
                            to: to,
                            sign: sign
                            },
                        success: function (data) {
                            var num=data['trans_result'].length;
                            var text='';
                            for (var i = 0; i < num; i++) {
                                text=text+data['trans_result'][i]['dst'];
                            }
                            $('#desb').html(text).css('display','block');
                        } 
                    });
                }
            });
           }
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
                var i=text.value.indexOf('!image:');
                if (i != '-1') {
                    $("#srhauto").fadeOut(100);
                    $("#picstip").fadeIn(500);
                } else {
                    $("#picstip").fadeOut(100);
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
    //判断类型并AJAX提交
    function airsim()
    {
        if (document.getElementById("title").value!='') {
        var oBtn1=document.getElementById('btnS');
        var oInput=document.getElementById("title");
        var i=oInput.value.indexOf('type:\\d/');
            if (i != '-1'){
                getId("btnS").style.display="none";
                getId("btnUP").style.display="none";
                getId("loading").style.display="";
                var name = oInput.value.substring(0,oInput.value.indexOf("type:\\d/"));
                window.location.href='./d/?'+name;
            } else {
                var title=oInput.value;
                getId("srhauto").style.display="none";
                getId("btnS").style.display="none";
                getId("btnUP").style.display="none";
                getId("loading").style.display="";
                    $.post( './run.php', { 'title' : title },function(data){
                        if( title == '' ) 
                            alert("搜索失败，请刷新重试");
                        else
                            $('#rst_show').html(data).css('display','block');
                            document.getElementById("title").value='';
                            getId("ifhomea").style.display="none";
                            getId("ifhomeai").style.display="none";
                            getId("ifhomeaii").style.display="none";
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
                    });
            }
        }
    }

        // '/?title=' 执行AJAX
        var sotitle ="<?php echo $sotitle; ?>";
        if (sotitle!='') {
            var i=sotitle.indexOf(' type:');
            if (i != "-1") {
                var name1 = sotitle.substring(0,sotitle.indexOf(" type:"));
                var name2 = " type:\\"+sotitle.substring(sotitle.indexOf(" type:")+6,sotitle.indexOf("/"))+"/";
                sotitle=name1+name2;
            }
            document.getElementById('title').value=sotitle;
            airsim();
        }

        //按钮 执行AJAX
        document.getElementById('btnS').onclick=function() {
            airsim();
        };

	    //回车 执行AJAX
        $('#title').bind('keypress', function(event) {  
            if (event.keyCode == "13") {              
                event.preventDefault();   
                //回车执行
                airsim(); 
            }  
        });
    </script>
</body>
</html>