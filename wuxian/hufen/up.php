<?php
define('IN_QY',true);
require("include/common.inc.php");
session_start();
if(!$_SESSION['uid']) die(json_encode(array('sta' => "非法登录")));
//if(!$_SESSION['vip']) die(json_encode(array('sta' => "vip")));
$sql = "select * FROM `weixin` WHERE `uid`='". $_SESSION['uid'] ."'";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);

if($row[uptime] + 600 > time()) die(json_encode(array('sta' => "time","flist"=>$row[uptime] + 600 - time())));

//if($row[upnum] >= 10 and $_SESSION['vip']==2 and date("d") ==  date("d" , $row[uptime]))  die(json_encode(array('sta' => "upnum")));
//置顶刷新次数-1 一天三次
mysql_query("UPDATE `weixin` SET `shuaxin`=shuaxin-1 WHERE `shuaxin`>0") or  die(json_encode(array('sta' => "sql error")));
//刷新次数
$num =  date("d") ==  date("d" , $row[uptime]) ? $row[upnum] + 1 : 1;
$time = time();	

mysql_query("UPDATE `weixin` SET `shuaxin`=3,uptime='{$time}',upnum='{$num}' WHERE uid = '{$_SESSION[uid]}'") or  die(json_encode(array('sta' => "sql error")));

die(json_encode(array('sta' => "ok")));
?>