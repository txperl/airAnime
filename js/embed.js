$(document).ready(function(){
	$("#btnU").click(function(){
		//判断上传文件的类型
		filepath=$("#file").val();
		var extStart=filepath.lastIndexOf(".");
		var ext=filepath.substring(extStart,filepath.length).toUpperCase();
		var max=1048869; // 大小限制为 1MB
		var img_size = document.getElementById("file").files[0].size;
		if(ext!=".BMP"&&ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("图片限于png,gif,jpeg,jpg格式");
			return false;
		}
		if (img_size>max) {
			alert("图片最大为1MB");
			return false;
		}
		//开始上传
		getId("btnS").style.display="none";
        getId("btnUP").style.display="none";
    	getId("loading").style.display="";
    	getId("srhauto").style.display="none";
		//使用jquery.form插件异步提交表单，详细内容参考官方文档
		$("#upform").ajaxForm(function(data,status){
			if(status == "success") {
				var imginfo = data;
				getId("btnS").style.display="";
         		getId("btnUP").style.display="";
    			getId("loading").style.display="none";
    			getId("srhauto").style.display="none";
    			$('#title').focus();
				document.getElementById("title").value='!image:'+imginfo.linkurl+';';
			}
			else{
				alert('上传错误！请重新上传图片。');
			}
		});
	});
});
