<?php

//防止恶意调用

if(!defined('IN_QY')){

	exit('Access denied!');

}

//设置字符集编码

header('Content-Type:text/html; charset=utf-8');

//转换硬路径常量

define('QY_ROOT',substr(dirname(__FILE__),0,-7));

//拒绝PHP低版本

if(PHP_VERSION<'4.1.0'){

	exit('Version is to Low!');

}
require QY_ROOT.'../Public/Conf/config.php';
require QY_ROOT.'include/global.func.php';

require QY_ROOT.'include/mysql.func.php';

//连接数据库



?>