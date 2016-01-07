<?php
//粉丝按钮
$fensi_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=fensi';
$buy_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=index_info&refresh=1';
$jiazu_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=member&refresh=1';
$newmenu = '{

		 "button":[
			{	
			 	"name":"粉丝人脉",
 				"type":"view",
				"url":"'.$fensi_button.'"

			},
			{
				"name":"会员专属",
				"sub_button":[
				{	
				   "name":"超级人脉",

				   "type":"view",

					"url":"http://tianshi.lomedia.com.cn/hufen/list.php"

				},

				{
				   "type":"click",

				   "name":"工具大全",

				   "key":"erweima"
				},
				{	

					"type":"click",

					"name":"会员福利",

					"key":"VIP3"
				}]
		   },

		   {

			   "name":"个人中心",
			    "sub_button":[
			    {	
				   "name":"新手指南",
				   "type":"view",					"url":"http://mp.weixin.qq.com/s?__biz=MzA3NzE1NDQ3MA==&mid=208850156&idx=1&sn=bcbb9afda9e1fdf855455d90461c1370#rd"
				   
				},

				{	

				   "name":"客服",

				   "type":"view",

					"url":"http://wpa.qq.com/msgrd?v=3&uin=53867784&site=cjrm&menu=yes"
				},
				
				{	

				   "name":"成为CEO",

				   "type":"view",

					"key":"http://tianshi.lomedia.com.cn/index.php?g=App&m=Index&a=index"

				},
				
				{	

					"type":"view",

					"name":"我的主页",

					"url":"'.$jiazu_button.'"
				}]

		   }]

		}';		

$message_name = '天使微商';

$link_config = 'http://mp.weixin.qq.com/s?__biz=MzA3NzE1NDQ3MA==&mid=208850156&idx=1&sn=bcbb9afda9e1fdf855455d90461c1370#rd';

$config_good_pic = 'http://tianshi.lomedia.com.cn/imgpublic/goodpic.png';

$headimgurl = 'http://tianshi.lomedia.com.cn/imgpublic/2weima.jpg';

?>