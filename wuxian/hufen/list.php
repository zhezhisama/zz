<?php
/**
本页可定义变量
$xianzhitime = 60;			//设置刷新置顶限制时间，单位：秒
$haoyou = 10;				//设置刷新置顶加好友人数限制
刷粉列表组成为 付费（多个）+新加（1个）+刷新置顶（2个），分别用shuaxin表示
shuaxin为0时，表示没有置顶，置顶为1时是刷新置顶，为2时是最新刷新置顶，为3时是新添加用户

每刷新一次记录次数 up_num+ 日期改变归零
**/
define('IN_QY',true);
define('SCRIPT','list');
require("include/common.inc.php");
//判断排序字段是否存在，不存在新增
$rs = mysql_query("DESCRIBE weixin `listorder`");
if(!mysql_num_rows($rs)) {
	$sql="alter table weixin add listorder int(10)  default 0";
$result=mysql_query($sql);
}
session_start();
$utime = time();
/*
if(!$_COOKIE['sid']){
	setcookie("sid",session_id(),time()+(3600*24*365*10));
}
*/
$uid = $_GET['uid'] ? $_GET['uid'] : $_SESSION['uid'];
//判断是否为微信用户并设置用户sessionID
if($uid){
	$query = mysql_query("select * FROM `wemall_user` where `uid`='".$uid."'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	if(!$row) exit("<script>alert('非法微信用户，禁止访问...');</script>");	//是否有这个记录
	$_SESSION['uid'] = $uid;
	$_SESSION['vip'] = $row['member'];
	
}/*else{
	exit("<script>alert('非法微信用户，禁止访问...');</script>");
}*/

//判断是否已经上传信息
$sql = "select * FROM `weixin` WHERE `uid`='". $_SESSION['uid'] ."'";
$query = mysql_query($sql);
$my_code = mysql_fetch_array($query);
$fansid = $my_code[id] ? $my_code[id] : 0;
$upnum = $my_code[upnum] ? $my_code[upnum] : 0;
$uptime = $my_code[uptime] ? $my_code[uptime] : 0;
$mytime = time() - $uptime;
/*if($my_code){
	$code_button = "修改二维码";
}else{*/
	$code_button = "上传二维码";	
//}
//检查登录/刷新置顶时间
if(!$_COOKIE['time'])
{
	setcookie("time",time()+(3600*24*365*10), time()+3600*24*365);	//如果没有设置登录时间  设置当前登录时间
	$time=0;	//如果没有 设置刷新时间为0
}
//----------置顶刷新------------
if($_GET['shuaxin'])
{
	$time=time()+(3600*24*365*10)-$_COOKIE['time'];		//距上次刷新的时间 称
	$xianzhitime = 60;			//设置刷新置顶限制时间，单位：秒
	$haoyou = 5;				//设置刷新置顶加好友人数限制
	//判断是否符合置顶刷新条件：加的人数和时间
	if($_COOKIE['count']>=$haoyou&&$time>=$xianzhitime){
		mysql_query("UPDATE `weixin` SET `shuaxin`=0 WHERE `shuaxin`=1") or die(mysql_error());	//找到原来刷新列表中第二个 设置为0
		mysql_query("UPDATE `weixin` SET `shuaxin`=1 WHERE `shuaxin`=2") or die(mysql_error());	//找到原来刷新列表中最新的一个，设置为1
		$sql = "UPDATE `weixin` SET `shuaxin`=2 WHERE `uid`='".$_SESSION['uid']."'";//置顶刷新设置为2
		mysql_query($sql) or die(mysql_error());									//置顶刷新设置为2
		setcookie("count",0,time()+(3600*24*365*10)); //设置加置顶刷新后加好友数为0
		setcookie("time",time()+(3600*24*365*10), time()+3600*24*365*10); //设置加置顶刷新后时间为当前时间
		$time=0;	//设置加置顶刷新后时间为当前时间
		echo '<script> alert("成功刷新");</script>';
	}elseif($time<$xianzhitime){
		$tishi="离上次刷新置顶还不到".$xianzhitime."秒，不要太频繁哦";
		qy_location($tishi,"list.php");
	}elseif($_COOKIE['count']<$haoyou){
		$tishi="为了你我他，请添加".$haoyou."个好友后刷新置顶";
		qy_location($tishi,"list.php");
	}
}

$where = '';
	//$where = $where ." and (name like '%".$_POST['search']."%' or miaoshu like '%".$_POST['search']."%' or addr like '%".$_POST['search']."%' )";
$where_arr = array();
if(!empty($_GET['search']))
	$where_arr[] = "(name like '%".$_GET['search']."%' or miaoshu like '%".$_GET['search']."%')";
if(!empty($_GET['province']))
	$where_arr[] = "prov='{$_GET['province']}'";
if(!empty($_GET['city']))
	$where_arr[] = "city='{$_GET['city']}'";
if(!empty($_GET[sex])){
	$where_arr[] = "sex='{$_GET[sex]}'";
}
$where = empty($where_arr) ? "" : "and "  .  implode(" and " , $where_arr);
?>

<!DOCTYPE html>
<html>
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" /> 
	<meta name="format-detection" content="telephone=no" /> 
	<title>超级人脉 - 优质的人脉圈子</title> 
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="js/style.css?0707" /> 
	<style type="text/css">
	ul li{ list-style:none;}
	#bottom{width: 100%;height: 46px;position: fixed;bottom: 0px;left: 0px; background: #ff4444;}
	#bottom li{width: 33%;float: left;list-style: none;text-align: center;line-height: 46px;font-size: 16px;}
	#bottom li a{display: inline-block;width: 100%;height: 100%;text-decoration: none; color:#fff;  font-family: "Microsoft YaHei;"}
#bottom2{width: 100%;height: 39px;position: fixed;z-index: 1;left: 0px;border-top: 1px solid #ccc;border-bottom: 1px solid #ccc;background: #f4f4f4;margin-top:52px;}
#bottom2 li{width: 50%;float: left;list-style: none;text-align: center;line-height: 39px;font-size: 16px;}
#bottom2 li a{display: inline-block;width: 100%;height: 100%;text-decoration: none;}
	#header .refresh1{padding: 0px 10px; height: 30px;line-height: 30px;border-radius: 5px;background: #FFFFFF;left: 10px;top:10px;position: absolute ;z-index: 2;font-size: 14px;color: #000;text-decoration: none;box-shadow: 0px 0px 3px #fff;}
	#msgshow1{width: 80%;padding: 10px 10%;color: #fff;position: fixed;z-index: 1000;color: #fff;font-size: 16px;background-color: rgba(0,0,0,0.7);display: none; padding-bottom: 10px;top: 42px;left: 0px;}
	</style> 
	<script type="text/javascript" src="js/jquery.cityselect1.js?0710"></script>
	<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
		WeixinJSBridge.call('hideOptionMenu');
	});
	var storage = window.localStorage;
	var vip = <?=$_SESSION['vip']; ?>;//
	var ctime = 1;
	var ntime = Math.round(new Date() / 1000);
	var utime = <?=$utime; ?>;
	var mytime =  ntime - (<?=$mytime; ?>); //$mytime 为剩余秒数
	var fansid = <?=$fansid; ?>;
	var upnum = <?=$upnum; ?>;
	var search = "<?=$_GET['search']; ?>";
	var province = "<?=$_GET['province']; ?>";
	var city = "<?=$_GET['city']; ?>";
	var sex = "<?=$_GET['sex']; ?>";
	$(function(){
		//执行showTime()  
		showTime();  
		$("#set_city").citySelect({nodata:"none",required:false}); 
		$(".all").click(function(){location='list.php'});
		$(".reload").click(function(){location.reload();});
	});

	//设定倒数秒数  
	var t = 20;  
	//显示倒数秒数  
	function showTime(){  
		t -= 1;  
		$(".dao").html( t );  
		if(t==0){ 
			 $.get("shua.php" , {},
                    function(data) {
              
                        if (data.sta == "err") {
                           t = 20;
						   alert("重新刷新");
							window.location.reload();
							
                        } else {
                            if (data.sta == "ok") {
                    
                                for (var i = 0; i < data.flist.length; i++) {
									var name = "down_"+i;
									$("#"+name+" .desc .name .user" ).html(data.flist[i].username);
									$("#"+name+" .headimg > img" ).attr("src" , data.flist[i].headimg);
									$("#"+name+" .desc .desc_info " ).html(data.flist[i].remark);
									$("#"+name+" .adddiv .addfans " ).attr('fansid',data.flist[i].id );
									$("#"+name+" > input " ).attr('name','fsimg'+data.flist[i].id );
									$("#"+name+" > input " ).attr('class','fscode_it'+data.flist[i].id );
									$("#"+name+" > input " ).attr('value',data.flist[i].qrcode);
                                }
                              
								t = 20;
                            } else {
								t = 20;
								alert("重新刷新");
								window.location.reload();
                            }
                        }
                    },
                    "json")
		}  
		//每秒执行一次,showTime()  
		setTimeout("showTime()",1000);  
	}  
	
    </script> 
	<!--<script type="text/javascript" src="js/qcindex1.js?07083"></script> -->
    <script>
    if (fansid) {
    var timestamp = Math.round(new Date() / 1000);
    //if (storage.getItem("refresh")) {
        if (timestamp - mytime < 600 && (vip == 1 || upnum <= 10)) {
            setTimeout("uptime()", 1000)
        }
    //}
}
function uptime() {
    var timestamp = Math.round(new Date() / 1000);
    var t = 600 - (timestamp - mytime);
    t = t < 0 ? 0 : t;
    if (t) {
        $(".refresh").html("剩余:" + t + "秒");
        setTimeout("uptime()", 1000)
    } else {
        $(".refresh").html("刷新本页")
    }
}

$(function() {
    $(".refresh1").click(function() {
        if ($("#msgshow1").css("display") == "none") {
            $("#msgshow1").show()
        } else {
            $("#msgshow1").hide()
        }
    });
    if(city !=""){
       $(".refresh1").text(city); 
   }else if(province != ""){
    $(".refresh1").text(province); 
   }
        
    $(".imgshow").click(function() {
        var timestamp = Math.round(new Date() / 1000);
        //alert(ctime);
        //alert (timestamp);
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
    $("a.addfans").live("click",
    function() {
        ctime = Math.round(new Date() / 1000);
        var fansid = $(this).attr("fansid");
        $("#showimg").attr("src", $(".fscode_it" + fansid).val());
        $("#showcode").css("display", "block")
    });
	
    //$("a.upcode").click(function() {
    //    if (!fansid) {
	//		  if (storage.getItem("addfans_num")) {
	//			  var click_num = parseInt(storage.getItem("addfans_num"));
	//			  if (click_num < 3) {
	//				  alert("亲爱的，需要加3个好友才能发布哦！");
	//				  return false
	//			  }
	//			  storage.setItem("addfans_num", 0)
		//	  } else {
		//		  storage.setItem("addfans_num", 0);
			//	  alert("亲爱的,需要加3个好友才能发布哦！");
			//	  return false
			 // }
		 // }
	 
	   /* if (!vip) {
            alert("您不是VIP会员，无法发布二维码！\n\n购买VIP会员即可发布二维码，享受主动被加的特权，结识更多人脉朋友！");
            return false
        } else {
            if (vip == 2 && !fansid) {
                if (storage.getItem("addfans_num")) {
                    var click_num = parseInt(storage.getItem("addfans_num"));
                    if (click_num < 5) {
                        alert("亲爱的,您是试用VIP，需要加5个好友才能发布哦！\n\n购买包月VIP，无需加人即可发布二维码！");
                        return false
                    }
                    storage.setItem("addfans_num", 0)
                } else {
                    storage.setItem("addfans_num", 0);
                    alert("亲爱的,您是试用VIP，需要加5个好友才能发布哦！\n\n购买包月VIP，无需加人即可发布二维码！");
                    return false
                }
            }
        }*/
    //});
    $("a.refresh").click(function() {
        if (fansid) {
           /* if (upnum >= 5 && vip == 2) {
                alert("亲爱的,您是试用VIP，每日只能置顶5次（刚发布算1次）！\n\n购买包月VIP，即可不受置顶次数的限制！");
                return false
            }
            var timestamp = Math.round(new Date() / 1000);
            if (storage.getItem("refresh")) {
                if (timestamp - mytime < 600) {
                    alert("距您上次刷新不到10分钟,请您休息会,稍后再刷新!\n\n还需等待：" + (600 - (timestamp - mytime)) + "秒！");
                    return false
                }
            }
            if (vip != 1) {
                if (storage.getItem("addfans_num")) {
                    var click_num = parseInt(storage.getItem("addfans_num"));
                    if (click_num < 3) {
                        alert("亲爱的,您是试用VIP，加3个好友后才可使用置顶刷新一次哦！\n\n购买包月VIP，无需加人即可使用置顶刷新！");
                        return false
                    }
                    storage.setItem("addfans_num", 0)
                } else {
                    storage.setItem("addfans_num", 0);
                    alert("亲爱的,您是试用VIP，加3个好友后才可使用置顶刷新一次哦！\n\n购买包月VIP，无需加人即可使用置顶刷新！");
                    return false
                }
            }*/
            $.get("up.php?a=upfans&r=" + timestamp, {},
            function(data) {
                var info = eval(data);
                if (info.sta == "ok") {
                    //storage.setItem("refresh", timestamp);
                    alert("刷新成功！");
                    window.location.href = "list.php?r=" + Math.round(new Date())
                } else {
                    if (info.sta == "time") {
                        alert("距您上次刷新不到10分钟,请您休息会,稍后再刷新!\n\n还需等待：" + (info.flist) + "秒！");
                        return false
                    } else {
                        if (info.sta == "vip") {
                            alert("刷新失败，只有VIP会员才能使用。");
                            return false
                        } else {
                            if (info.sta == "upnum") {
                                alert("亲爱的,您是试用VIP，每日只能置顶10次（新发布算1次）！\n\n购买包月VIP，即可不受置顶次数的限制！");
                                return false
                            } else {
                                alert("刷新失败，请稍后再尝试！\n\n" + info.sta);
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
    });
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
                    //document.write("/fans.php?search=" + 1 + "&province=" + getUrlParam("province") + "&city=" + getUrlParam("city") + "&sex="+ getUrlParam("sex") +"&page=" + page + "&utime=" + utime + "&r=" + Math.random())
                    $.get("fans.php?search=" + search + "&province=" + province + "&city=" + city + "&sex="+ sex +"&page=" + page + "&utime=" + utime + "&r=" + Math.random(), {},
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

    </script>
<!-- tianshi.lomedia.com.cn Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d4254b715086304dcf37fab38e56efba";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body>
	<div class="body"> 
		<div id="header"> 
			<a href="javascript:;" class="refresh1">高级筛选</a>
			<span>超级人脉</span>
			<a href="refresh.php?uid=<?=$uid?>" class="top">立即置顶</a>
		</div>
	<ul id="bottom2">
		<li><a href="list.php" ><font style="color:#ea222e">个人二维码</font></a></li>
		<li style="width:48%;border-left:1px solid #ccc"><a href="listqun.php"><font style="color:#333">微信群二维码</font></a></li>
		
	</ul>
    
		<ul class="list"> 
		<?php	//土豪显示 
			$query = mysql_query("select * FROM `weixin` where top=3 and top_time > ".time()." ORDER BY top_time ASC") or die(mysql_error());
			while($row=mysql_fetch_array($query)){
		?>
        	<li>
				<div class="headimg" style="position:relative;">
					<img src="<?=$row['photoimg']?>" /><img src="./images/tuhao.png" style=" position:absolute;right:0px; top:0px;">
				</div>
				<div class="desc">
					<span class="name"><span style="color: #999;">[<?=$row['prov']?><?=$row['city']?>]</span><?=$row['name']?></span>
					<span class="desc_info"><?=$row['miaoshu']?></span>
				</div>
				<div class="adddiv">
					<a href="javascript:;" class="addfans" fansid="<?=$row['id']?>">加好友</a>
				</div><input type="hidden" name="fsimg<?=$row['id']?>" class="fscode_it<?=$row['id']?>" value="<?=$row['codeimg']?>" />
				
			</li>
        <?php }?>
        	
		<!--<?php	//显示后台排序列表
		  $query = mysql_query("select * FROM `weixin` where listorder>0 ORDER BY listorder DESC") or die(mysql_error());
		  while($row=mysql_fetch_array($query)){
		?>

			<li>
				<div class="headimg">
					<img src="<?=$row['photoimg']?>" />
				</div>
				<div class="desc">
					<span class="name"><span style="color: #999;">[<?=$row['prov']?><?=$row['city']?>]</span><?=$row['name']?></span>
					<span class="desc_info"><?=$row['miaoshu']?></span>
				</div>
				<div class="adddiv">
					<a href="javascript:;" class="addfans" fansid="<?=$row['id']?>">加好友</a>
				</div><input type="hidden" name="fsimg<?=$row['id']?>" class="fscode_it<?=$row['id']?>" value="<?=$row['codeimg']?>" />
				
			</li>
		<?php }?>-->
        
        <?php	//随机显示
			//---------END--------
				//随机显示
				$autosql = mysql_query("select id FROM weixin where `shuaxin`=0 $where") or die(mysql_error());	//所得所有数据
				$num = mysql_num_rows($autosql);	//获取数据总量
				$autonum = mt_rand(0,$num);		//生成随机数据总量内的数字
				//$page_sql = "select * from weixin where 1=1 $where  order by id desc";	//随机查询语句
				//qy_page($page_sql,20);
				$sql="SELECT *  FROM weixin where `shuaxin`=0 $where ORDER by id DESC LIMIT $autonum,2";
				//$_id ='&';
				/*$sj1 = substr($utime ,-4);
				$sj2 = substr($utime ,-3);
				$sql="SELECT *,mod(id*{$sj1} , {$sj2}) as sjpx  FROM weixin where shuaxin=0 and listorder=0 $where ORDER by sjpx DESC LIMIT 0,3";*/
				$query=mysql_query($sql);
				$i = 0;
				while($row=mysql_fetch_array($query)){
				
				?>
			<li id="down_<?= $i?>">
				<div class="headimg">
					<img src="<?=$row['photoimg']?>" />
				</div>
				<div class="desc">
					<span class="name"><span style="color: #f00;">【<em class="dao">20</em> 排队，快加我】</span><span class="user"><?=$row['name']?></span></span>
					<span class="desc_info"><?=$row['miaoshu']?></span>
				</div>
				<div class="adddiv">	
					<a href="javascript:;" class="addfans" fansid="<?=$row['id']?>">加好友</a>
				</div><input type="hidden" name="fsimg<?=$row['id']?>" class="fscode_it<?=$row['id']?>" value="<?=$row['codeimg']?>" />
			</li> 

		<?
			$i++;
         }	
        ?>
        
		<?php //置顶平台
			$query = mysql_query("select * FROM `weixin` where top in(1,2) and top_time > ".time()." ORDER BY top DESC,top_time ASC") or die(mysql_error());
			while($row=mysql_fetch_array($query)){
		?>
        	<li>
				<div class="headimg" style="position:relative;">
					<img src="<?=$row['photoimg']?>" /><img src="./images/zhiding.png" style=" position:absolute;right:0px; top:0px;">
				</div>
				<div class="desc">
					<span class="name"><span style="color: #999;">[<?=$row['prov']?><?=$row['city']?>]</span><?=$row['name']?></span>
					<span class="desc_info"><?=$row['miaoshu']?></span>
				</div>
				<div class="adddiv">
					<a href="javascript:;" class="addfans" fansid="<?=$row['id']?>">加好友</a>
				</div><input type="hidden" name="fsimg<?=$row['id']?>" class="fscode_it<?=$row['id']?>" value="<?=$row['codeimg']?>" />
				
			</li>
        <?php }?>
        		
          
		<?php	//显示shuaxin列表
			$query = mysql_query("select * FROM `weixin` where 1=1 $where and (`shuaxin`>0 and `listorder`=0 and `top` =0 ) or (`shuaxin`>0 and `listorder`=0 and `top` != 0 and `top_time` < ".time().") ORDER BY `shuaxin` DESC,`uptime` DESC") or die(mysql_error());
			while($row=mysql_fetch_array($query)){
		?>

			<li>
				<div class="headimg">
					<img src="<?=$row['photoimg']?>" />
				</div>
				<div class="desc">
					<span class="name"><span style="color: #999;">[<?=$row['prov']?><?=$row['city']?>]</span><?=$row['name']?></span>
					<span class="desc_info"><?=$row['miaoshu']?></span>
				</div>
				<div class="adddiv">
					<a href="javascript:;" class="addfans" fansid="<?=$row['id']?>">加好友</a>
				</div><input type="hidden" name="fsimg<?=$row['id']?>" class="fscode_it<?=$row['id']?>" value="<?=$row['codeimg']?>" />
			</li>
		<?php }?>
		
		<?php	//随机显示
			//---------END--------
				//随机显示
				/*$autosql = mysql_query("select id FROM weixin where `shuaxin`=0 $where") or die(mysql_error());	//所得所有数据
				$num = mysql_num_rows($autosql);	//获取数据总量
				$autonum = mt_rand(0,$num);		//生成随机数据总量内的数字
				$page_sql = "select * from weixin where 1=1 $where  order by id desc";	//随机查询语句
				//qy_page($page_sql,20);
				$sql="SELECT *  FROM weixin where 1=1 $where ORDER by id DESC LIMIT $autonum,20";
				$_id ='&';*/
				$sj1 = substr($utime ,-4);
				$sj2 = substr($utime ,-3);
				$sql="SELECT *,mod(id*{$sj1} , {$sj2}) as sjpx  FROM weixin where 1=1 $where and shuaxin=0 and listorder=0  ORDER by sjpx DESC LIMIT 0,15";
				$query=mysql_query($sql);
				while($row=mysql_fetch_array($query)){
				?>

			<li>
				<div class="headimg">
					<img src="<?=$row['photoimg']?>" />
				</div>
				<div class="desc">
					<span class="name"><span style="color: #999;">[<?=$row['prov']?><?=$row['city']?>]</span><?=$row['name']?></span>
					<span class="desc_info"><?=$row['miaoshu']?></span>
				</div>
				<div class="adddiv">
					<a href="javascript:;" class="addfans" fansid="<?=$row['id']?>">加好友</a>
				</div><input type="hidden" name="fsimg<?=$row['id']?>" class="fscode_it<?=$row['id']?>" value="<?=$row['codeimg']?>" />
			</li> 

			<?
				}	
			?>
			
		</ul> 
		<div id="loading" class="loading" style="display:block;"></div> 
		<div id="showcode">
			<div class="imgshow">
				<span class="close"><a href="javascript:;" class="closebtn"><img src="images/closebtn.png" /></a></span>
				<img src="#" id="showimg" style="box-shadow: 0px 0px 3px #fff" ;="" />
				<div class="fontcss">
					<font style="color:#ea222e;margin-top: 3px;">1、长按识别二维码<br />2、添加时请注明来自：<b>微商圈人脉</b></font>
				</div>
			</div>
		</div>
		<ul id="bottom">
			<li><!----><a href="javascript:void();" class="refresh" ><font >刷新本页</font></a></li>
			<li><a href="../index.php?g=App&m=Index&a=member&refresh=1&etype=1" class=""><font >会员中心</font></a></li>
<!--../index.php?g=App&m=Index&a=member&refresh=1&etype=1  hufen/list.php&shuaxin=1-->
			</font></a></li>
            <li><a href="liebiao.php" class="upcode"><font ><?=$code_button;?></font></a></li>
		</ul>
	</div>
<!--刷新置顶说明-->	
	<!--<div id="msgshow">
		刷新置顶说明使用说明：
		<br /> 1、置顶刷新可以将您上传的二维码刷新到人脉列表最顶端让更多人看到您。
		<br /> 2、按钮每10分钟可使用一次。
		<br /> 4、点击本提示框关闭提示。
		<br />
	</div>-->
	<input type="hidden" name="limitnum" value="10" /> 
<!--筛选-->
	<div id="msgshow1"> 
		<form name="txinfoForm" id="cityForm" method="get" action=""> 
			<div id="set_city" style="margin-top:15px;">
				地区筛选：
				<select class="prov" name="province" style="font-size:15px;color:#000000;"><option value="">请选择</option></select> 
				<select class="city" name="city" style="font-size:15px;color:#000000;"><option value="">请选择</option></select> 
			</div> 
			<div style="margin-top:22px;font-size:16px;">
				指定性别：
				<select class="sex" name="sex" style="font-size:15px;"> <option value="1">男</option> <option value="2">女</option> <option value="0" selected="">不限</option> </select> 
			</div> 
			<div style="margin-top:22px;font-size:16px;">
				关键词筛选：
				<input type="text" value="" style="height: 25px;width:55%;" maxlength="16" name="search" placeholder="从名称和描述中筛选" /> 
			</div> 
			<div style="margin-top:15px;"></div> 
			<span>
				<input style="width:100px; margin-top:10px; height: 28px;font-size:16px;" type="submit" name="" value="切换指定" />　　
				<input style="width:100px; margin-top:10px; height: 28px;font-size:16px;" type="button" name="" class="all" value="恢复全国" />
			</span>
		</form> 
		<div style="margin-top:20px;"></div> 
	</div>  
</body>
</html>