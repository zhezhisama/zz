

///////获得成绩排行
var SaveFirstStr1 = "<div id=\"bak2\" style=\"border: 0px none; background-color: darkgray; z-index: 1109; opacity: 0.3; position: absolute; visibility: visible; display: block; top: 0px; left: 0px; width: 100%; height: 100%; filter:alpha(opacity=50);\"></div>" +
        "<div id=\"tanchuang2\" style=\" background-color: white;background-image:url('img/popwin.jpg'); z-index: 1109;  position: absolute; visibility: visible; display: block; top: 150px; left: 20%; width: 231px; height: 147px;\">" +
        "<p align=\"center\"></p><p align=\"center\">";
var SaveFirstStr2 = "</p></div><script> function hide2(){document.getElementById(\"tanchuang2\").style.display = \"none\";" +
"document.getElementById(\"bak2\").style.display = \"none\";}</script>";

var showWXdiv = "<div id=\"bak3\" style=\"border: 0px none; background-color: darkgray; z-index: 1109; opacity: 0.3; position: absolute; visibility: visible; display: block; top: 0px; left: 0px; width: 100%; height: 100%; filter:alpha(opacity=50);\"></div>" +
        "<div id=\"tanchuang3\" style=\" background-color: white;background-image:url('img/popwinNIn.jpg'); z-index: 1109;  position: absolute; visibility: visible; display: block; top: 100px; left: 15%; width: 231px; height: 204px;\">" +
        "<p align=\"center\">&nbsp;</p><p align=\"center\">&nbsp;</p>";
showWXdiv += "<p align=\"center\">输入微信号：</p><div align=\"center\" style='width:90%;margin-left:5%'><input id=\"txt_wx\" type=\"text\" ></input></div>" +
        "<div align=\"center\" style='width:90%;margin-left:5%'><input type='button' onclick='hide3()' value='取消' data-inline=\"true\"/><input type='button' onclick='save()' value='提交' data-inline=\"true\" data-theme=\"b\"/></div></br>" +
        "<p align=\"center\"><span id='errmsg' style='color:red'></span></div>";
showWXdiv += "</div><script>function trim(str){return str.replace(/^(\s|\xA0)+|(\s|\xA0)+$/g,'').replace(/[]/g,'');} function hide3(){document.getElementById(\"tanchuang3\").style.display = \"none\";" +
"document.getElementById(\"bak3\").style.display = \"none\";} " +
"function save(){var wx=document.getElementById(\"txt_wx\").value; var dq = document.getElementById(\"dq\").value;  if(trim(wx)!=\"\"){saveMain(wx,getQString('code'),dq);}else {$('#errmsg').html('请输入微信号');}}</script>";





$(document).ready(function () {
    //$("#btncontinue").attr("href", "examIndex.aspx?code=" + getQString('code')); //examTList.html&response_type=code&scope=snsapi_base&state=1#webchat_redirect
    //$("#btncontinue").attr("href", "examTList.html&response_type=code&scope=snsapi_base&state=1#webchat_redirect");
    $(document).bind("showDialog", function () {
        $.mobile.page.prototype.options.keepNative = "select";
    });

    $("#btncontinue").click(function () {
        min = 2;
        sec = 59;
        var type = sessionStorage.type;
        sessionStorage.clear();
        sessionStorage.type = type;
        location.href = "zxd.php?type=" + type + "&code=" + getQString('code');
    });
    $("#btn_saveScore").click(function () {
        $("#sph").html(showWXdiv);
        $("#sph").trigger("create");
    });
});

//保存成绩
function saveMain(wx,code,dq) {
    var type = sessionStorage.type;
    var score = sessionStorage.score;
    var heightScore = localStorage.heightScore;
    var recordBreak = "no";
    if (code != "null") {
        localStorage.code = code;
    } else {
        code = localStorage.code;
    }
    if (typeof (heightScore) == "undefined") heightScore = "0";

    if (parseInt(heightScore) > 0 && parseInt(score) > parseInt(heightScore)) {
        recordBreak = "yes";
    }
    if (typeof (wx) == "undefined") wx = "unknown";

    $.ajax({
        type: "get",
        datatype: "json",
        url: "../web/GetExamHandler.ashx?action=submit&wx=" + wx + "&type=" + type + "&score=" + score + "&break=" + recordBreak + "&code=" + code+"&dq="+encodeURI(dq)+"&"+Math.random(),
        success: function (res) {
            var obj = eval(res);
            var r = obj.r;
            if (r == "True") {
                var gsuc = obj.success;
                var guid = obj.guid;
                if (gsuc == "1") {
                    localStorage.heightScore = score;
                    location.href = "Award.htm?id=" + guid;
                    //                $("#sph").html(SaveFirstStr1 + "成绩保存成功！</p><p align=\"center\"><button type='button' onclick='hide2()'>确定</button>" + SaveFirstStr2);
                    //                $("#sph").trigger("create");
                } else { $("#sph").html(SaveFirstStr1 + "成绩保存失败！</p><p align=\"center\"><button type='button' onclick='hide2()'>确定</button>" + SaveFirstStr2); }
            } else {
                $("#sph").html(SaveFirstStr1 + "活动已结束</p><p align=\"center\"><button type='button' onclick='hide2()'>确定</button>" + SaveFirstStr2); 
            }
        }, error: function () { $("#sph").html(SaveFirstStr1 + "提交成绩时发生错误~</p><p align=\"center\"><button type='button' onclick='hide2()'>确定</button>" + SaveFirstStr2); }
    });
}


function getQString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);return null;
}