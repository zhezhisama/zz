<?php
define('IN_QY',true);
require_once("include/common.inc.php");
header("Content-type: text/html;charset=utf-8");
session_start();
if(empty($_SESSION['admin_user'])){
	qy_location('请先登陆!','login.php');
	exit;
}
if($_GET['action']=="loginout"){
	$_SESSION['admin_user']="";
	qy_location('退出成功!','login.php');
	exit;
}
?>