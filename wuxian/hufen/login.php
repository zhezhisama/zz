<?php
define('IN_QY',true);
require('include/common.inc.php');
session_start();
if($_GET['action']=='login'){
	//qy_check_code($_POST['vcode'],$_SESSION['vcode']);
	$username=$_POST['username'];
	$bl='lyk';
	$password=md5(md5($_POST['password'].$bl));
	if(isset($username) && isset($password)){
		$sql="SELECT * FROM admin WHERE userid='$username' AND password='$password'";
		$query=mysql_query($sql);
		$row=mysql_fetch_array($query);
		if($row){
			$_SESSION['admin_user']=$row['userid'];
			$_SESSION['admin_name']=$row['username'];
			$_SESSION['admin_role']=$row['role'];
			$_SESSION['admin_id']=$row['id'];
			$_SESSION['lastime']=$row['lastlog_time'];
			$_SESSION['lastip']=$row['lastlog_ip'];	
			$ip=$_SERVER['REMOTE_ADDR'];// 获取客户端IP
			$l_sql="UPDATE admin SET lastlog_time='".date('Y-m-d H:i:s')."',lastlog_ip='".$ip."' WHERE userid='$username'";
			mysql_query($l_sql);

			qy_close();
			qy_location('登陆成功!','orderlist.php');
		}else{
			qy_close();
			qy_location('用户名或密码错误，请重新登陆！','login.php');
		}
	}
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>单独后台登录</title>
    <link rel="stylesheet" href="css/pintuer.css">
    <link rel="stylesheet" href="css/admin.css">
    <script src="js/jquery.js"></script>
    <script src="js/pintuer.js"></script>
    <script src="js/respond.js"></script>
    <script src="js/admin.js"></script>
    <link type="image/x-icon" href="/favicon.ico" rel="shortcut icon" />
    <link href="/favicon.ico" rel="bookmark icon" />
</head>
<body >
<div class="container" >
    <div class="line">
        <div class="xs6 xm4 xs3-move xm4-move" >
            <br /><br />

            <br /><br />
            <form action="?action=login" method="post">
            <div class="panel" >
                <div class="panel-head" align="center"><strong>单独后台管理</strong></div>
                <div class="panel-body" style="padding:30px;">
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="text" class="input" name="username" placeholder="登录账号" data-validate="required:请填写账号,length#>=5:账号长度不符合要求" />
                            <span class="icon icon-user"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="field field-icon-right">
                            <input type="password" class="input" name="password" placeholder="登录密码" data-validate="required:请填写密码,length#>=8:密码长度不符合要求" />
                            <span class="icon icon-key"></span>
                        </div>
                    </div>
             <!--       <div class="form-group">
                        <div class="field">
                            <input type="text" class="input" name="vcode" placeholder="填写右侧的验证码" data-validate="required:请填写右侧的验证码" />
                            <img src="code.php" width="80" height="32" class="passcode" id="vcode" onclick="document.getElementById('vcode').src='code.php?'+Math.random();"/>
                        </div>
                    </div>
			-->	
                </div>
                <div class="panel-foot text-center"><button class="button button-block bg-main text-big">立即登录后台</button></div>
            </div>
            </form>
        </div>
    </div>
</div>
	<div align="center" style="padding-top:100px">
	
	</div>
</body>
</html>