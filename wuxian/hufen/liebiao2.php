<?php
session_start();
$uid = $_SESSION['uid'];
$mysqli = new mysqli("localhost","root","Dazhou123","tianshiweishang");
$mysqli->query("set names utf8");
$res = $mysqli->query("select * from weixinqun where uid = '".$uid."'");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>超级人脉 - 发布我的名片</title>
<style>
*{
	margin:0;
	padding:0;
}
ul li{
	list-style:none;
	width:47%;
	height:160px;
	border:1px dashed #ccc;
	margin-left:1%;
	margin-right:1%;
	float:left;
}
</style>
</head>

<body>
<ul>
	<?php $i=0; while($row = $res->fetch_assoc()){?>
	<li>
    	<div style="text-align:center;"><a href="add2.php?xiu=1&&xu=<?php echo $i;?>"><img src="<?php echo $row['codeimg']?>" style="height:130px; max-width:100%;"  /></a></div>
        <div style="font-size:12px; height:30px; line-height:30px;"><center><a href="add2.php?xiu=1&&xu=<?php echo $i;?>" style="color:#000;"><?php echo $row['name']?></a></center></div>
    </li>
    <?php $i++;}?>
    <li>
    	<div style="text-align:center; line-height:150px; font-size:50px;"><a href="add2.php?xiu=2&&xu=0" style="color:#000;">+</a></div>
    </li>
</ul>
</body>
</html>
