<?php

class ApiAction extends Action {



	public function get_user_pic($uid,$ticket,$logo,$name)

	{

		$user_pic = "./imgpublic/user_".$uid.".jpg";

		

		if(!file_exists($user_pic))

		{

			$pic = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;

			$data = file_get_contents($pic);

			$filepath = "./imgpublic/";

			$filename = "ticket_".$uid.".jpg";

			$fp = @fopen($filepath.$filename,"w");

			@fwrite($fp,$data); 

			fclose($fp);

			$ticket = $filepath.$filename;

			

			if(!empty($logo)){

				$pic = $logo;

				$data = file_get_contents($pic);

				$filepath = "./imgpublic/";

				$filename = "logo_".$uid.".jpg";

				$fp = @fopen($filepath.$filename,"w");

				@fwrite($fp,$data);

				fclose($fp);

				$logo = $filepath.$filename;

			}

			import ( 'ThinkImage', APP_PATH . 'Common/ThinkImage', '.class.php' );



			$img = new ThinkImage(THINKIMAGE_GD, './card.jpg'); 

			

			$img->open($ticket)->thumb(270, 270)->save($ticket);

			if(!empty($logo)){$img->open($logo)->thumb(60, 60)->save($logo);}

			define('THINKIMAGE_WATER_CENTER',    5);

			$name = substr($name,0,15);

			if(!empty($logo)){

				$img->open('./card.jpg')->water($ticket, array(183,438))->water($logo, array(290,543))->text($name,'./hei.ttf','18','#000000', array(150,736))->save($user_pic);

			}

			else

			{

				$img->open('./card.jpg')->water($ticket, array(169,823))->text($name,'./hei.ttf','18','#000000', array(282,721))->save($user_pic);

			}

		

		}

		return $user_pic;

	}

	

	public function ticket($usersresult)

	{

		//推荐人

		//$result_l = M("User")->where(array('id'=>$usersresult['l_id']))->find();

			

		//二维码

		if($usersresult['member']==1)

		{

			$ticket = $usersresult['ticket'];

			$wx_info = json_decode($usersresult['wx_info'],true);

			$logo = $wx_info['headimgurl'];

			$name = $wx_info['nickname'];

			

			$ticket = $this->get_user_pic($usersresult['uid'],$ticket,$logo,$name); 

			$pic = 'user_'.$usersresult['uid'].'.jpg';

		}

		/*else if(!empty($result_l))

		{

			$ticket = $result_l['ticket'];

			$wx_info = json_decode($result_l['wx_info'],true);

			$logo = $wx_info['headimgurl'];

			$name = $wx_info['nickname'];

			

			$ticket = $this->get_user_pic($result_l['uid'],$ticket,$logo,$name); 

			$pic = 'user_'.$result_l['uid'].'.jpg';

		}*/

		else

		{

			$ticket = "./imgpublic/benbendou.jpg";

			$pic = 'benbendou.jpg';

		}

		

		$returnValue['ticket'] = $ticket;

		$returnValue['pic'] = $pic;

		return $returnValue;

	}

	public function kefu()
	{
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
		
		$weObj->text ( "kefu" )->reply ();
		
		/*$image = realpath(dirname(__FILE__).'/../../../../').'/kefu.jpg';
		$data['media'] = "@$image";
		$res = $weObj->uploadMedia($data, 'image');
		$weObj->getRev ()->image ( $res['media_id'] )->reply ();*/
		exit ();
		
	}

	public function login($username, $password) {

		$where ["username"] = $username;

		$where ["password"] = md5 ( $password );

		$result = M ( "Admin" )->where ( $where )->find ();

		if ($result) {

			return $result ["username"];

		}

	}

	public function getsetting() {

		$result = M ( "Info" )->find ();

		if ($result) {

			return $result;

		}

	}

	public function setting($name, $notification) {

		$data ["id"] = 1;

		$data ["name"] = $name;

		$data ["notification"] = $notification;

		$result = M ( "Info" )->save ( $data );

		if ($result) {

			return $result;

		}

	}

	public function getalipay() {

		$result = M ( "Alipay" )->find ();

		if ($result) {

			return $result;

		}

	}

	public function setalipay($alipayname, $partner, $key) {

		$select = M("Alipay")->select();

		if ($select) {

			$data ["id"] = $select[0]['id'];

			$data ["alipayname"] = $alipayname;

			$data ["partner"] = $partner;

			$data ["key"] = $key;

			$result = M ( "Alipay" )->save ( $data );

		}else{

			$data ["alipayname"] = $alipayname;

			$data ["partner"] = $partner;

			$data ["key"] = $key;

			$result = M ( "Alipay" )->add ( $data );

		}

		

		if ($result) {

			return $result;

		}

	}

	public function getarraymenu() {

		$result = M ( "Menu" )->select ();

		

		import ( 'Tree', APP_PATH . 'Common', '.php' );

		$tree = new Tree (); // new 之前请记得包含tree文件!

		$tree->tree ( $result ); // 数据格式请参考 tree方法上面的注释!

		                         

		// 如果使用数组, 请使用 getArray方法

		$result = $tree->getArray ();

		

		// 下拉菜单选项使用 get_tree方法

		// $tree->get_tree();

		if ($result) {

			return $result;

		}

	}

	public function getmenu($data = "") {

		$result = M ( "Menu" )->where($data)->select ();

		if ($result) {

			return $result;

		}

	}

	public function addmenu($parent, $name, $addmenu) {

		if ($addmenu == 0) {

			$data ["name"] = $name;

			$data ["pid"] = $parent;

			$result = M ( "Menu" )->add ( $data );

			if ($result) {

				return $result;

			}

		} else {

			$data ["id"] = $addmenu;

			$data ["name"] = str_replace ( "│ ", "", $name );

			$data ["pid"] = $parent;

			$result = M ( "Menu" )->save ( $data );

			if ($result) {

				return $result;

			}

		}

	}

	public function delmenu($id) {

		$result = M ( "Menu" )->where ( array (

				'id' => $id 

		) )->delete ();

		if ($result) {

			return $result;

		}

	}

	public function getmenuvalue($menu_id) {

		$result = M ( "Menu" )->where ( array (

				"id" => $menu_id 

		) )->find ();

		if ($result) {

			return $result ["name"];

		}

	}

	public function getgood($data = "") {

		$result = M ( "Good" )->where($data)->select ();

		if ($result) {

			return $result;

		}

	}

	public function addgood($data) {

		if ($data["id"]) {

			$result = M ( "Good" )->save($data);

		}else{

			$result = M ( "Good" )->add($data);

		}

		

		if ($result) {

			return $result;

		}

	}

	public function delgood($id) {

		$result = M ( "Good" )->where ( array (

				"id" => $id 

		) )->delete ();

		if ($result) {

			return $result;

		}

	}

	public function getorder() {

	}

	public function gettheme() {

		$m = M ( "Info" );

		$result = $m->find ();

		if ($result) {

			return $result;

		}

	}

	public function delorder($id) {

		$reuslt = M ( "Order" )->where ( array (

				"id" => $id 

		) )->delete ();

		if ($reuslt) {

			return $reuslt;

		}

	}

	public function deltx($id) {

		$reuslt = M ( "Tx_record" )->where ( array (

				"id" => $id 

		) )->delete ();

		if ($reuslt) {

			return $reuslt;

		}

	}

	

	public function txpublish($id) {

		$result = M ( "Order" )->where ( array('id'=>$id) )->find();



		if(!empty($result))

		{

			$data ["id"] = $id;

			$data ["order_status"] = 4;

			M ( "Order" )->save ( $data );

			M ( "Order_level" )->where(array('order_id'=>$result['orderid']))->save ( array('status'=>4) );

			M ( "Order_level" )->where(array('level_id'=>$result['user_id']))->save ( array('status'=>4) );

			

			$userd_id = $result['user_id'];

			$data = array();

			$data['member'] = 0;

			$data['ticket'] = '';

			$data['url'] = '';

			$data['l_id'] = 0;

			$data['l_b'] = 0;

			$data['l_c'] = 0;

			$data['c_cnt'] = 0;

			$data['b_cnt'] = 0;

			$data['a_cnt'] = 0;

			$user_info = M('User')->where(array('id'=>$userd_id))->find();

			if(!empty($user_info))

			{

				//下一级关系都断掉

				M('User')->where(array('id'=>$userd_id))->save($data);

				$a_cnt = M('User')->where(array('l_id'=>$userd_id))->save(array('l_id'=>0,'l_b'=>0,'l_c'=>0));

				$b_cnt = M('User')->where(array('l_b'=>$userd_id))->save(array('l_b'=>0,'l_c'=>0));

				$c_cnt = M('User')->where(array('l_c'=>$userd_id))->save(array('l_c'=>0));

				

				//上级数量减去

				if($user_info['l_id'])

				{

					M('User')->where(array('id'=>$user_info['l_id']))->setDec('a_cnt',1);

					if($a_cnt>0){M('User')->where(array('id'=>$user_info['l_id']))->setDec('b_cnt',$a_cnt);};

					if($b_cnt>0){M('User')->where(array('id'=>$user_info['l_id']))->setDec('c_cnt',$b_cnt);};

				}

				

				if($user_info['l_b'])

				{

					M('User')->where(array('id'=>$user_info['l_b']))->setDec('b_cnt',1);

					if($a_cnt>0){M('User')->where(array('id'=>$user_info['l_b']))->setDec('c_cnt',$a_cnt);};

				}

				

				if($user_info['l_c'])

				{

					M('User')->where(array('id'=>$user_info['l_c']))->setDec('c_cnt',1);

				}

			}

			

		}

	}

	

	

	public function publish($id) {

		

		$result = M ( "Order" )->where ( array('id'=>$id) )->find();



		if(!empty($result))

		{

			$data ["id"] = $id;

			$data ["order_status"] = 1;

			$data ["time"] = date('Y-m-d H:i:s');

			M ( "Order" )->save ( $data );

			

			$user_info = M ( "User" )->where ( array('id'=>$result['user_id']) )->find();



			if(!empty($user_info))

			{

				$order_info = json_decode($result['order_info'],true);

				$data = array();

				$data['touser'] = $user_info['uid'];

				$data['msgtype'] = 'text';

				$data['text']['content'] = '<a href="http://'.$_SERVER['SERVER_NAME'].'/hufen/list.php">点击开始疯狂涨粉吧</a>';

				

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

				

				$weObj->sendCustomMessage($data);

			}

		}

							

		if ($reuslt) {

			return $reuslt;

		}

	}

	public function payComplete($id) {

		$data ["id"] = $id;

		$data ["pay_status"] = 1;

		$result = M ( "Order" )->save ( $data );

		if ($reuslt) {

			return $reuslt;

		}

	}

	public function txpayComplete($id) {

		$data ["id"] = $id;

		$data ["status"] = 1;

		$result = M ( "Tx_record" )->save ( $data );

		if ($reuslt) {

			return $reuslt;

		}

	}

	public function getuser($uid) {

		$m = M ( "User" );

		$where["uid"] = $uid;

		$result = $m->where($where)->find ();

		if ($result) {

			return $result;

		}

	}
	
	//确认收货完成发送信息
	public function orderover($out_trade_no){
		$order_info = M ("Order")->where(array('orderid'=>$out_trade_no))->find();
		if(empty($order_info))
		{
			exit('未找到订单信息');
		}
		$userdata = M ( "User" )->where ( array (
				"id" => $order_info['user_id']) )->find ();
	
		if (!$userdata) {
			exit('未找到用户信息');
		}
		
		$Order_level_info = M ("Order_level")->where(array('order_id'=>$out_trade_no))->select();
		if(!empty($Order_level_info))
		{
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
			
			foreach($Order_level_info as $info)
			{
				if($info['level_type']==1)
				{
					$level_text = '一';
				}
				
				if($info['level_type']==2)
				{
					$level_text = '二';
				}
				
				if($info['level_type']==3)
				{
					$level_text = '三';
				}
				
				$level_id = $info['level_id'];
				$user_info = M('User')->where(array('id'=>$level_id))->find();
				$wx_info = json_decode($userdata['wx_info'],true);
				
				if(strlen($user_info['uid'])>10)
				{
					$data = array();
					$data['touser'] = $user_info['uid'];
					$data['msgtype'] = 'text';
					$data['text']['content'] = '【'.$wx_info['nickname'].'】在'.date('Y-m-d H:i:s').'加入您的队伍，已经成为您的'.$level_text.'级员工，从此开始为您赚米，您获得的【￥'.$info['price'].'】积分自动到账！';
					$weObj->sendCustomMessage($data);
				}
			}
		}
		
	}

//-----------自动发货

	public function autofahuo($id) {

			M ("Order")->where(array('orderid'=>$id))->save (array('order_status'=>1));	//自动设置为已发货状态		

			$order_info = M ( "Order" )->where ( array('orderid'=>$id) )->find();	//传输过来订单ID，查询到userid

			$user_info = M ( "User" )->where ( array('id'=>$order_info['user_id']) )->find();



			if(!empty($user_info))

			{

				$order_info = json_decode($result['order_info'],true);

				$data = array();

				$data['touser'] = $user_info['uid'];

				$data['msgtype'] = 'text';

				$data['text']['content'] = '恭喜您成功购买软件，今天起作自己的老板，现在您可以使用您的特权，可以分享赚米。点击生成【<a href="http://'.$_SERVER['SERVER_NAME'].'/index.php?a=my_ticket">推广二维码</a>】，在【我的订单】中点击【确认收货】后,您才可以进行积分兑换。
您代理的产品达成交易后，您将获得【12积分】
您的一级员工达成交易后，您将获得【3积分】
您的二级员工达成交易后，您将获得【2积分】';

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

				

				$weObj->sendCustomMessage($data);

			}

	}

//----------------------------------------------------

}









