<?php
define('IN_QY',true);
require("include/common.inc.php");
session_start();
if(!$_SESSION['uid'])	exit("<script>alert('非法用户，禁止访问...');</script>");
//查找当前sessionid的用户
$sql = "select * FROM `weixinqun` WHERE `uid`='". $_SESSION['uid'] ."'";
$query = mysql_query($sql);
//$code_data = mysql_fetch_array($query);

$xiu = $_GET['xiu'];
$gai = $_POST['gai'];
$xu = $_GET['xu'];
for($a=0;$a<mysql_num_rows($query);$a++){
	$code_data[$a] = mysql_fetch_array($query);
}

if($_POST[wxtitle]){
	if($gai==1){
		$sql="UPDATE weixinqun SET 
		name='{$_POST["wxtitle"]}',
		photoimg='{$_POST["wximg"]}',
		codeimg='{$_POST["qrcode"]}',
		miaoshu='{$_POST["content"]}',
		prov='{$_POST["province"]}',
		city='{$_POST["city"]}',
		wxid='{$_POST["wxid"]}',
		cdate='".date('Y-m-d H:i:s')."'
		WHERE uid='{$_SESSION['uid']}' and id = '{$_POST["uuuuuid"]}'";
		mysql_query($sql);
		if(mysql_affected_rows() == 1){
			qy_close();
			echo "<script type='text/javascript'>alert('信息修改成功!信息修改不会刷新置顶');location.href='listqun.php';</script>";
			exit;
		}else{
			qy_close();
			qy_alert_back('信息修改失败!');
		}
	}else if($gai==2){
		mysql_query("UPDATE `weixinqun` SET `shuaxin2`=0 WHERE `shuaxin2`=3") or die(mysql_error());	//刚添加的刷新状态改为0
		$sql="INSERT INTO weixinqun (id,`name`,photoimg,codeimg,addr,prov,city,miaoshu,cdate,uid,wxid,shuaxin2)" .
			"VALUES(
				0,
				'{$_POST["wxtitle"]}',
				'{$_POST["wximg"]}',
				'{$_POST["qrcode"]}',
				'".$_POST["addr"]."',
				'{$_POST["province"]}',
				'{$_POST["city"]}',
				'{$_POST["content"]}',
				'".date('Y-m-d H:i:s')."',
				'".$_SESSION['uid']."',
				'".$_SESSION['wxid']."',
				'3'
				)";
			//echo $sql;exit;
		mysql_query($sql);
		if(mysql_affected_rows() == 1){
			qy_close();
			echo "<script type='text/javascript'>alert('添加成功!');location.href='listqun.php';</script>";
			exit;
		}else{
			qy_close();
			qy_alert_back('信息添加失败!');
		}
	}
}else{
	if(!$code_data){
		$sql = "select wx_info from wemall_user where uid='{$_SESSION['uid']}'";
		$row = mysql_fetch_array(mysql_query($sql));
		$udata = json_decode($row[wx_info]);
		$code_data = array(
			"name" =>  $udata->nickname,
			"sex" =>  $udata->sex,
			"photoimg" =>  $udata->headimgurl,
			"prov" =>  $udata->province,
			"city" =>  $udata->city
			);
	}	
//表单部分
?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<title>超级人脉 - 发布微信群二维码</title>
	<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.form.js?0709"></script>
	<script type="text/javascript" src="js/jquery.cityselect.js?0709"></script>
	<script type="text/javascript" src="http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js">
</script><link rel="stylesheet" type="text/css"href="js/style.css?0705">
<style type="text/css">
#bottom{width: 100%;height: 46px;position: fixed;bottom: 0px;left: 0px;border-top: 1px solid #ccc;background: #f4f4f4;}
#bottom li{width: 100%;float: left;list-style: none;text-align: center;line-height: 46px;font-size: 16px;}
#bottom li a{display: inline-block;width: 100%;height: 100%;text-decoration: none;}
.refresh{padding: 0px 10px; height: 30px;line-height: 30px;border-radius: 5px;background: #FFFFFF;left: 10px;top:6px;position: absolute ;z-index: 2;font-size: 14px;color: #ea222e;text-decoration: none;box-shadow: 0px 0px 3px #fff;}
.refresh1{padding: 5px 10px; height: 40px;line-height: 40px;border-radius: 5px;background: #FFFFFF;left: 30px;font-size: 18px;color: #ea222e;text-decoration: none;box-shadow: 0px 0px 3px #fff;}
<?
if($code_data[photoimg]) echo ".btn1{display:none;}\r\n";
if($code_data[codeimg]) echo ".btn{display:none;}\r\n";
?>

</style>
<script type="text/javascript">
document.addEventListener('weixinqunJSBridgeReady', function onBridgeReady() {
	weixinqunJSBridge.call('hideOptionMenu');
});
var uptime = 0;
var fansid = 0;
var myprovince = remote_ip_info['province']; 
var mycity = remote_ip_info['city'];
var sex = "<?=$code_data[sex]; ?>";
<?
if($code_data[prov]){
	echo "myprovince = '{$code_data[prov]}';\r\n";
}
if($code_data[city]){
	echo "mycity = '{$code_data[city]}';\r\n";
}
?>
$(function(){ 

	$("#set_city").citySelect({ 
		prov:myprovince,  
		city:mycity 
	});
	$(".sex").attr("value" , sex); 
}); 
</script>
<script type="text/javascript" src="js/qcadd.js?0711"></script>
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
<?php if($xiu==1){?>
	<div class="body">
		<div id="upheader"><a href="listqun.php" class="refresh"><b>  返回  </b></a><span style="margin-left:38%;">发布群二维码</span></div>
		<ul class="upinfo">
			<li class="">
				<b>我的头像：</b>
				<div class="btn1" style="display:none">
					<span>图片上传</span>
					<input id="fileupload1" type="file" name="himg">
				</div>
				<div class="progress1" style="display:none">
					<span class="bar1"></span><span class="percent1">0%</span>
				</div>
				<div class="files1" style="display:none"></div>
				<div id="showimg1">
				<?
				if($code_data[$xu][photoimg]){
				?>	
					<span>
						<img src='<?=$code_data[$xu][photoimg]; ?>' width='100' class='qcimg'>
						<span class='delimg1' rel='<?=$code_data[photoimg]; ?>'>更改</span>
					</span>
				<?
					}
				?>
				</div>
			</li>
			<li class="">
				<b>微信群二维码：</b>
				<div class="btn">
					<span>图片上传</span>
					<input id="fileupload" type="file" name="qcimg">
				</div>
				<div class="progress">
					<span class="bar"></span><span class="percent">0%</span>
				</div>
				<div class="files" style="display:none"></div>
				<div id="showimg">
				<?
				if($code_data[$xu][codeimg]){
				?>	
					<span>
						<img src='<?=$code_data[$xu][codeimg]; ?>' width='100' class='qcimg'>
						<span class='delimg' rel='<?=$code_data[codeimg]; ?>'>更改</span>
					</span>
				<?
					}
				?>
				</div>
				<div style="margin-top:18px;"></div>
				<span style="font-size:14px;color:#ea222e;">提温馨提醒：群二维码有效期只有7天，每7天需要更新一次！您可以同时发布个人微信二维码和微信群二维码，当然您也可以只发布个人微信而不发布微信群！</span>
			</li>
		</ul>
		<form action="add2.php" method="post" id="codeform" enctype="multipart/form-data">
			<ul class="upinfo">
				<li class=""><b>昵称：</b><input type="text" value="<?=$code_data[$xu][name]; ?>" name="wxtitle" placeholder="填写我的昵称"/></li>
				<!--<li class=""><b>微信号：</b><input type="text" value="<?=$code_data[$xu][wxid]; ?>" name="wxid" placeholder="填写我的微信号"/></li>-->
				<!--<li class=""  style="width:30%;float:left;"><b>性别：</b>
					<div style="margin-top:10px;font-size:16px;">
						<select class="sex" name="sex" style="font-size:14px;">
							<option value="1" selected>男</option>
							<option value="2">女</option>
							<option value="0">保密</option>
						</select>
					</div>-->
					<li class="" style="width:60%;float:left;"><b>所在城市：</b>
						<div id="set_city" style="margin-top:10px;">
							<select class="prov" name="province" style="font-size:14px;"></select>
							<select class="city" name="city" style="font-size:14px;"></select>
						</div>
					</li>
					<li class=""><b>描述：</b><textarea name="content" id="content" placeholder="填写描述信息" style="min-height:50px;"><?=$code_data[$xu][miaoshu]; ?></textarea></li>
					<li class=""><label style="font-size: 13px;line-height: 30px;">提交即接受<a href="about/user_contract.html" style="color:#ea222e;">《平台服务许可协议》</a></label></li>
					<li class="subinfo"><a href="javascript:;" class="upbtn">立即上传</a></li>
				</ul>
				<input type="hidden" name="wximg" value="<?=$code_data[$xu][photoimg]; ?>" />
				<input type="hidden" name="qrcode" value="<?=$code_data[$xu][codeimg]; ?>" />
				<input type="hidden" name="wximg_upid" value="" />
				<input type="hidden" name="qrcode_upid" value="" />
                <input type="hidden" name="uuuuuid" value="<?=$code_data[$xu]['id']; ?>" />
                <input type="hidden" name="gai" value="1" />
			</form>
			<!-- style="margin-left:24px;"><a href="listqun.php" class="refresh1"><b><< 返回</b></a></div-->
			<div style="margin-top:18px;"></div>
		</ul>
	</div>
<?php }else if($xiu==2){?>
	<div class="body">
		<div id="upheader"><a href="listqun.php" class="refresh"><b>  返回  </b></a><span style="margin-left:38%;">发布群二维码</span></div>
		<ul class="upinfo">
			<li class="">
				<b>我的头像：</b>
				<div class="btn1" style="display:none">
					<span>图片上传</span>
					<input id="fileupload1" type="file" name="himg">
				</div>
				<div class="progress1" style="display:none">
					<span class="bar1"></span><span class="percent1">0%</span>
				</div>
				<div class="files1" style="display:none"></div>
				<div id="showimg1">
				
					<span>
						<span class='delimg1' rel='<?=$code_data[0][photoimg]; ?>'>更改</span>
					</span>
				
				</div>
			</li>
			<li class="">
				<b>微信群二维码：</b>
				<div class="btn">
					<span>图片上传</span>
					<input id="fileupload" type="file" name="qcimg">
				</div>
				<div class="progress">
					<span class="bar"></span><span class="percent">0%</span>
				</div>
				<div class="files" style="display:none"></div>
				<div id="showimg">
				<?
				if($code_data[0][codeimg]){
				?>	
					<span>
						
						<span class='delimg' rel='<?=$code_data[0][codeimg]; ?>'>更改</span>
					</span>
				<?
					}
				?>
				</div>
				<div style="margin-top:18px;"></div>
				<span style="font-size:14px;color:#ea222e;">提温馨提醒：群二维码有效期只有7天，每7天需要更新一次！您可以同时发布个人微信二维码和微信群二维码，当然您也可以只发布个人微信而不发布微信群！</span>
			</li>
		</ul>
		<form action="add2.php" method="post" id="codeform" enctype="multipart/form-data">
			<ul class="upinfo">
				<li class=""><b>昵称：</b><input type="text" value="<?=$code_data[0][name]; ?>" name="wxtitle" placeholder="填写我的昵称"/></li>
				<!--<li class=""><b>微信号：</b><input type="text" value="<?=$code_data[0][wxid]; ?>" name="wxid" placeholder="填写我的微信号"/></li>-->
				<!--<li class=""  style="width:30%;float:left;"><b>性别：</b>
					<div style="margin-top:10px;font-size:16px;">
						<select class="sex" name="sex" style="font-size:14px;">
							<option value="1" selected>男</option>
							<option value="2">女</option>
							<option value="0">保密</option>
						</select>
					</div>-->
					<li class="" style="width:60%;float:left;"><b>所在城市：</b>
						<div id="set_city" style="margin-top:10px;">
							<select class="prov" name="province" style="font-size:14px;"></select>
							<select class="city" name="city" style="font-size:14px;"></select>
						</div>
					</li>
					<li class=""><b>描述：</b><textarea name="content" id="content" placeholder="填写描述信息" style="min-height:50px;"></textarea></li>
					<li class=""><label style="font-size: 13px;line-height: 30px;">提交即接受<a href="about/user_contract.html" style="color:#ea222e;">《平台服务许可协议》</a></label></li>
					<li class="subinfo"><a href="javascript:;" class="upbtn">立即上传</a></li>
				</ul>
				<input type="hidden" name="wximg" value="<?=$code_data[0][photoimg]; ?>" />
				<input type="hidden" name="qrcode" value="<?=$code_data[0][codeimg]; ?>" />
				<input type="hidden" name="wximg_upid" value="" />
				<input type="hidden" name="qrcode_upid" value="" />
                <input type="hidden" name="gai" value="2" />
			</form>
			<!-- style="margin-left:24px;"><a href="listqun.php" class="refresh1"><b><< 返回</b></a></div-->
			<div style="margin-top:18px;"></div>
		</ul>
	</div>
<?php }?>
</body>
</html>
<?
}
?>