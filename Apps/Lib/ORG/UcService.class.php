<?php


class UcService{
	public function __construct(){
		include_once(WBLOG_ROOT_PATH . 'Apps/Conf/config_ucenter.php');
		include_once(WBLOG_ROOT_PATH . 'uc_client/client.php');
	}
	public function register($username, $password, $email){
		/**
		* 会员注册
		*/
		$uid = uc_user_register($username, $password, $email);//UCenter的注册验证函数
		 if($uid <= 0) {
			 if($uid == -1){
			 	return '用户名不合法';
			 }else if($uid == -2){
			 	return '包含不允许注册的词语';
			 }else if($uid == -3){
			 	return '用户名已经存在';
			 }else if($uid == -4){
			 	return 'Email 格式有误';
			 }else if($uid == -5){
			 	return 'Email 不允许注册';
			 }else if($uid == -6){
			 	return '该 Email 已经被注册';
			 }else{
			 	return '未定义';
			 }
		 }else{
			 return intval($uid);//返回一个非负数
		 }
	}
}






?>