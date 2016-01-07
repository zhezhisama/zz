<?php
define('IN_QY',true);
define('SCRIPT','list');
require("include/common.inc.php");
session_start();
if(!$_COOKIE['sid']){
	setcookie("sid",session_id(),time()+(3600*24*365*10));
}

$where = '';
if($_POST['search']){
	$where = $where ." and (name like '%".$_POST['search']."%' or miaoshu like '%".$_POST['search']."%' or addr like '%".$_POST['search']."%' )";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="Cache-Control" content="must-revalidate,no-cache">
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<title>微信</title>
<link type="text/css" href="css/menu.css" rel="stylesheet">
<link type="text/css" href="css/phone.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
	function isWeiXin(){ 
		var ua = window.navigator.userAgent.toLowerCase(); 
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){ 
			return true; 
		}else{ 
			return false; 
		} 
	}
	function go(){
		$("#form1").submit();
	}
	function preview(num){
		$("#pageNo").val(num);
		$("#form1").submit();
	}
	function next(num){
		$("#pageNo").val(num);
		$("#form1").submit();
	}

	if(!isWeiXin()){
		//	window.location = "http://121.40.150.127";
	}
	$(function () {
		
		$(".login_btn").click(function () {
			var id = $("#count").val();
			$("#desc").html($(this).attr("data-content"));
			$("#img").attr("src",$(this).attr("data-img"));
			$(".modal").show();

			$.post("data_index.php?count="+id,  function(data){ 
				$("#count").val(Number(id) + 1);
			});
			
		});
		$(".modal").on("click",function(){
			$(this).hide();
		});
	});
</script>
</head>
<body>
<div class="views">
	<div class="view view-main">
		<header class="navbar header">
			<h2 align="center">微商</h2>
		</header>
		<form id="form1" action="list.php" method="post">
			<input type="hidden" id="pageNo" name="pageNo" value="1">
			<div class="search">
				<input type="text" name="search" id="search" value="" class="intext" placeholder="搜索">
				<a class="search-btn" href="javascript:go();"></a>
			</div>
		</form>
		<input  type="hidden" value="<? if($_COOKIE['count']){echo $_COOKIE['count'];}else{echo 1;}?>" name="count" id="count">
		<div class="list1">
			<ul>
				<?
				$autosql = mysql_query("select * FROM weixin where 1=1 $where") or die(mysql_error());
				$autonum = mysql_num_rows($autosql);
				
				$page_sql = "select * from weixin where 1=1 $where  order by id desc";
				//qy_page($page_sql,20);
				$sql="SELECT *  FROM weixin where 1=1 $where ORDER by id DESC LIMIT $autonum,20";
				$_id ='&';
				
				$query=mysql_query($sql);
				while($row=mysql_fetch_array($query)){
				?>
				<li>
					<span class="left pic " style="border-radius:50%;overflow:hidden;">
						<img src="<?=$row['photoimg']?>">
					</span>
					<div class="list_txt">
						<span class="more right" style="margin-top:10px;margin-right:-25px">
							<div  data-img="<?=$row['codeimg']?>" data-content="" value="加为好友" class="login_btn" style="width:80px;height:30px;line-height:30px; border-radius:10px;text-align:center">加为好友</div>
						</span>
						<h6 style="overflow:hidden;height:30px;line-height:30px;"><?=$row['name']?></h6>
        				<p style="padding-left:10px;overflow: hidden;max-width:60%;color:#a09f9f; font-size:12px; line-height:20px;height:40px"><?=$row['miaoshu']?></p>
					</div>
				</li>
				<?
				}	
				?>
				<li>
					<?=qy_paging3()?>
				</li>
			</ul>
		</div>
	</div>
</div>

<div data-role="widget" data-widget="nav4" class="nav4">
	<nav>
		<div id="nav4_ul" class="nav_4">
			<ul class="box">
			<li style="width:50%">
				<a href="list.php" class=""><span style="color:#ea5505">疯狂交友</span></a>
			</li>
			<li style="width:50%">
				<a href="add.php" class=""><span>上传二维码</span></a>
			</li>
			</ul>
		</div>
	</nav>
</div>

<div class="modal">
	<div style="margin:20px auto;">
		<div id="desc" style="margin:0 auto;color:#fff;text-align:center;"></div>
		<div style="margin:10px auto;">
		<img id="img" src="" style="display: block;margin:0px auto;width:75%;height:75%;">
		</div>
	</div>
</div>
</body>
</html>