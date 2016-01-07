//剩余时间

TimeLeft();
//--------<
var showTime = "";
function TimeLeft() {
    if (sec == 0 && min > 0) {
        min -= 1;
        sec = 59;
    }
    if (sec == 0 && min == 0) {
        showTime = "时间到！";
        sessionStorage.end = "true";
        //showDialog(); //弹窗显示分数，并成绩入库
		document.getElementById('form4').submit();
    } else if (sec == -1 && min == -1) {
        showDialog(); //弹窗显示分数，并成绩入库
    }else {
        showTime = min + "分" + sec;
        sec -= 1;
        setTimeout(function () { TimeLeft(); }, 1000);
    }
    $("#ltime").html("倒计时："+showTime);
}
var shareJsStr = "<script>$(function(){ WeixinJSBridge.call('showOptionMenu'); WeixinJSBridge.call('hideToolbar'); });</script>";
var dqDiv = "<br/>请选择地区:<div id='sele'><select name='dq' id='dq' data-native-menu='false'>"+
"<option value='徐州'>徐州</option><option value='合肥'>合肥</option><option value='南京'>南京</option><option value='上海'>上海</option><option value='杭州'>杭州</option></div>";

//模拟提交
function showDialog() {
    sessionStorage.subStatus = "true";
    $("#btn_show").trigger("click");
var score = sessionStorage.score;
if (typeof (score) == "undefined") score = "0";
    $("#PHB").html("本次答题成绩："+score +" 分。"+dqDiv);
    $("#PHB").trigger("create");
    getPHB();
}

///成绩入库并计算排名
function getPHB() {
    var type = sessionStorage.type;
    var score = sessionStorage.score;
    var heightScore = localStorage.heightScore;
    if (typeof (score) == "undefined") score = "0";
    if (typeof (heightScore) == "undefined") heightScore = "0";
    
   
    //--->
    $.ajax({
        type: "get",
        datatype: "json",
        url: "../web/GetExamHandler.ashx?action=ckphb&type=" + type + "&score=" + score,
        success: function (res) {
            var obj = eval(res);
            var r = obj.result;
			if(r=="True"){
                var gscore = obj.score;
                var ph = obj.ph;
                var msg = "本次答题成绩：<font color='red'>" + gscore + "</font>分，";
                if (parseInt(heightScore) > 0 && parseInt(score) > parseInt(heightScore)) {
                    msg += "刷新了个人记录！";
                }
                msg += "目前排行第" + ph + "位。";
                $("#PHB").html(msg+dqDiv);
                $("#PHB").trigger("create");
            } else {
                $("#sph").html(AnsFirstStr1 + "活动已结束" + AnsFirstStr2);
            }
        }, error: function () { $("#sph").html(AnsFirstStr1 + "查找排名时失败~" + AnsFirstStr2); }
    });
    //--<
           
    //sessionStorage.clear(); //清空所有内容
}

