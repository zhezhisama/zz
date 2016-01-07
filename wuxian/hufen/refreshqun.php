<?php 
define('IN_QY',true);
require("include/common.inc.php");
session_start();
$uid = $_GET['uid'] ? $_GET['uid'] : $_SESSION['uid'];
//判断是否为微信用户并设置用户sessionID
if($uid){
	//查看用户信息
	$query = mysql_query("select * FROM `wemall_user` where `uid`='".$uid."'") or die(mysql_error());
	$row = mysql_fetch_array($query);
	if(!$row) exit("<script>alert('请重试');</script>");	//是否有这个记录
	$_SESSION['uid'] = $uid;
	//$_SESSION['vip'] = $row['member'];
	$price = $row['price'];
	/*if($_SESSION['vip'] == 0){
		exit("<script>alert('您还没有成为代理');location.href='list.php&uid=".$uid."'</script>");//是否是会员
	}*/
	//查看时否上传二维码
	
	$sql = "select * FROM `weixinqun` WHERE `uid`='". $uid ."'";
	$query = mysql_query($sql) or die(mysql_error());
	$weixin = mysql_fetch_array($query);
	if(!$weixin) exit("<script>alert('请先上传二维码');location.href='listqun.php'</script>");	//是否有这个记
}

	$now = time();
	$top_time = mysql_query("SELECT min(top_time) FROM `weixinqun` where `top` = 3 and `top_time` >= $now ");
	$fir_time =  mysql_fetch_array($top_time);
	$count_sql = mysql_query("SELECT count(id) as num FROM `weixinqun` where `top` = 3 and `top_time` >= $now ");
	$count = mysql_fetch_array($count_sql);
	
	if($count['num'] >= 3){
		$fir_str ="今日土豪置顶已满，预计".date("Y-m-d H:i:s",$fir_time[0])."可抢";
	}else{
		$fir_str = "还有".(3-$count['num'])."个土豪置顶席位。";
	}

if($_POST){
	
	//提交数据
	$top = $_POST['top'];
	
	//查看是否有足够的积分
	if($top == 1){
		$need_price = 10;
		$top_time = $now + 3600*24;
	}elseif($top == 2){
		$need_price = 60;
		$top_time = $now + 3600*24*7;
	}elseif($top == 3){
		$need_price = 99;
		$top_time = $now + 3600*24;
	}
	
	if($now < $weixin['top_time']){
		exit("<script>alert('现在你还存在你置顶状态，请等待置顶时间结束。');location.href='refreshqun.php'</script>");
	}
	
	if($price < $need_price){
		exit("<script>alert('对不起您没有足够的积分，请充值');location.href='refreshqun.php'</script>");
	}
	
	if($count['num'] >= 3 && $top == 3){
		exit("<script>alert('对不起，现在没有土豪置顶名额');location.href='refreshqun.php'</script>");
	}
	
	$sql="UPDATE weixinqun SET 
		top='".$top."',
		top_time='".$top_time."'
		WHERE uid='{$uid}'";
	mysql_query($sql);
	if(mysql_affected_rows() == 1){
		mysql_query("UPDATE `wemall_user` SET `price`= price-$need_price WHERE `uid`='".$uid."'") or die(mysql_error());
		qy_close();
		echo "<script type='text/javascript'>alert('置顶成功!');location.href='listqun.php';</script>";
		exit;
	}else{
		qy_close();
		qy_alert_back('置顶失败!');
	}
	
}



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>超级人脉 - 置顶刷新</title>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js?0709"></script>
<script type="text/javascript" src="js/jquery.cityselect.js?0709"></script>
<script type="text/javascript" src="http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js"></script>

<!--<link rel="stylesheet" type="text/css"href="js/style.css?0705">-->
<style type="text/css">

.czsubmit{background-color: #eb3c5a;padding: 8px 20px;font-size: 18px;letter-spacing: 2px; text-decoration: none;border: 1px solid #eb3c5a; border-radius: 5px; color: #ffffff;display: block; cursor: pointer;text-align: center;margin-top:20px;}
.radio_inp{display:block;height:30px;line-height:30px;}
.cbtn{padding:5px 5px;border-radius:4px;}
</style>
<link href="../Application/Tpl/App/default/Public/Static/css/foods.css?t=333" rel="stylesheet">

<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.call('hideOptionMenu');
});
</script>
</head>
<body class="sanckbg mode_webapp">
	<div id="member-container" style="display: block;">
		
		<div class="div_header">
        	<div style=" height:8px;"></div>
			<span style='float:left;margin-left:10px;margin-right:10px;'>
				<?php
					$wx_info = json_decode($row['wx_info'],true);
					$img = !empty($wx_info['headimgurl'])?$wx_info['headimgurl']:'../Application/Tpl/App/default/Public/Static/images/defult.jpg';
					echo "<img src='".$img."' width='70px;' height='70px;' style='border-radius:50%; border:5px solid rgba(255,255,255,.6)'>";
				?>
			</span>
			<span class="header_right">
            <div style="height:8px;"></div>
				<div class="header_l_di">
					<span  style=" color:#fff !important; font-size:14px; margin-top:10px;">昵称：<?= $wx_info['nickname'];?></span>&nbsp;&nbsp;
                    <span style=" display:none;">会员ID号：<?=$row['id'] ?></span>
		
				</div>
				<div>	
                <span style=" color:#fff !important; font-size:14px;">关注时间：<?= date('Y-m-d',$wx_info['subscribe_time']);?></span>
                </div>
				
			</span>
		</div>
      <div id="tx-container" style="display: block !important;  padding-top:5px;" >  
        <section class="order">
			
            <form name="reform" id="reform" method="post" action="refreshqun.php" >
				<div class="contact-info" style="margin-top:0px; padding-top:0px;">
					<ul>
                        <li class="jifen_show">您当前剩余积分：<span><?=$row['price'] ?></span></li>
						<li>
							<table style="padding: 0; margin: 0; width: 100%;">
								<tbody>
									<tr>
                                    	<td width="100px" align="center" valign="top" ><span style="height:30px;line-height:30px;display:block;margin-top:6px;color:#000;">选择置顶时间:</span></td>
										<td style="height:100px;" >
											
                                              	<label class="radio_inp" style="color:#eb3c5a;"><input type="radio" name="top" value="3" />土豪置顶99积分/天（土豪置顶）</label>
                        						<label  class="radio_inp"><input type="radio" name="top" value="1" checked/>10积分/天</label>
                   								<label class="radio_inp"><input type="radio" name="top" value="2" />60积分/周</label>
											
                                        </td>
									</tr>

									<tr>
                                    	<td colspan="2" style="font-size:14px; color:#f00;text-align:center;"><?=$fir_str?></td>
                                    </tr>
								</tbody>
							</table>

							<div class="footReturn">
                                <a class="submit" href="javascript:;"  onclick="document.getElementById('reform').submit();">置&nbsp;&nbsp;&nbsp;&nbsp;顶</a>
                                <a class="czsubmit" href="/index.php?g=App&m=Index&a=cz" >充&nbsp;&nbsp;&nbsp;&nbsp;值</a>
                            
							</div>

						</li>
					</ul>
				</div>
			</form>
		</section>
        <style>
        .round li b{width:40%;display:inline-block;overflow:hidden;text-align:center;vertical-align:middle;padding:5px 0px;}
		
		.round li b:nth-child(3) {width:20%;}
		.oredr_btn{display: block; padding: 2px 10px ;border-radius:4px;color:#fff;text-align:center;}
	
        </style>
        <ul class="round"  style='color:#000;font-size:12px;'>
			 <li><b>置顶情况</b><b>结束时间</b><b>状态</b></li>
             <?php if($weixin['top'] != 0){ ?>
             <li><b><?php if($weixin['top'] == 1 ){ echo "置顶1天"; }elseif($weixin['top'] == 2){echo "置顶1周"; }elseif($weixin['top'] == 3){echo "土豪置顶1天"; }?></b><b><?php  echo date("Y-m-d H:i:s",$weixin['top_time'])?></b><b><?php if(time() > $weixin['top_time'] ){?><span class="oredr_btn" style="background-color:#ccc;">已过期</span><?php }else{?><span class="oredr_btn" style="background-color:#56d176;">置顶中</span><?php }?></b></li>	
             <?php }?>	
                <!--<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
					<tr>
						<td class="cc" width="40%">置顶情况</td>
						<td class="cc" width="30%">结束时间</td>
						<td class="cc" width="30%">状态</td>
					</tr>
                    
					<tbody>
				<?php if($weixin['top'] != 0){ ?>
						<tr>
						<td><?php if($weixin['top'] == 1 ){ echo "置顶1天"; }elseif($weixin['top'] == 2){echo "置顶1周"; }elseif($weixin['top'] == 3){echo "土豪置顶1天"; }?></td>
						<td class="cc"><?php  echo date("Y-m-d H:i:s",$weixin['top_time'])?></td>
						<td class="cc"><?php if(time() > $weixin['top_time'] ){?><span class="cbtn" style="background-color:#ccc;">已过期</span><?php }else{?><span class="cbtn" style="background-color:#56d176;">置顶中</span><?php }?></td>
						</tr>
				<?php }?>	
					</tbody>
                    
                    
				</table>-->
		</ul>
       </div>     
    </div>    
</body>
</html>