<?php

//微云莱网络 旺旺ID: jie6059	 有问题请联系QQ 53867784   提醒，该文件一定不要用记事本编辑，可用notepad++

	header('Content-Type:text/html; charset=utf-8');

	error_reporting(E_ALL & ~E_NOTICE);

	
	define('DB_HOST', '127.0.0.1');
	define('DB_USER', 'root');
	define('DB_PWD', 'Dazhou123');
	define('DB_NAME', 'tianshiweishang');
	define('DB_PREFIX', 'wemall_');
	define ('FENURL','http://www.famsoon.com.cn/hufen');		//交友列url地址，不要加/

	

	$conn = @mysql_connect(DB_HOST, DB_USER, DB_PWD) or die('数据库链接失败：'.mysql_error());

	

	@mysql_select_db(DB_NAME) or die('数据库错误：'.mysql_error());

	

	@mysql_query('SET NAMES UTF8') or die('字符集错误：'.mysql_error());

?>