<?php

class WechatAction extends Action {

	public function init() {

		import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );

		$config = M ( "Wxconfig" )->where ( array (

				"id" => "1" 

		) )->find ();

		

		$options = array (

				'token' => $config ["token"], // 填写你设定的key

				'encodingaeskey' => $config ["encodingaeskey"], // 填写加密用的EncodingAESKey

				'appid' => $config ["appid"], // 填写高级调用功能的app id

				'appsecret' => $config ["appsecret"], // 填写高级调用功能的密钥

				'partnerid' => $config ["partnerid"], // 财付通商户身份标识

				'partnerkey' => $config ["partnerkey"], // 财付通商户权限密钥Key

				'paysignkey' => $config ["paysignkey"]  // 商户签名密钥Key

				);

		$weObj = new Wechat ( $options );

		return $weObj;

	}

	public function index() {

		$weObj = $this->init ();

		$weObj->valid ();

		$type = $weObj->getRev ()->getRevType ();				

		include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/Public/Conf/button_config.php'; 

		switch ($type) {

			case Wechat::MSGTYPE_TEXT :

// 				$weObj->text ( "hello, I'm wechat" )->reply ();

				$key = $weObj->getRev()->getRevContent();

				

				$replay = M("Wxmessage")->where(array("key"=>$key))->select();

				for ($i = 0; $i < count($replay); $i++) {

					if ($replay[$i]["type"]==0) {

						$appUrl = 'http://' . $this->_server ( 'HTTP_HOST' ) . __ROOT__;

						$newsArr[$i] = array(

								'Title' => $replay[$i]["title"],

								'Description' => $replay[$i]["description"],

								'PicUrl' => $appUrl . '/Public/Uploads/'.$replay[$i]["picurl"],

								'Url' => $replay[$i]["url"]
/*.'&uid=' . $weObj->getRevFrom ()*/
						);

					}else{

						$weObj->text ( $replay[$i]["description"] )->reply ();

						exit ();

					}

				}

				

				if(!empty($newsArr))

				{

					$weObj->getRev ()->news ( $newsArr )->reply ();

				}

				else

				{

					$weObj->transfer_customer_service()->reply();

				}

				exit ();

				

				break;

			case Wechat::MSGTYPE_EVENT :

				$eventype = $weObj->getRev ()->getRevEvent ();
				//点击事件
				if ($eventype ['event'] == "CLICK") {

					$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

								
              		//第一次新增写入数据库
					if (!$usersresult) {
						//获取头像等信息
						$user = array();
						$openid = $weObj->getRevFrom ();
	
						$wx_info = $weObj->getUserInfo($openid);
	
						$user['wx_info'] = json_encode($wx_info);
						$user ["uid"] = $openid;
						//$user['price'] = 2;
						$user_id = M ( "User" )->add ( $user );
						if ($user_id) {
							$where = array();
		
							$where["uid"] = $weObj->getRevFrom ();
		
							$usersresult = $m->where($where)->find ();
						}
					}
      				if( $eventype ['key']=='erweima')
					{
						$usersresult = R ( "Api/Api/getuser", array (
							$weObj->getRevFrom ()
						) );
						
						if($usersresult['member']==1)
						{
							
							$data = array();
							$data['touser'] = $usersresult["uid"];
							$data['msgtype'] = 'text';
							$data['text']['content'] = '您的二维码正在生成，请等待,预计3-5秒.';
							$weObj->sendCustomMessage($data);
							
							$ticket = R ( "Api/Api/ticket", array (
								$usersresult 
							) );
							
							$image = realpath(dirname(__FILE__).'/../../../../').'/imgpublic/'.$ticket['pic'];
							
							$data['media'] = "@$image";
							$res = $weObj->uploadMedia($data, 'image');

							$weObj->getRev ()->image ( $res['media_id'] )->reply ();
							
							
						}
						else
						{
							$text = '您还没有购买软件使用权，无法生成推广二维码，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=goods">点击购买</a>成为微商云订单用户，即可拥有软件使用代理权，发布属于你的推广二维码，开启微商的财富之门。';
							$weObj->text ( $text )->reply ();
						}
						exit ();
					}
					if( $eventype ['key']=='kefu')
					{
						$data = array();
						$data['touser'] = $usersresult["uid"];
						$data['msgtype'] = 'text';
						$data['text']['content'] = '您的客服正在生成，请等待,预计3-5秒.';
						$weObj->sendCustomMessage($data);	
						
						$image = realpath(dirname(__FILE__).'/../../../../').'/kefu.jpg';
						$data['media'] = "@$image";
						$res = $weObj->uploadMedia($data, 'image');
						$weObj->getRev ()->image ( $res['media_id'] )->reply ();
						exit ();
					}
					if( $eventype ['key']=='GET_PIC')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

              		//第一次新增写入数据库
					if (!$usersresult) {
					//获取头像等信息
					$user = array();
					$openid = $weObj->getRevFrom ();

					$wx_info = $weObj->getUserInfo($openid);

					$user['wx_info'] = json_encode($wx_info);
				 	$user ["uid"] = $openid;
				 	//$user['price'] = 2;
					$user_id = M ( "User" )->add ( $user );
					if ($user_id) {
					$where = array();

					$where["uid"] = $weObj->getRevFrom ();

					$usersresult = $m->where($where)->find ();
					}
					}

						if($usersresult['member']==1)

						{

							$ticket = R ( "Api/Api/ticket", array (

								$usersresult 

							) );

							$image = realpath(dirname(__FILE__).'/../../../../').'/imgpublic/'.$ticket['pic'];

							$data['media'] = "@$image";

							$res = $weObj->uploadMedia($data, 'image');

							$weObj->getRev ()->image ( $res['media_id'] )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，部分功能无法使用，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员，即可发布自己的微信二维码，结识更多的人脉朋友。
--------------------------
<a href="http://'.$_SERVER['SERVER_NAME'].'/hufen/list.php?uid='.$usersresult['uid'].'>点这里进入【粉丝人脉】</a>';

							$weObj->text ( $text )->reply ();

						}

						exit ();

					}

					elseif( $eventype ['key']=='GET_URL')
					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

								
              		//第一次新增写入数据库
					if (!$usersresult) {
					//获取头像等信息
					$user = array();
					$openid = $weObj->getRevFrom ();

					$wx_info = $weObj->getUserInfo($openid);

					$user['wx_info'] = json_encode($wx_info);
				 	$user ["uid"] = $openid;
				 	//$user['price'] = 2;
					$user_id = M ( "User" )->add ( $user );
					if ($user_id) {
					$where = array();

					$where["uid"] = $weObj->getRevFrom ();

					$usersresult = $m->where($where)->find ();
					}
					}

						if($usersresult['member']==1)

						{

							$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Member&a=register&mid='.$usersresult['id'];

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，部分功能无法使用，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员，即可发布自己的微信二维码，结识更多的人脉朋友。
--------------------------
<a href="http://'.$_SERVER['SERVER_NAME'].'//hufen/list.php?uid='.$usersresult['uid'].'>点这里进入【粉丝人脉】</a>';

							$weObj->text ( $text )->reply ();

						}

						exit ();

					}

					else

					{

						$appUrl = 'http://' . $this->_server ( 'HTTP_HOST' ) . __ROOT__;

						

						$news = M ( "Wxmessage" )->where ( array (

								"key" => $eventype ['key'],

								"type" => 0 

						) )->select ();

						

						if ($news) {

							for($i = 0; $i < count ( $news ); $i ++) {

								$newsArr[$i] = array(

									'Title' => $news[$i]["title"],

									'Description' => $news[$i]["description"],

									'PicUrl' => $appUrl . '/Public/Uploads/'.$news[$i]["picurl"],

									'Url' => $news[$i]["url"]

								);

							}



							$weObj->getRev ()->news ( $newsArr )->reply ();

						}

						

						

						

						$replay = M("Wxmessage")->where(array("key"=>$eventype ['key'],"type" => 1))->select();

						if(!empty($replay[0]["description"]))

						{

							$weObj->text ( $replay[0]["description"] )->reply ();

							exit ();

					}
					
					//start		
						
						elseif( $eventype ['key']=='VIP1')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '微信多开工具
适用环境：安卓系统	
使用方法：安卓5.4多开，需先安装微养号（微养号只是辅助软件，安装好这后无需打开，但一定要安装）
重要提示：微信多开工具属第三方开发，真爱网不对软件的安全性负责，请用户自行判断是否需要进行安装！
<a href="http://pan.baidu.com/s/1i3EpyE5">本地下载地址一</a>.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
				
						elseif( $eventype ['key']=='VIP2')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '自动杀死粉
适用环境：PC端操作系统
使用方法：顾名思义，简单几步即可’杀死‘粉丝，不要犹豫了，在PC端输入以下地址，即可下载该工具！
重要提示：微信多开工具属第三方开发，真爱网不对软件的安全性负责，请用户自行判断是否需要进行安装！
下载地址：http://mn2.pc6.com/rm/weiyoulieshou.zip.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='VIP3')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '自动抢红包
适用环境：苹果ios系统（已越狱）
软件说明：怪不得老是抢不到红包，原来都是在用这个神器！
重要提示：微信多开工具属第三方开发，真爱网不对软件的安全性负责，请用户自行判断是否需要进行安装！
下载地址：http://apt.so/xuanshao.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='VIP4')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '微信群发器
适用环境：PC端操作系统
重要提示：微信多开工具属第三方开发，真爱网不对软件的安全性负责，请用户自行判断是否需要进行安装！
下载地址：http://7xlt40.com1.z0.glb.clouddn.com/微信群发_PC版.zip.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='VIP5')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '对话截图生成器
适用环境：PC端操作系统
软件说明：对话生成器是一款可以帮助用户生成微信对话截图的软件，该软件使用方便，操作简单，最新版！
下载地址：http://7xlt40.com1.z0.glb.clouddn.com/wxduihua.exe.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='VIP6')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '手机号生成器
适用环境：PC端操作系统
下载地址：http://7xlt40.com1.z0.glb.clouddn.com/手机号生成器_PC版.zip.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='VIP7')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '佣金制度
天使微商分销，让你赚个不停！
佣金比例分别为：10元，7元，3元.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '佣金制度
天使微商分销，让你赚个不停！
佣金比例分别为：10元，7元，3元.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='miji')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)
						{

							$url = '您还没有购买云订单软件，不能使用此服务，点击购买 微商第一工具，开启微商致富之路。';

							$weObj->text ( $url )->reply ();

						}
						else
						{
							$text = '您还没有购买云订单软件，不能使用此服务，点击购买 微商第一工具，开启微商致富之路。';
							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='GET_UURLS')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '<a href="http://yx8.com">点击打开手游战神</a>.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
						
						elseif( $eventype ['key']=='GET_UURLS1')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

						

						if($usersresult['member']==1)

						{

							$url = '<a href="http://www.jiaosm.net">点击打开同步大片</a>.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，无法查阅信息，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员后再来获取吧，.';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}
				//end
					
					
					elseif( $eventype ['key']=='GET_UURL')

					{

						$usersresult = R ( "Api/Api/getuser", array (

							$weObj->getRevFrom ()

						) );

								
             	 	//第一次新增写入数据库
					if (!$usersresult) {
					//获取头像等信息
					$user = array();
					$openid = $weObj->getRevFrom ();

					$wx_info = $weObj->getUserInfo($openid);

					$user['wx_info'] = json_encode($wx_info);
				 	$user ["uid"] = $openid;
					//$user['price'] = 2;
					$user_id = M ( "User" )->add ( $user );
					if ($user_id) {
					$where = array();

					$where["uid"] = $weObj->getRevFrom ();

					$usersresult = $m->where($where)->find ();
					}
					}

						if($usersresult['member']==1)

						{

							$url = ' <a href="http://'.$_SERVER['SERVER_NAME'].'/hufen/list.php?uid='.$usersresult['uid'].'">点击进入【粉丝人脉】</a>
							
您的专属二维码海报，您可以自愿分享给您的微信好友、朋友圈和微信群.';

							$weObj->text ( $url )->reply ();

						}

						else

						{

							$text = '您还不是VIP会员，部分功能无法使用，<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=index">点击购买</a>成为VIP会员，即可发布自己的微信二维码，结识更多的人脉朋友。
--------------------------
<a href="http://'.$_SERVER['SERVER_NAME'].'/hufen/list.php?uid='.$usersresult['uid'].'">点这里进入【粉丝人脉】</a>';

							$weObj->text ( $text )->reply ();

						}

						exit ();

						}

					}

				}elseif ($eventype['event'] == "subscribe") {

					//初始化用户

					$m = M ( "User" );

					$where = array();

					$where["uid"] = $weObj->getRevFrom ();

					$result = $m->where($where)->find ();
                     
		
					$user = array();

					

					//获取头像等信息

					$openid = $weObj->getRevFrom ();

					$wx_info = $weObj->getUserInfo($openid);

					$user['wx_info'] = json_encode($wx_info);
              		
					//生成验证码
					/*$yzm = "Y";
					for ($i = 0; $i < 3; $i++)
					{
						$yzm .= dechex(rand(1, 15)); //dechex将10进制转为16进制
					}
					$user['yzm'] = $yzm;*/
					//第一次新增关注写入数据库
					if (!$result) {
						$user["uid"] = $openid;
						$user['price'] = 2;
						$user_id = M ( "User" )->add ( $user );
						if ($user_id) {
							$where = array();
							$where["uid"] = $weObj->getRevFrom ();
							//新增会员信息
							$result = $m->where($where)->find ();
							$data = array();

							$data['touser'] = $result['uid'];
		
							$data['msgtype'] = 'text';
		
							$data['text']['content'] = '感谢您的关注，赠送2积分，可以换成米，点击【个人中心-<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=member">我的主页</a>】查看您的积分。';
		
							$weObj->sendCustomMessage($data);
						}
					}
					
					if(!empty($eventype['ticket']) && empty($result['l_id']) && empty($result['member']))
					{
						$where = array();
						$where["ticket"] = $eventype['ticket'];
						$results = $m->where($where)->find ();

						if(!empty($results['id']))
						{
							
							//新增会员的l_id 为上级的id
							$user ["l_id"] = $results['id'];
							
							//增加分销人
							$a_info = array();
							$a_info['id'] = $results['id'];
							$a_info['a_cnt'] = $results['a_cnt']+1;
							//增加会员注册赠送积分
							//$a_info['price'] = $results['price'] + 2;
							$user_id = M ( "User" )->save ( $a_info );

							if(strlen($results['uid'])>10)
							{
								/*$data = array();
								$data['touser'] = $results['uid'];
								$data['msgtype'] = 'text';
								$data['text']['content'] = '【'.$wx_info[nickname].'】通过您的二维码关注平台，即将成为您的一级员工！您的一级员工购买任意商品后成为您的推销员，您将获得【12积分】，如果【'.$wx_info[nickname].'】成功推销产品，您将获得【3积分】';
								$weObj->sendCustomMessage($data);*/

							}

							if($results['l_id'])//b jibie
							{
								$where = array();
								$where["id"] = $results['l_id'];
								$b_results = $m->where($where)->find ();

								if(!empty($b_results))
								{
									$b_info = array();
									$b_info['id'] = $b_results['id'];
									$b_info['b_cnt'] = $b_results['b_cnt']+1;
									//增加会员注册赠送积分
									//$b_info['price'] = $b_results['price'] + 0.5;
									$user_id = M ( "User" )->save ( $b_info );
									$user["l_b"] = $b_results['id'];

									if(strlen($b_results['uid'])>10)
									{
										/*$data = array();
										$data['touser'] = $b_results["uid"];
										//$a_info = json_encode($results['wx_info']);//一级成员信息
										$data['msgtype'] = 'text';
										$data['text']['content'] = '【'.$wx_info[nickname].'】通过'.$a_info['nickname'].'的二维码关注平台，即将成为您的二级员工！您的二级员工购买任意商品后成为您的推销员，您将获得【3积分】，如果【'.$wx_info[nickname].'】成功推销产品，您将获得【2积分】';
										$weObj->sendCustomMessage($data);*/

									}

									

									if($b_results['l_id'])//c jibie
									{
										$where = array();
										$where["id"] = $b_results['l_id'];
										$c_results = $m->where($where)->find ();

										if(!empty($c_results))
										{
											$c_info = array();
											$c_info['id'] = $c_results['id'];
											$c_info['c_cnt'] = $c_results['c_cnt']+1;
											//增加会员注册赠送积分
											//$c_info['price'] = $c_results['price'] + 0.5;
											$user_id = M ( "User" )->save ( $c_info );
											$user["l_c"] = $c_results['id'];
											
											if(strlen($c_results['uid'])>10)
											{
												/*$data = array();
												$data['touser'] = $c_results["uid"];
												$b_info = json_encode($b_results['wx_info']);//二级成员信息
												$data['msgtype'] = 'text';
												$data['text']['content'] = '【'.$wx_info[nickname].'】通过'.$b_info['nickname'].'的二维码关注平台，成为您的三级员工，你的三级员工购买任意商品后成为您的推销员，您将获得【2积分】';
												$weObj->sendCustomMessage($data);*/

											}

										}
										

									}

								}

							}

						}

					}

					if(!empty($result['id']))
					{
						$user['id'] = $result['id'];

						$user_id = M ( "User" )->save ( $user );

					}
					else
					{

						$user ["uid"] = $openid;
						$user_id = M ( "User" )->add ( $user );
						
					}

					if( !empty($result['l_id']) )
					{
						$user["l_id"] = $result['l_id'];
					}

					if(empty($result["l_id"]) && !empty($result['member']))
					{
						$text = '感谢您的关注，您成为'.$message_name.'的第【'.$result['id'].'】位用户，微商云订单，专业为微商打造的订单记录云平台。';
					}

					else
					{
						
						if(!empty($user["l_id"]))
						{
							$user_info = M ( "User" )->where( array('id'=>$user ["l_id"]) )->find();

							$user_info = json_decode($user_info['wx_info'],true);

							

							if($result['id']>1)
							{
								$user_id = $result['id'];
							}
							/*由【'.$user_info['nickname'].'】邀请*/
							$text = '感谢您的关注，您成为'.$message_name.'的第【'.$result['id'].'】位用户，微商云订单，专业为微商打造的订单记录云平台。';

						}
						else
						{

							$text = '感谢您的关注，您成为'.$message_name.'的第【'.$result['id'].'】位用户，微商云订单，专业为微商打造的订单记录云平台。';

						}

					}
						
					
					
    				$weObj->text ( $text )->reply ();
					//
					$data['touser'] = $result['uid'];
					$data['msgtype'] = 'text';
					$data['text']['content'] = '现在购买微商云订单软件，同时拥有我们的软件代理权，开始财富旅程。同时享受价值近万元的微商工具大全和娱乐大礼包。希望通过我们的软件，让大家的微商之路更加畅通。
点击 <a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?g=App&m=Index&a=goods">购买软件</a>
点击 <a href="http://mp.weixin.qq.com/s?__biz=MzI4NTA3MjA2Nw==&mid=400370843&idx=1&sn=1f5cac743ba4c38e4996111651962fc7&scene=0&from=groupmessage&isappinstalled=0#wechat_redirect">教你如何使用</a>';
					$weObj->sendCustomMessage($data);
					
				}elseif ($eventype['event'] == "unsubscribe") {
        		    //初始化用户
        		    
        		    $m = M ( "User" );
        		    
        		    $where = array();
        		    
        		    $where["uid"] = $weObj->getRevFrom ();
        		    
        		    $result = $m->where($where)->find ();
        		    
        		    if($result){
        		        if($result['member'] == 0){//删除会员信息
        		            //上级减去数量
        		            if($result['l_id']){
        		            
        		                M('User')->where(array('id'=>$result['l_id']))->setDec('a_cnt',1);
        		            
        		                if($a_cnt>0){M('User')->where(array('id'=>$result['l_id']))->setDec('b_cnt',$a_cnt);};
        		            
        		                if($b_cnt>0){M('User')->where(array('id'=>$result['l_id']))->setDec('c_cnt',$b_cnt);};
        		            
        		            }
        		            
        		            if($result['l_b']){
        		                M('User')->where(array('id'=>$result['l_b']))->setDec('b_cnt',1);
        		                if($a_cnt>0){M('User')->where(array('id'=>$result['l_b']))->setDec('c_cnt',$a_cnt);};
        		            }
        		            
        		            if($user_info['l_c']){
        		            
        		                M('User')->where(array('id'=>$result['l_c']))->setDec('c_cnt',1);
        		            
        		            }
        		            
        		            //删除会员
        		            M("User")->where(array('id'=>$result['id']))->delete();  
        		             
        		        }
        		        
        		    }
        		    //if结束
        		} 

				exit ();

				break;

			default :
				$text = '超级人脉源计划，即刻通知，你已经被列入计划中！';
				$weObj->text ( $text )->reply ();

		}

	}

	/*

	

	*/

	public function createMenu() {

		include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/Public/Conf/button_config.php'; 



		$weObj = $this->init ();

		$weObj->createMenu ( $newmenu );

		$this->success ( "重新创建菜单成功!" );

	}

}