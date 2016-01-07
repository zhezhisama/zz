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
}else{
	exit("<script>alert('非法微信用户，禁止访问...');</script>");
}

//判断是否已经上传信息
$sql = "select * FROM `weixin` WHERE `uid`='". $_SESSION['uid'] ."'";
$query = mysql_query($sql);
$my_code = mysql_fetch_array($query);
$fansid = $my_code[id] ? $my_code[id] : 0;
$upnum = $my_code[upnum] ? $my_code[upnum] : 0;
$uptime = $my_code[uptime] ? $my_code[uptime] : 0;
$mytime = time() - $uptime;
if($my_code){
	$code_button = "更新二维码";
}else{
	$code_button = "上传二维码";	
}
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
	<title>真爱网 - 最优质的人脉圈子</title> 
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="js/style.css?0707" /> 
	<style type="text/css">
	#bottom{width: 100%;height: 46px;position: fixed;bottom: 0px;left: 0px;border-top: 1px solid #ccc;background: #f4f4f4;}
	#bottom li{width: 33%;float: left;list-style: none;text-align: center;line-height: 46px;font-size: 16px;}
	#bottom li a{display: inline-block;width: 100%;height: 100%;text-decoration: none;}

	#header .refresh1{padding: 0px 10px; height: 30px;line-height: 30px;border-radius: 5px;background: #FFFFFF;left: 10px;top:5px;position: absolute ;z-index: 2;font-size: 14px;color: #000;text-decoration: none;box-shadow: 0px 0px 3px #fff;}
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
		$("#set_city").citySelect({nodata:"none",required:false}); 
		$(".all").click(function(){location='list.php'});
		$(".reload").click(function(){location.reload();});
	}); 
	</script> 
	<script type="text/javascript" src="js/qcindex1.js?07083"></script> 
</head> 
<body> 
	<div class="body"> 
		<div id="header"> 
			<a href="javascript:;" class="refresh1">高级筛选</a>
			<span>真爱网</span>
			<a href="javascript:;" class="refresh">置顶刷新</a> 
		</div> 
		<ul class="list"> 
			

				<?php
				//显示shuaxin列表
				$query = mysql_query("select * FROM `weixin` where `shuaxin`>0 ORDER BY `shuaxin` DESC") or die(mysql_error());
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


				<?php
				}
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
				$sql="SELECT *,mod(id*{$sj1} , {$sj2}) as sjpx  FROM weixin where shuaxin=0 $where ORDER by sjpx DESC LIMIT 0,15";
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
					<font style="color:#ea222e;margin-top: 3px;">1、长按识别二维码<br />2、添加时请注明来自：<b>真爱网</b></font>
				</div>
			</div>
		</div>
		<ul id="bottom">
			<li><a href="javascript:void();" class="reload"><font style="color:#ea222e">刷新本页</font></a></li>
			<li style="width:33%;border-left:1px solid #ccc"><a href="http://tianshi.lomedia.com.cn/index.php?g=App&m=Index&a=index"><font style="color:#00CC00">立即购买</font></a></li>
			<li style="width:33%;border-left:1px solid #ccc"><a href="add.php" class="upcode"><font style="color:#333"><?=$code_button;?></font></a></li>
			
		</ul>
	</div>
<!--刷新置顶说明-->	
	<div id="msgshow">
		按钮使用说明：
		<br /> 1、置顶刷新可以将您上传的二维码刷新到人脉列表最顶端让更多人看到您。
		<br /> 2、按钮每10分钟可使用一次。
		<br /> 3、只有VIP会员才能使用。
		<br /> 4、点击本提示框关闭提示。
		<br />
	</div>
	<input type="hidden" name="limitnum" value="10" /> 
<!--筛选-->
	<div id="msgshow1"> 
		<form name="txinfoForm" id="cityForm" method="get" action=""> 
			<div id="set_city" style="margin-top:15px;">
				地区筛选：
				<select class="prov" name="province" style="font-size:16px;color:#000000;"><option value="">请选择</option></select> 
				<select class="city" name="city" style="font-size:16px;color:#000000;"><option value="">请选择</option></select> 
			</div> 
			<div style="margin-top:22px;font-size:16px;">
				指定性别：
				<select class="sex" name="sex" style="font-size:16px;"> <option value="1">男</option> <option value="2">女</option> <option value="0" selected="">不限</option> </select> 
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