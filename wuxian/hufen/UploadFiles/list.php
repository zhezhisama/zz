<?php

define('IN_QY',true);

define('SCRIPT','list');

require("include/common.inc.php");

session_start();



//判断是否为微信用户

if($_GET['uid']){

	$uid = $_GET['uid'];

	$query = mysql_query("select * FROM `wemall_user` where `uid`='".$uid."' AND `member`=1") or die(mysql_error());

	$row = mysql_fetch_array($query);

	if(!$row) exit("<script>alert('非法微信用户，禁止访问...');</script>");	//是否有这个记录

	$_SESSION['uid'] = $uid;

}else{

	exit("<script>alert('非法微信用户，禁止访问...');</script>");

}	



if($_SESSION['time'])

{

	$time=time()+(3600*24*365*10)-$_SESSION['time'];

	echo '相差时间为：'.$time;

}else{

	$_SESSION['time']=time()+(3600*24*365*10);

	echo '创建的时间为'.$_SESSION['time'];

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

<title>众联星空-疯狂交友</title>

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

			<h2 align="center">众联星空-疯狂交友</h2>

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

				<?php

				$myid = $_GET['myid'];

				if($myid){

					$myquery = mysql_query("select * FROM weixin where id=$myid") or die(mysql_error());

					$myrow=mysql_fetch_array($myquery);

				?>

				<li>

					<span class="left pic " style="border-radius:50%;overflow:hidden;">

						<img src="<?=$myrow['photoimg']?>">

					</span>

					<div class="list_txt">

						<span class="more right" style="margin-top:10px;margin-right:-25px">

							<div  data-img="<?=$myrow['codeimg']?>" data-content="" value="加为好友" class="login_btn" style="width:80px;height:30px;line-height:30px; border-radius:10px;text-align:center">加为好友</div>

						</span>

						<h6 style="overflow:hidden;height:30px;line-height:30px;"><?=$myrow['name']?></h6>

        				<p style="padding-left:10px;overflow: hidden;max-width:60%;color:#a09f9f; font-size:12px; line-height:20px;height:40px"><?=$myrow['miaoshu']?></p>

					</div>

				</li>



				<?php

				}

				//随机显示

				$autosql = mysql_query("select id FROM weixin where 1=1 $where") or die(mysql_error());	//所得所有数据

				$num = mysql_num_rows($autosql);	//获取数据总量

				$autonum = mt_rand(0,$num);		//生成随机数据总量内的数字

				$page_sql = "select * from weixin where 1=1 $where  order by id desc";	//随机查询语句

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

			</ul>

		</div>

	</div>

</div>



<div data-role="widget" data-widget="nav4" class="nav4">

	<nav>

		<div id="nav4_ul" class="nav_4">

			<ul class="box">

			<li style="width:33%">

				<a href='javascript:window.location.reload()' class=""><span><strong>๑换一批</strong></span></a>

			</li>

			<li style="width:33%">

				<a href=''><span>Ψ刷新置顶</span></a>

			</li>

			<li style="width:33%">

				<a href="add.php" class=""><span>☺我的信息</span></a>

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