/*
if (vip && fansid) {
    var timestamp = Math.round(new Date() / 1000);
    if (storage.getItem("refresh")) {
        if (timestamp - mytime < 600 && (vip == 2 || upnum <= 4)) {
            setTimeout("uptime()", 1000)
        }
    }
}*/


function uptime() {
    var timestamp = Math.round(new Date() / 1000);
    var t = 600 - (timestamp - mytime);
    if (t) {
        $(".refresh").html("剩余:" + t + "秒");
        setTimeout("uptime()", 1000)
    } else {
        $(".refresh").html("置顶刷新")
    }
}

$(function() {
	
    $(".refresh").click(function() {
        if ($("#msgshow1").css("display") == "none") {
            $("#msgshow1").show()
        } else {
            $("#msgshow1").hide()
        }
    });
    $(".imgshow").click(function() {
        var timestamp = Math.round(new Date() / 1000);
        if (ctime && timestamp - ctime >= 5) {
            if (storage.getItem("addfans_num")) {
                var click_num = parseInt(storage.getItem("addfans_num"));
                storage.setItem("addfans_num", click_num + 1)
            } else {
                storage.setItem("addfans_num", 1)
            }
        }
        $("#showcode").css("display", "none")
    });
    /*$("a.addfans").live("click",
    function() {
        ctime = Math.round(new Date() / 1000);
        var fansid = $(this).attr("fansid");
        $("#showimg").attr("src", $(".fscode_it" + fansid).val());
        $("#showcode").css("display", "block")
    });*/
    $("a.upcode").click(function() {
        if (!vip) {
            alert("您不是VIP会员，无法发布二维码！\n\n购买VIP会员即可发布二维码，享受主动被加的特权，结识更多人脉朋友！");
            return false
        } else {
            if (vip < 2 && !fansid) {
                if (storage.getItem("addfans_num")) {
                    var click_num = parseInt(storage.getItem("addfans_num"));
                    if (click_num < 5) {
                        alert("亲爱的,您是试用VIP，需要加5个好友才能发布哦！\n\n购买包月VIP，无需加人即可发布二维码！\n\n您已加了" + click_num + "人，继续加油！");
                        return false
                    }
                    storage.setItem("addfans_num", 0)
                } else {
                    storage.setItem("addfans_num", 0);
                    alert("亲爱的,您是试用VIP，需要加5个好友才能发布哦！\n\n购买包月VIP，无需加人即可发布二维码！\n\n您已加了0人，继续加油！");
                    return false
                }
            }
        }
    });
    /*$("a.refresh").click(function() {
        if (vip && fansid) {
            if (upnum >= 4 && vip == 1) {
                alert("亲爱的,您是试用VIP，每日只能置顶4次（刚发布算1次）！\n\n购买包月VIP，即可不受置顶次数的限制！");
                return false
            }
            var timestamp = Math.round(new Date() / 1000);
            if (storage.getItem("refresh")) {
                if (timestamp - mytime < 600) {
                    alert("距您上次刷新不到10分钟,请您休息会,稍后再刷新!\n\n还需等待：" + (600 - (timestamp - mytime)) + "秒！");
                    return false
                }
            }
            if (vip != 2) {
                if (storage.getItem("addfans_num")) {
                    var click_num = parseInt(storage.getItem("addfans_num"));
                    if (click_num < 5) {
                        alert("亲爱的,您是试用VIP，加5个好友后才可使用置顶刷新一次哦！\n\n购买包月VIP，无需加人即可使用置顶刷新！\n\n您已加了" + click_num + "人，继续加油！");
                        return false
                    }
                    storage.setItem("addfans_num", 0)
                } else {
                    storage.setItem("addfans_num", 0);
                    alert("亲爱的,您是试用VIP，加5个好友后才可使用置顶刷新一次哦！\n\n购买包月VIP，无需加人即可使用置顶刷新！\n\n您已加了0人，继续加油！");
                    return false
                }
            }
            $.get("/fans.php?a=upfans&r=" + timestamp, {},
            function(data) {
                var info = eval(data);
                if (info.sta == "ok") {
                    storage.setItem("refresh", timestamp);
                    window.location.href = "/wxfans.php?up=1&r=" + Math.round(new Date())
                } else {
                    if (info.sta == "time") {
                        alert("距您上次刷新不到10分钟,请您休息会,稍后再刷新!\n\n还需等待：" + (600 - info.flist) + "秒！");
                        return false
                    } else {
                        if (info.sta == "vip") {
                            alert("置顶失败，只有VIP会员才能使用。");
                            return false
                        } else {
                            if (info.sta == "upnum") {
                                alert("亲爱的,您是试用VIP，每日只能置顶4次（新发布算1次）！\n\n购买包月VIP，即可不受置顶次数的限制！");
                                return false
                            } else {
                                alert("置顶刷新失败，请稍后再尝试！\n\n如刚发布的二维码，需要等待10分钟才可刷新！");
                                return false
                            }
                        }
                    }
                }
            },
            "json")
        } else {
            $("#msgshow").css("display", "block")
        }
    });*/
    $("#msgshow").click(function() {
        storage.setItem("msgshow", "1");
        $(this).css("display", "none")
    });
    if (!page) {
        var stop = true;
        var page = 2
    }
    $(window).scroll(function() {
        if ($(document).scrollTop() + 100 >= $(document).height() - $(window).height()) {
            if (stop == true) {
                $("#loading").css("display", "block");
                stop = false;
                setTimeout(function() {
                    var lnum = $("input[name=limitnum]").val();
                    $.get("/fans.php?a=getfans&page=" + page + "&utime=" + utime + "&r=" + Math.random(), {},
                    function(data) {
                        var info = eval(data);
                        if (info.sta == "err") {
                            stop = true;
                            $("#loading").css("display", "none")
                        } else {
                            if (info.sta == "ok") {
                                var liStr = "";
                                for (var i = 0; i < info.flist.length; i++) {
                                    liStr += '<li><div class="headimg"><img src="' + info.flist[i].headimg + '"/></div><div class="desc"><span class="name"><span style="color: #999;">[' + info.flist[i].city + "]</span>" + info.flist[i].username + '</span><span class="desc_info">' + info.flist[i].remark + '</span></div><div class="adddiv"><a href="javascript:;" class="addfans" class="fansadd" fansid="' + info.flist[i].id + '">加好友</a></div><input type="hidden" name="fsimg' + info.flist[i].id + '" class="fscode_it' + info.flist[i].id + '" value="' + info.flist[i].qrcode + '"/></li>'
                                }
                                page++;
                                $("ul.list").append(liStr);
                                $("input[name=limitnum]").val(parseInt(lnum) + 2);
                                stop = true
                            } else {
                                if (info.sta == "null") {
                                    stop = false;
                                    $("#loading").css("display", "none");
                                    liStr = '<li style="height:20px;"><center color="green">已经全部加载完毕！</center></li>';
                                    $("ul.list").append(liStr)
                                } else {
                                    stop = true;
                                    $("#loading").css("display", "none");
                                    $("#loading").hide()
                                }
                            }
                        }
                    },
                    "json")
                },
                200)
            }
        }
    });
    $(document).scrollTop(0);
    $("input[name=limitnum]").val(10)
});