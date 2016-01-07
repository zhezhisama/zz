$(function() {
    $("a.upbtn").click(function() {
        if ($.trim($("input[name=wxtitle]").val()) == "") {
            alert("请填写我的昵称");
            return false
        }
       /* if ($.trim($("input[name=wxid]").val()) == "") {
            alert("请填写我的微信号");
            return false
        }*/
        if ($("input[name=wximg]").val() == "") {
            alert("请上传我的头像");
            return false
        }
        if ($("input[name=qrcode]").val() == "") {
            alert("请上传微信二维码");
            return false
        }
        if ($("#content").val() == "") {
            alert("请填写个人描述");
            return false
        }
        $("#codeform").submit()
    });
    var bar = $(".bar");
    var percent = $(".percent");
    var showimg = $("#showimg");
    var progress = $(".progress");
    var files = $(".files");
    var btn = $(".btn span");
    var btn1 = $(".btn");
    $("#fileupload").wrap("<form id='myupload' action='upimg.php?a=qcimg&r=" + Math.random() + "' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function() {
        $("#myupload").ajaxSubmit({
            dataType: "json",
            beforeSend: function() {
                showimg.empty();
                progress.show();
                var percentVal = "0%";
                bar.width(percentVal);
                bar.show();
                btn.show();
                btn1.show();
                files.hide();
                percent.show();
                percent.html(percentVal);
                btn.html("上传中...")
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + "%";
                bar.width(percentVal);
                percent.html(percentVal)
            },
            success: function(data) {
                var err = data.err;
                if (err) {
                    btn.html("重新上传");
                    bar.width("0");
                    percent.hide();
                    files.html('<font style="color:red">' + err + "</font>");
                    files.show();
                    $("input[name=qrcode]").val("")
                } else {
                	var img = data.pic;
                    var key = data.key;
                    var upid = data.upid;
                    percent.hide();
                    files.hide();
                    bar.hide();
                    btn.hide();
                    btn1.hide();
                    //if (img.indexOf("http://") < 0) {
                     //   img = "http://m.chaojirm.com/static/qcimg" + img
                    //}
                    showimg.html("<span><img src='" + img + "' width='100' class='qcimg'><span class='delimg' rel='" + img + "'>删除</span></span>");
                    btn.html("图片上传");
                    $("input[name=qrcode]").val(img);
                    $("input[name=qrcode_upid]").val(upid)
                }
            },
            error: function(xhr) {
            	alert();
                btn.html("重新上传");
                bar.width("0");
                percent.hide();
                files.show();
                files.html('<font style="color:red">图片上传失败!</font>');
                $("input[name=qrcode]").val("")
            }
        })
    });
    $(".delimg").live("click",
    function() {
        var pic = $(this).attr("rel");
        percent.hide();
        bar.hide();
        btn.show();
        btn1.show();
        files.hide();
        showimg.empty();
        progress.hide();
        $("input[name=qrcode]").val("")
    });
    var bar1 = $(".bar1");
    var percent1 = $(".percent1");
    var showimg1 = $("#showimg1");
    var progress1 = $(".progress1");
    var files1 = $(".files1");
    var btn2 = $(".btn1 span");
    var btn3 = $(".btn1");
    $("#fileupload1").wrap("<form id='myupload1' action='upimg.php?a=himg&r=" + Math.random() + "' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload1").change(function() {
        $("#myupload1").ajaxSubmit({
            dataType: "json",
            beforeSend: function() {
                showimg1.empty();
                progress1.show();
                var percentVal = "0%";
                bar1.width(percentVal);
                bar1.show();
                btn2.show();
                btn3.show();
                files1.hide();
                percent1.show();
                percent1.html(percentVal);
                btn2.html("上传中...")
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + "%";
                bar1.width(percentVal);
                percent1.html(percentVal)
            },
            success: function(data) {
                var err = data.err;
                if (err) {
                    btn2.html("重新上传");
                    bar1.width("0");
                    percent1.hide();
                    files1.html('<font style="color:red">' + err + "</font>");
                    files1.show();
                    $("input[name=wximg]").val("")
                } else {
                    var img = data.pic;
                    var key = data.key;
                    var upid = data.upid;
                    percent1.hide();
                    files1.hide();
                    bar1.hide();
                    btn2.hide();
                    btn3.hide();
                    /*if (img.indexOf("http://") < 0) {
                        img = "http://m.chaojirm.com/static/qcimg" + img
                    }*/
                    showimg1.html("<span><img src='" + img + "' width='100' class='qcimg'><span class='delimg1' rel='" + img + "'>删除</span></span>");
                    btn2.html("图片上传");
                    $("input[name=wximg]").val(img);
                    $("input[name=wximg_upid]").val(upid)
                }
            },
            error: function(xhr) {
                btn2.html("重新上传");
                bar1.width("0");
                percent1.hide();
                files1.show();
                files1.html('<font style="color:red">图片上传失败!</font>');
                $("input[name=wximg]").val("")
            }
        })
    });
    $(".delimg1").live("click",
    function() {
        var pic = $(this).attr("rel");
        percent1.hide();
        bar1.hide();
        btn2.show();
        btn3.show();
        files1.hide();
        showimg1.empty();
        progress1.hide();
        $("input[name=wximg]").val("")
    })
});