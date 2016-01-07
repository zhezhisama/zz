<?php
//微云莱网络 旺旺ID: jie6059	 有问题请联系QQ 53867784
//粉丝按钮
$fensi_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=fensi';
$buy_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=index_info&refresh=1';
$jiazu_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=member&refresh=1';
$tool_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=tool';
$fuli_button = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=fuli';

$newmenu = '{
		 	"button":[
			{

				"name":"微商云订单",
				"type":"view",
				"url":"http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=yunorder"
		   },

			{

				"name":"惊喜",
				
				"sub_button":[
				{	
				   "name":"无限加人",

				   "type":"view",

					"url":"http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=hufen&list=1"

				},
				{	
				   "name":"无限加群",

				   "type":"view",

					"url":"http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Index&a=hufen&list=2"

				},
				{
				   "type":"view",

				   "name":"无限电影",

				   "url":"http://sm.7410.cc/?from=singlemessage&isappinstalled=0"
				},
				{
				   "type":"view",

				   "name":"无限游戏",

				   "url":"http://www.laiwanli.com/hotgame/?from=groupmessage&isappinstalled=0#rd"
				},
				{	

					"type":"view",

					"name":"更多福利",

					"url":"'.$fuli_button.'"
				}]
		   },

		   {

			    "name":"个人中心",
			    "sub_button":[
			    {	
				   "name":"新手指南",
				   "type":"click",					
				   "key" : "zhinan"
				   
				},

				{	

					"type":"click",

					"name":"二维码",

					"key":"erweima"
				},
				
				{	

					"type":"view",

					"name":"我的主页",

					"url":"'.$jiazu_button.'"
				},
				{	

				   "name":"客服",

				   "type":"click",

					"key":"kefu"
				},
				{	

				   "name":"商务",

				   "type":"view",

					"url":"http://mp.weixin.qq.com/s?__biz=MzI4NTA3MjA2Nw==&mid=401167408&idx=1&sn=deb7c385484d27308d13bddb7dee48d5#rd"
				}]

		   }]

		}';		

$message_name = '超级无限人脉';

$link_config = 'http://mp.weixin.qq.com/s?__biz=MzA3NzE1NDQ3MA==&mid=208850156&idx=1&sn=bcbb9afda9e1fdf855455d90461c1370#rd';

$config_good_pic = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'/imgpublic/goodpic.png';

$headimgurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'/imgpublic/2weima.jpg';

?>