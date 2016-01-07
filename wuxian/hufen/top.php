<?
$url =$_SERVER["SCRIPT_NAME"];
$filename= substr($url,strrpos($url,'/')+1,strlen($url)); 
$script_php = substr($filename,0,strrpos($filename,'.')); 
$roles = explode('_',$filename);

define('IN_QY',true);
define('SCRIPT',$script_php);
session_start();
require('include/common.inc.php');
require("check.php");
if($roles[0]=='admin' && $_SESSION['admin_role']!="administrator"){
	qy_alert_back('您没有浏览此页面的权限，请用与管理员联系!');
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>微信管理系统-后台管理</title>
    <link rel="stylesheet" href="css/pintuer.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/jquery.js"></script>
    <script src="js/pintuer.js"></script>
    <script src="js/respond.js"></script>
    <script src="js/admin.js"></script>
	<script src="js/common.js"></script>
	<script src="js/jquery.min.js"></script>
	<script type="text/javascript" charset="UTF-8" src="js/form/form.js"></script>
	<script type="text/javascript" charset="UTF-8" src="js/artdialog/artDialog.js?skin=brief"></script>
	<script type="text/javascript" charset="UTF-8" src="js/artdialog/plugins/iframeTools.js"></script>
	<script src="js/My97DatePicker/WdatePicker.js"></script>
    <link type="image/x-icon" href="/favicon.ico" rel="shortcut icon" />
    <link href="/favicon.ico" rel="bookmark icon" />
	<script type="text/javascript">
	function repass(){
	  var url = 'repass.php';
	  var params = $("input[name='goods_id[]']").serialize()+"&id=";
	  if(url.indexOf('?')==-1) url = url+'?'+params;
	  else url = url+'&'+params;
	  art.dialog.open(url,{id:'goods_select',title:'修改密码：',width:400,height:200,padding: '10px',lock:true,opacity:0.1});
	}
	function shipping(id){
	  var url = 'shipping.php';
	  var params = $("input[name='ids[]']").serialize()+"&id="+id;
	  if(url.indexOf('?')==-1) url = url+'?'+params;
	  else url = url+'&'+params;
	  art.dialog.open(url,{id:'goods_select',title:'发货：',width:400,height:200,padding: '10px',lock:true,opacity:0.1});
	}
	function p_shipping(){
	  var url = 'p_shipping.php';
	  var params = $("input[name='ids[]']").serialize()+"&id=";
	  if(url.indexOf('?')==-1) url = url+'?'+params;
	  else url = url+'&'+params;
	  art.dialog.open(url,{id:'goods_select',title:'批量发货：',width:400,height:200,padding: '10px',lock:true,opacity:0.1});
	}
	</script>
</head>
<body>


