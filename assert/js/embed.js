$(document).ready(function () {
    $("#btnUUP").click(function () {
        //判断上传文件的类型
        filepath = $("#btnFile").val();
        var extStart = filepath.lastIndexOf(".");
        var ext = filepath.substring(extStart, filepath.length).toUpperCase();
        var max = 5242880; // 大小限制为 5MB
        var img_size = document.getElementById("btnFile").files[0].size;
        if (ext != ".BMP" && ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
            //alert("图像仅限于 png,gif,jpeg,jpg 格式");
            //return false;
        }
        if (img_size > max) {
            alert("图像最大为 5MB");
            return false;
        }
        //开始上传
        $("#keytitle").val('loading...');
        //使用jquery.form插件异步提交表单，详细内容参考官方文档
        $("#upform").ajaxForm(function (data, status) {
            if (data.code == "success") {
                var imginfo = data;
                console.log(imginfo);
                $('#keytitle').focus();
                $('#keytitle').val(imginfo.data['url']);
            }
            else if(data.code == "exception" && data.message.split("https:").length > 1) {
                console.log(data);
                $('#keytitle').focus();
                $('#keytitle').val("https:" + data.message.split("https:")[1]);
            } else {
                console.log(data);
                alert('上传错误！请重新上传图片。');
            }
        });
    });
});
