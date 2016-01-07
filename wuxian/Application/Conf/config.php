<?php
$arr1 =  array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_PORT'   => '3306', // 端口
	'DB_CHARSET'=> 'utf8',// 数据库编码默认采用utf8
// 	'URL_ROUTER_ON'   => true,
// 	'SHOW_PAGE_TRACE' => true,
	'URL_MODEL' => 0,
	'APP_GROUP_LIST' => 'App,Admin,Api', //项目分组设定
	'DEFAULT_GROUP'  => 'App', //默认分组
);

include './Public/Conf/config.php';

$arr2 = array(
	'DB_HOST'   => DB_HOST, // 服务器地址
	'DB_NAME'   => DB_NAME, // 数据库名
	'DB_USER'   => DB_USER, // 用户名
	'DB_PWD'    => DB_PWD,  // 密码
	'DB_PREFIX' => DB_PREFIX, // 数据库表前缀
);

return array_merge($arr1 , $arr2);
?>