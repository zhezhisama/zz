<?php
set_time_limit(0);
define('IN_QY',true);
define('SCRIPT','list');
require("include/common.inc.php");
$date = date('Y-m-d H:i:s',time()-60*60*24*30);
//echo $date;
$sql = "select * from wemall_order where pay_status=1 and time > '{$date}' order by time";
$query = mysql_query($sql);
$data = array();
while($row = mysql_fetch_array($query)){
	$data[] = $row;
	//echo strtotime($row[time])+60*60*24*60  . "<br>";
}

/*$sql = "";
foreach($data as $val){
	$limit_time = strtotime($val[time]) - 60*60*24*30;
	$limit_time = date('Y-m-d H:i:s',$limit_time);
	//echo $val[time];
	$sql = "update wemall_order set time='{$limit_time}' where id='{$val[id]}'";
	if(mysql_query($sql))
		echo "{$val[id]}--ok<br>";
	else
		echo "{$val[id]}<b color=red>error<b> <br>";
	
}
*/
foreach($data as $val){
	$limit_time = strtotime($val[time]) + 60*60*24*30;
	$sql = "update wemall_user set limit_time='{$limit_time}' where id='{$val[user_id]}'";
	if(mysql_query($sql))
		echo "{$val[user_id]}--ok<br>";
	else
		echo "{$val[user_id]}--<b style='color:red;'>error</b> <br>";	
}
$udata = array();
$sql = "select wx_info,uid from wemall_user";
$query = mysql_query($sql);

while($row = mysql_fetch_array($query)){
	$udata[$row[uid]] = json_decode($row[wx_info]);
}
$time = time()-1000;

foreach($udata as $uid => $val){
	$sql = "update weixin set prov='" . $val->province ."',city='" . $val->city . "',sex='" . $val->sex .  "',uptime='" . $time .  "' where uid='{$uid}'";
	if(mysql_query($sql)){
		echo "{$uid}---update ok<br>";
	}else{
		echo "{$uid}---update fail<br>";
	}
}
$sql = "update weixin set uptime='" . $time .  "'";
mysql_query($sql);
mysql_close();
?>