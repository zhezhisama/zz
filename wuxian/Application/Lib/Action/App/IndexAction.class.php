<?php
class IndexAction extends Action {
	function _initialize() {
		header("Content-type: text/html; charset=utf-8"); 
		if($_GET['debug'])
		{
		
		}
		else
		{
			$agent = $_SERVER['HTTP_USER_AGENT']; 
			if(!strpos($agent,"icroMessenger")) {
				//echo '请使用微信访问';exit;
			}
		}
		//$this->init();
	}
	
	public function init($type='member')
	{

		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(strpos($agent,"icroMessenger") && ((!isset($_GET['uid']) && empty($_SESSION["uid"])) || isset($_GET['refresh']))) {

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
			
			$info = $weObj->getOauthAccessToken();
			
			if(!$info)
			{
				$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/$type",$_GET);
				$url = $weObj->getOauthRedirect($callback,'','snsapi_base');
				header("Location: $url");
				exit();
			}
			else
			{
				$_SESSION['uid'] = $_POST['uid'] = $_GET['uid'] = $info['openid'];
			}
		}
	
		if(!empty($_SESSION["uid"]) && empty($_GET['uid']))
		{
			$_GET['uid'] = $_SESSION["uid"];
		}
		
		if ($_GET['uid']) {
		
			$uid = $_SESSION["uid"] = $_GET['uid'];
			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
			
			if(strpos($agent,"icroMessenger") && strlen($uid)>10) {
				if($usersresult['wx_info']==null || $usersresult['wx_info']==false || $usersresult['wx_info']=='false' || $usersresult['wx_info']=='null')
				{
					$wx_info = $weObj->getUserInfo($uid);
					if($wx_info['subscribe']!=1)
					{
						if($type !='index_info')
						{
							exit('请先关注公众号！');
						}
					}
					else
					{
						if(empty($usersresult))
						{
							$user = array();
							$user ["uid"] = $uid;
							$usersresult['id'] = M ( "User" )->add ( $user );
						}
				
						$user['id'] = $usersresult['id'];
						$user['wx_info'] = json_encode($wx_info);
						$user_id = M ( "User" )->save ( $user );
					}
				}
			}
			
			//更新我的可提现金额
			$where = array();
			$where ["level_id"] = $usersresult['id'];
			$where ["status"] = 2;
			$where ["active_time"] = array('elt',date('Y-m-d H:i:s'));
			$result = M ( "Order_level" )->where($where)->select();
			
			foreach($result as $info)
			{
				$level_id = $info['level_id'];
				$price = $info['price'];
				
				M ("User")->where(array('id'=>$level_id))->setInc('price',$price);
				M ( "Order_level" )->where(array('id'=>$info['id']))->save(array('status'=>3));
				
				M ( "Order" )->where(array('orderid'=>$info['order_id']))->save(array('order_status'=>3));
			}
			
			//7天未确认的，自动收获
			$where = array();
			$where ["order_status"] = 1;
			$where ["pay_status"] = 1;
			
			$tixianinfo = array();
			$tixianinfo['shouhuo'] = 7;
			$tixianinfo['tixian'] = 7;
			$tixianinfo['jine'] = 50;
			if(file_exists('./Public/Conf/tixianinfo.php'))
			{
				require './Public/Conf/tixianinfo.php';
				$tixianinfo = json_decode($tixianinfo,true); 
			}
		
			$date = strtotime("-$tixianinfo[shouhuo] days");
			
			$date = date('Y-m-d H:i:s',$date);
			$where ["time"] = array('elt',$date);
			$result = M ( "Order" )->where($where)->select();

			foreach($result as $info)
			{
				$out_trade_no = $info['orderid'];
				$this->confirm_order_status($out_trade_no);
			}
			
			//分销保持资格
			$this->fenxiao_zige($usersresult);
		}
		else
		{	
		

			$url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Member/login');
			header("Location: $url");
			exit();
		}
	}
	
	public function fenxiao_zige($usersresult)
	{
		//分销保持资格
		//$this->dongjia_time = '';
		$this_time = time();
		if($usersresult['member'] > 0 and $usersresult['limit_time'] < $this_time)
		{
				$data = array();
				$data['member'] = 0;
				$data['id'] = $usersresult['id'];
				M("User")->save($data);
		}
	}
	public function hufen()
	{
		$this->init('hufen');
		if ($_GET ['uid']) {
			if($_GET ['list'] == 1){
				redirect('http://' . $_SERVER ['SERVER_NAME'].'/hufen/list.php');//跳转	
			}else{
				redirect('http://' . $_SERVER ['SERVER_NAME'].'/hufen/listqun.php');//跳转
			} 
		}
	}
	
	public function yunorder(){
		$this->init('yunorder');
		$uid = $_GET ['uid'];
		$usersresult = R ( "Api/Api/getuser", array (
					$uid 
		) );
		
		//临时修改 php@妖孽 之前为1
		if ($usersresult['member'] == 1) {
			redirect('http://yundingdan.tlgz88.com.cn/mobile/user.php?wxid='.$usersresult['uid']);//跳转
		}else{
			echo "<script>alert('您还未购买此软件');location.href='index.php?g=App&m=Index&a=goods_detail&goods_id=22'</script>";	
		}
		
	}
	
	public function member_info()
	{
		if ($_GET ['id'] && $_GET ['type']) {
			$type = $_GET ['type'];
			import ( 'ORG.Util.Page' );
			$id = $_GET ["id"];
			$user = htmlspecialchars($_GET ["user"]);
			$where = array('id'=>$id);
			$usersresult = M ("User")->where($where)->find();
			if(empty($usersresult))
			{
				exit('未查到该用户信息');
			}
			//分销保持资格
			$this->fenxiao_zige($usersresult);
			$this->assign ( "users", $usersresult );
			$count_desc="我的分销：";
			if($type==1)
			{
				$where = array('l_id'=>$id);
			}
			else if ($type==2)
			{
				$where = array('l_b'=>$id);
			}
			else if($type==3)
			{
				$where = array('l_c'=>$id);
			}
			if(!empty($user)){$where['id'] = $user;}
			
			$count = M ("User")->where($where)->count();
			
			if($type==1)
			{
				$count_desc .= "部分会员($count)人";
			}
			else if ($type==2)
			{
				$count_desc .= "二级会员($count)人";
			}
			else if($type==3)
			{
				$count_desc .= "三级会员($count)人";
			}
			$Page = new Page ( $count, 10 ); 
			$Page -> setConfig('header', '人');
			$Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
			$show = $Page->show (); // 分页显示输出
			$result = M ("User")->where($where)->limit ( $Page->firstRow . ',' . $Page->listRows )->select();
			$this->assign ( "result", $result );
			$this->assign ( "page", $show ); 
			$this->assign ( "count_desc", $count_desc ); 
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$this->assign ( "dongjia_time", $this->dongjia_time );
			
			$this->display ();
		}
	}
	
	
	
	public function jifen_info()
	{
		if ($_GET ['id'] && $_GET ['type']) {
			$type = $_GET ['type'];
			import ( 'ORG.Util.Page' );
			$id = $_GET ["id"];
			$user = htmlspecialchars($_GET ["user"]);
			$where = array('id'=>$id);
			
			$where1 = array('level_id'=>$id);
			
			$usersresult = M ("User")->where($where)->find();
			
			//wemall_order  // wemall_order_level
			
			//$wx_info = json_decode($users['wx_info'],true);
			
			//user_status stats, user_profile profile
		
			$num = M ("order_level")->where(array('level_id' => $id , 'status' => 3))->count();
			
			$result = M ("User")->where($where)->limit ( $Page->firstRow . ',' . $Page->listRows )->select();
			
			
			$Page = new Page ( $num, 10 ); 
			$Page -> setConfig('header', '人');
			$Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
			$show = $Page->show (); // 分页显示输出
			
			
			$jifenresult = M ("order_level")->where(array('level_id' => $id , 'status' => 3))->field('order_id,level_id,price,active_time')->order('active_time desc' )->limit ( $Page->firstRow . ',' . $Page->listRows )->select();
			$jresult = array();
			foreach($jifenresult as $jkey=>$jval){
				
				$order_where = array('orderid'=>$jval['order_id'],'order_status'=>3); 
				$orderresult = M ("order")->where($order_where)->field('user_id')->find();
			
				$userinfosult = M ("user")->field('id,wx_info')->where(array('id'=>$orderresult['user_id']))->find();
				
				$jresult[$jkey] = $jval;
				$jresult[$jkey]['user_id'] = $userinfosult['id'];
				$jresult[$jkey]['wx_info'] = $userinfosult['wx_info'];
			}
		
		/*	
			$jifen_num = count($jifenresult);
			
			if($jifen_num > 0 && $jifen_num ==1){
				$order_arr = ' orderid = ';
			}else{
				$order_arr = ' orderid in ( ';
					
				for($i = 0;$i < $jifen_num; $i++){
					if($i == ($jifen_num-1) ){
						$order_arr .= $jifenresult[$i]['order_id'].")";
					}else{
						$order_arr .= $jifenresult[$i]['order_id'].",";
					}
				
				}
			}
			
			
			$orderresult = M ("order")->where($order_arr)->field('user_id')->order('id desc' )->select();
			
			$order_num = count($orderresult);
			

			if( $order_num > 0 && $order_num == 1){
				
				$img_arr = " id = ".$orderresult[0]['user_id'];
				
			}else{
				$img_arr = 'id in (';
				for($j = 0 ;$j < $order_num ; $j++ ){
					if($j == ($order_num-1) ){
						$img_arr .= $orderresult[$j]['user_id']." )";
					}else{
						$img_arr .= $orderresult[$j]['user_id'].",";
					}
					
				}
			}
			

			$userinfosult = M ("user")->where($img_arr)->field('id,wx_info')->order('id desc' )->select();*/
			
			
			
			/*for($j = 0 ;$j < $order_num ; $j++ ){
				
				$jifenresult[$j]['wx_info'] = $userinfosult[$j]['wx_info'];
				$jifenresult[$j]['user_id'] = $userinfosult[$j]['id'];
					
			}*/
			
			
			$this->assign ( "jifenresult", $jresult );
			
			
			
			if(empty($usersresult))
			{
				exit('未查到该用户信息');
			}
			
			
			
			
			//分销保持资格
			$this->fenxiao_zige($usersresult);
			$this->assign ( "users", $usersresult );
			$count_desc="我的分销：会员(".$num.")人";
			
			
			$this->assign ( "result", $result );
			$this->assign ( "page", $show ); 
			$this->assign ( "count_desc", $count_desc ); 
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$this->assign ( "dongjia_time", $this->dongjia_time );
			
			$this->display ();
		}
	}
	
	
	
	
	
	
	
	
	
	
	public function tool()
	{
			$this->init('member');
			if ($_GET ['uid']) {
			
			$type = $_GET ['type'];
			import ( 'ORG.Util.Page' );
			$uid = $_GET ["uid"];
			$user = htmlspecialchars($_GET ["user"]);
			$where = array('uid'=>$uid);
			$usersresult = M ("User")->where($where)->find();
			/*if(empty($usersresult))
			{
				exit('未查到该用户信息');
			}*/
			//分销保持资格
			$this->fenxiao_zige($usersresult);
			$this->assign ( "users", $usersresult);
			$count_desc="我的分销：";
			if($type==1)
			{
				$where = array('l_id'=>$usersresult['id']);
			}
			else if ($type==2)
			{
				$where = array('l_b'=>$usersresult['id']);
			}
			else if($type==3)
			{
				$where = array('l_c'=>$usersresult['id']);
			}
			if(!empty($user)){$where['id'] = $user;}
			
			$count = M ("User")->where($where)->count();
			
			if($type==1)
			{
				$count_desc .= "部分会员($count)人";
			}
			else if ($type==2)
			{
				$count_desc .= "二级会员($count)人";
			}
			else if($type==3)
			{
				$count_desc .= "三级会员($count)人";
			}
			$Page = new Page ( $count, 10 ); 
			$Page -> setConfig('header', '人');
			$Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
			$show = $Page->show (); // 分页显示输出
			$result = M ("User")->where($where)->limit ( $Page->firstRow . ',' . $Page->listRows )->select();
			$this->assign ( "result", $result );
			$this->assign ( "page", $show ); 
			$this->assign ( "count_desc", $count_desc ); 
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$this->assign ( "dongjia_time", $this->dongjia_time );
			$this->display ();
			}

	}
	
	
	public function fuli()
	{
			$this->init('member');
			if ($_GET ['uid']) {
			
				$type = $_GET ['type'];
				import ( 'ORG.Util.Page' );
				$uid = $_GET ["uid"];
				$user = htmlspecialchars($_GET ["user"]);
				$where = array('uid'=>$uid);
				$usersresult = M ("User")->where($where)->find();
				$this->assign ( "users", $usersresult);
				
			/*if(empty($usersresult))
			{
				exit('未查到该用户信息');
			}*/
			//分销保持资格
			$this->fenxiao_zige($usersresult);
			$this->assign ( "users", $usersresult);
			$count_desc="我的分销：";
			if($type==1)
			{
				$where = array('l_id'=>$usersresult['id']);
			}
			else if ($type==2)
			{
				$where = array('l_b'=>$usersresult['id']);
			}
			else if($type==3)
			{
				$where = array('l_c'=>$usersresult['id']);
			}
			if(!empty($user)){$where['id'] = $user;}
			
			$count = M ("User")->where($where)->count();
			
			if($type==1)
			{
				$count_desc .= "部分会员($count)人";
			}
			else if ($type==2)
			{
				$count_desc .= "二级会员($count)人";
			}
			else if($type==3)
			{
				$count_desc .= "三级会员($count)人";
			}
			$Page = new Page ( $count, 10 ); 
			$Page -> setConfig('header', '人');
			$Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
			$show = $Page->show (); // 分页显示输出
			$result = M ("User")->where($where)->limit ( $Page->firstRow . ',' . $Page->listRows )->select();
			$this->assign ( "result", $result );
			$this->assign ( "page", $show ); 
			$this->assign ( "count_desc", $count_desc ); 
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$this->assign ( "dongjia_time", $this->dongjia_time );
			$this->display ();
			}
		
	}
	
	
	
	
	public function member_top()
	{
		if ($_GET ['id']) {
			$id = $_GET ["id"];
			$where = array('id'=>$id);
			$usersresult = M ("User")->where($where)->find();
			
			if(empty($usersresult))
			{
				exit('未查到该用户信息');
			}
			
			$db = new Model();
			$top_info = $db->query("SELECT level_id, SUM( totalprice ) AS total, wx_info
			FROM (
			SELECT level_id, totalprice
			FROM  `wemall_order_level` 
			INNER JOIN  `wemall_order` ON  `wemall_order_level`.`order_id` =  `wemall_order`.`orderid`
			) AS t1
			INNER JOIN  `wemall_user` ON  `wemall_user`.id = level_id
			GROUP BY level_id
			ORDER BY `total` DESC , `t1`.`level_id` DESC 
			LIMIT 0 , 100");

			
			$ALL_COUNT = $db->query("SELECT sum(`totalprice`) as total FROM `wemall_order_level` inner join `wemall_order` on `wemall_order_level`.`order_id` =  `wemall_order`.`orderid` where `level_id`=$usersresult[id]");
			$all_buy_price = empty($ALL_COUNT[0]['total']) ? 0 : $ALL_COUNT[0]['total'];
				
			$query_info = $db->query("SELECT  SUM( totalprice ) AS total
			FROM (
			SELECT level_id, totalprice
			FROM  `wemall_order_level` 
			INNER JOIN  `wemall_order` ON  `wemall_order_level`.`order_id` =  `wemall_order`.`orderid`
			) AS t1
			GROUP BY level_id
	having total>$all_buy_price");

			$my_top = count($query_info);
			
			$this->assign ( "top_info", $top_info );
			$this->assign ( "users", $usersresult );
			$this->assign ( "my_top", $my_top );
			
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$this->display();
		}
	}
	
	public function get_user_dianpu_info($users)
	{
		$dianpu_info = array();
		if($users['member']==1)
		{
			$wx_info = json_decode($users['wx_info'],true);
			$img = !empty($wx_info['headimgurl'])?$wx_info['headimgurl']:'../Public/Static/images/defult.jpg';
			$dianpu_info['headimgurl'] = $img;
			$dianpu_info['nickname'] = $wx_info['nickname'];
		}
		else
		{
			$result_l = M("User")->where(array('id'=>$users['l_id']))->find();
			
			
			$dianpu_info = $this->get_l_info($users['l_id']);
		}
		
		return $dianpu_info;
	}
	
	public function get_l_info($l_id)
	{
		$result_l = M("User")->where(array('id'=>$l_id))->find();
		if(!empty($result_l))
		{
			$wx_info = json_decode($result_l['wx_info'],true);
			$l_name = $wx_info['nickname'];
			$img = !empty($wx_info['headimgurl'])?$wx_info['headimgurl']:'../Public/Static/images/defult.jpg';
			$headimgurls = $img;
		}
		
		include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/Public/Conf/button_config.php'; 
		
		$l_name = !empty($l_name) ? $l_name : ''.$message_name.'';
		$headimgurl = !empty($headimgurls) ? $headimgurls : $headimgurl;
		
		$headimgurl = !empty($headimgurl)?$headimgurl:'../Public/Static/images/defult.jpg';
		
		$info['headimgurl'] = $headimgurl;
		$info['nickname'] = $l_name;
		
		return $info;
	}
	
	public function index_info() {
		$this->init('index_info');
		$info = R ( "Api/Api/gettheme" );
		C ( "DEFAULT_THEME", $info ["theme"] );
		$this->assign ( "info", $info );
		$usersresult = array();
		$menuresult = R ( "Api/Api/getmenu" );
		$this->assign ( "menu", $menuresult );
		
		$goodsresult = R ( "Api/Api/getgood" );
		$this->assign ( "goods", $goodsresult );
		
		if ($_GET ['uid']) {
			$uid = $_GET ["uid"];
			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
		} 

		$this->assign ( "users", $usersresult );
		
		
		include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/Public/Conf/button_config.php'; 
		$this->assign ( "message_name", $message_name );
		$this->assign ( "link_config", $link_config );
		
		$dianpu_info = $this->get_user_dianpu_info($usersresult);
		$this->assign ( "dianpu_info", $dianpu_info );
		
		$this->display ();
	}
	
	public function index() {
		$this->init();
		if ($_GET ['uid']) {
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$menuresult = R ( "Api/Api/getmenu" );
			$this->assign ( "menu", $menuresult );
			
			$goodsresult = R ( "Api/Api/getgood" );
			
			
			$uid = $_GET ["uid"];
			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
			if (empty($usersresult)) {
				$usersresult = M("user")->where(array("uid"=>$_GET['uid']))->find();
			}
			$alipay = M ( "Alipay" )->find ();
			if ($alipay) {
				$this->assign ( "alipay", 1 );
			}
			
			//查询是否第一次购买
			$zhekou = 0;
			if(file_exists('./Public/Conf/zhekou.php'))
			{
				require './Public/Conf/zhekou.php';
			}
			
			if($zhekou>0)
			{
				$order = $result = M ( "Order" )->where ( array (
					"user_id" => $usersresult["id"],
					"pay_status" => 1
				) )->find ();
				if(!empty($order))
				{
					foreach($goodsresult as $key=>$info)
					{
						$goodsresult[$key]['price'] = $info['price'] * ($zhekou/100);
					}
				}
			}
		
			$this->assign ( "goods", $goodsresult );
			//print_r($goodsresult);
			$this->assign ( "users", $usersresult );
			
			$buy_cnt = $this->get_day_buy();
			$this->assign ( "buy_cnt", $buy_cnt );
			include dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/Public/Conf/button_config.php'; 
			$this->assign ( "config_good_pic", $config_good_pic );
			
			if($usersresult['member'] == 1){
				//$this->error('您已经成为会员', 'http://' . $_SERVER ['SERVER_NAME'].U('App/Index/member', array('uid'=>$uid)));
				$this->redirect('App/Index/member', array('uid'=>$uid));
				/*echo "location.href='index.php?g=App&m=Index&a=member'</script>";*/
				
				//echo "<div style='text-align:center; font-size:40px;margin-top:50px; color:#f00;'>您已经是vip会员！</div>";
			}else{
				$this->display ();
			}
			
			
		} else {
			echo '请使用微信访问!';
		}
	}
	
	public function goods() {
		$this->init('goods');
		if ($_GET ['uid']) {
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			$mwhere['pid'] = 0;
			$menuresult = R ( "Api/Api/getmenu" ,array (
					$mwhere
			) );
			$this->assign ( "menu", $menuresult );
			
			$where['menu_id'] = 23;
			$goodsresult = R ( "Api/Api/getgood",array (
					$where
			)  );
		
			//查询是否第一次购买
			$zhekou = 0;
			if(file_exists('./Public/Conf/zhekou.php'))
			{
				require './Public/Conf/zhekou.php';
			}
			
			if($zhekou>0)
			{
				$order = $result = M ( "Order" )->where ( array (
					"user_id" => $usersresult["id"],
					"pay_status" => 1
				) )->find ();
				if(!empty($order))
				{
					foreach($goodsresult as $key=>$info)
					{
						$goodsresult[$key]['price'] = $info['price'] * ($zhekou/100);
					}
				}
			}
			
			$this->assign ( "goods", $goodsresult );
			$this->assign ( "etype", 1 );
			$this->display ();
		} else {
			echo '请使用微信访问!';
		}
	}
	public function goods_detail(){
		$this->init();
		if ($_GET ['uid']) {
			$goods_id = $_GET['goods_id'];
			$goodsresult = $result = M ( "good" )->where ( array (
					"id" => $goods_id,
					"status" => 1
			) )->find ();
				
			$this->assign ( "goods", $goodsresult );
			
			$userdata = M ( "User" )->where ( array (
				"uid" => $_GET ['uid']
			) )->find ();
			$this->assign ( "users", $userdata );
			
			$user_arr = M("User")->where(array("member"=> 1,'dummy'=>0))->limit(0,15)->order("id desc")->select();
			foreach($user_arr as $key=>$value ){
				$user_arr[$key][] = $value;
				$user_arr[$key]['time'] = date('m-d H:i',strtotime($value['time']));
				$user_arr[$key]['phone'] = substr_replace($value['phone'] ,"****", 3 , 4);
			}
			
			$this->assign ( "user_arr", $user_arr );
			
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			
			$this->display ();
		} else {
			echo '请使用微信访问!';
		}
	}
	
	public function get_day_buy()
	{
		$tixianinfo = array();			
		$tixianinfo['dingdan'] = 20000;		
		if(file_exists('./Public/Conf/tixianinfo.php'))		
		{			
			require './Public/Conf/tixianinfo.php';			
			$tixianinfo = json_decode($tixianinfo,true); 		
		}
		
		$buy_cnt = $tixianinfo['dingdan'];
		
		//今日支付数量
		$where = array();
		$where ["pay_status"] = 1;
		
		$date = date('Y-m-d 00:00:00');
		
		$where ["time"] = array('egt',$date);
		$count = M ( "Order" )->where($where)->count();
		
		return $can_buycnt = $count>=$buy_cnt ? 0 : $buy_cnt-$count;
	}
	
	public function member()
	{
		$this->init('member');
		if ($_GET ['uid']) {
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			$uid = $_GET["uid"];
			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
			$where = array();
			$where ["status"] = 0;
			$where ["level_id"] = $usersresult['id'];
			$start_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["level_id"] = $usersresult['id'];
			$over_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 2;
			$where ["level_id"] = $usersresult['id'];
			$confirm_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 3;
			$where ["level_id"] = $usersresult['id'];
			$add_over_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 0;
			$where ["uid"] = $usersresult['id'];
			$get_start_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["uid"] = $usersresult['id'];
			$get_end_price = M ( "Tx_record" )->where($where)->sum('price');

			$start_price = $start_price>0 ? $start_price : 0;
			$over_price = $over_price>0 ? $over_price : 0;
			$confirm_price = $confirm_price>0 ? $confirm_price : 0;
			$add_over_price = $add_over_price>0 ? $add_over_price : 0;
			$get_end_price = $get_end_price>0 ? $get_end_price : 0;
			$get_start_price = $get_start_price>0 ? $get_start_price : 0;
			
			$all_price = $start_price+$over_price+$confirm_price+$add_over_price;
			
			$all_price = bcadd($start_price, $over_price, 2);
			$all_price = bcadd($all_price, $confirm_price, 2);
			$all_price = bcadd($all_price, $add_over_price, 2);
			
			$this->assign ( "start_price", $start_price );
			$this->assign ( "over_price", $over_price );
			$this->assign ( "confirm_price", $confirm_price );
			$this->assign ( "add_over_price", $add_over_price );
			$this->assign ( "get_start_price", $get_start_price );
			$this->assign ( "get_end_price", $get_end_price );
			$this->assign ( "all_price", $all_price );
			$this->assign ( "users", $usersresult );
			
// 			if($usersresult['id'] =='11287'){
// 				$gebie = '<div style="position:fixed;right:0px; top:50px"><a style="display:block;width:50px;height:50px;background:#f00;" href="/index.php?g=App&amp;m=Index&amp;a=orderslist1">测试</a></div>';//用于测试用的 只有dahua这个账号登录才会显示的内容
// 			};
			
			
			$this->assign ( "gebie", $gebie);
		
			$type_a_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>1,'id'=>$usersresult['id']));
			$type_b_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>2,'id'=>$usersresult['id']));
			$type_c_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>3,'id'=>$usersresult['id']));
			$this->assign ( "type_a_url", $type_a_url );
			$this->assign ( "type_b_url", $type_b_url );
			$this->assign ( "type_c_url", $type_c_url );
			
			$all_cnt = $usersresult['a_cnt']+$usersresult['b_cnt']+$usersresult['c_cnt'];
			$this->assign ( "all_cnt", $all_cnt );
			
			$where = array();
			$where ["uid"] = $usersresult['id'];
			
			$tx_record = M ( "Tx_record" )->where($where)->select();

			$this->assign ( "tx_record", $tx_record );
			
			if($usersresult['member']==1 && (empty($usersresult['ticket']) || empty($usersresult['url'])))
			{
				//$this->add_member($usersresult['id'],$usersresult['uid']);
			}
			
			$where = array();
			$where ["level_id"] = $usersresult['id'];
			$all_buy = M ( "Order_level" )->where($where)->count();
			
			$where = array();
			$where ["status"] = 0;
			$where ["level_id"] = $usersresult['id'];
			$all_over_buy = M ( "Order_level" )->where($where)->count();
			
			$all_start_buy = $all_buy - $all_over_buy;//已付款
			
			$this->assign ( "all_buy", $all_start_buy );//已付款
			$this->assign ( "all_over_buy", $all_over_buy );//未付款
			$this->assign ( "all_count_buy", $all_buy );//总付款
			
			//营业额
			/*$result = M ( "Good" )->find ();
			$all_buy_price = $result['price']*$all_buy;
			$this->assign ( "all_buy_price", $all_buy_price );*/
			$db = new Model();
            $ALL_COUNT = $db->query("SELECT sum(`totalprice`) as total FROM `wemall_order_level` inner join `wemall_order` on `wemall_order_level`.`order_id` =  `wemall_order`.`orderid` where `level_id`=$usersresult[id]");
			$all_buy_price = empty($ALL_COUNT[0]['total']) ? 0 : $ALL_COUNT[0]['total'];
			$this->assign ( "all_buy_price", $all_buy_price );
			
			//推荐人
			$l_name = $this->get_l_info($usersresult['l_id']);
			$l_name = $l_name['nickname'];
			$l_name = !empty($l_name) ? $l_name : ''.$message_name.'';
			$this->assign ( "l_name", $l_name );
			
			$ticket = R ( "Api/Api/ticket", array ($usersresult));
			$this->assign ( "ticket", $ticket['ticket'] );
			$this->assign ( "dongjia_time", $this->dongjia_time );
			$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Member&a=register&mid='.$usersresult['id'];
			$this->assign ( "member_url", $url );
			if(!$_GET["etype"]){
				$_GET["etype"] = 1;
			}
			$this->assign("etype", $_GET["etype"]);
			
			
			
			$tool_url = 'http://' . $_SERVER ['SERVER_NAME']."/index.php?g=App&m=Index&a=fuli&type=1";
			$fuli_url = 'http://' . $_SERVER ['SERVER_NAME']."/index.php?g=App&m=Index&a=tool&type=1";
			$this->assign ( "tool_url", $tool_url );
			$this->assign ( "fuli_url", $fuli_url );
			
			
			$this->display ();
		} else {
			echo '请使用微信访问!';
		}
	}
	
	public function fetchgooddetail() {
		$where ["id"] = $_POST ["id"];
		$result = M ( "Good" )->where ( $where )->find ();
		if ($result) {
			$this->ajaxReturn ( $result );
		}
	}
	
	public function orderslist(){
		$this->init();
		if($_GET['uid']){
			$uid = $_GET['uid'];
			$user_id = R ( "Api/Api/getuser", array (
					$uid 
			) );

			$result = M ( "Order" )->where ( array (
					"user_id" => $user_id ["id"] 
			) )->order ( 'id desc' )->select ();
			
			$where = array();
			$where ["status"] = 0;
			$where ["uid"] = $result['id'];
			$get_start_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["uid"] = $result['id'];
			$get_end_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$get_end_price = $get_end_price>0 ? $get_end_price : 0;
			$get_start_price = $get_start_price>0 ? $get_start_price : 0;
			$this->assign ( "get_start_price", $get_start_price );
			$this->assign ( "get_end_price", $get_end_price );
			
			foreach($result as $key=>$info)
			{
				if($info['pay_status']==0 && $info['pay_style']=='微信支付')
				{
					$orderid = $info['orderid'];
					$totalprice = $info['totalprice'];
					$cartdata = json_decode($info['cartdata'],true);
					$result[$key]['pay_url'] = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/pay',array('totalprice'=>$totalprice,'cart_name'=>$cartdata[0]['name'],'uid'=>$uid,'orderid'=>$orderid));
				}
				
				$order_info = json_decode($info['order_info'],true);
				$result[$key]['order_info_num'] = !empty($order_info['num']) ? $order_info['num'] : '';
				$result[$key]['order_info_name'] = !empty($order_info['name']) ? $order_info['name'] : '';
				
				$result[$key]['time'] = date('Y-m-d H:i:s',strtotime(substr($info['orderid'],0,14)));
			
			}
			
			$this->assign("result", $result);
			$this->assign ( "users", $user_id );
			$this->assign("etype" , 1);
			$this->assign("page_type" , 'order');
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			$this->display ();
		}else{
			echo "请用微信打开";
		}
		
		
	}


	public function orderslist1(){
		$this->init();
		if($_GET['uid']){
			$uid = $_GET['uid'];
			$user_id = R ( "Api/Api/getuser", array (
					$uid 
			) );

			$result = M ( "Order" )->where ( array (
					"user_id" => $user_id ["id"] 
			) )->order ( 'id desc' )->select ();
			
			$where = array();
			$where ["status"] = 0;
			$where ["uid"] = $result['id'];
			$get_start_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["uid"] = $result['id'];
			$get_end_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$get_end_price = $get_end_price>0 ? $get_end_price : 0;
			$get_start_price = $get_start_price>0 ? $get_start_price : 0;
			$this->assign ( "get_start_price", $get_start_price );
			$this->assign ( "get_end_price", $get_end_price );
			
			foreach($result as $key=>$info)
			{
				if($info['pay_status']==0 && $info['pay_style']=='微信支付')
				{
					$orderid = $info['orderid'];
					$totalprice = $info['totalprice'];
					$cartdata = json_decode($info['cartdata'],true);
					$result[$key]['pay_url'] = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/pay',array('totalprice'=>$totalprice,'cart_name'=>$cartdata[0]['name'],'uid'=>$uid,'orderid'=>$orderid));
				}
				
				$order_info = json_decode($info['order_info'],true);
				$result[$key]['order_info_num'] = !empty($order_info['num']) ? $order_info['num'] : '';
				$result[$key]['order_info_name'] = !empty($order_info['name']) ? $order_info['name'] : '';
				
				$result[$key]['time'] = date('Y-m-d H:i:s',strtotime(substr($info['orderid'],0,14)));
			
			}
			
			$this->assign("result", $result);
			$this->assign ( "users", $user_id );
			$this->assign("etype" , 1);
			$this->assign("page_type" , 'order');
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			$this->display ();
		}else{
			echo "请用微信打开";
		}
		
		
	}
	
	
	public function confirm_order()
	{
		$out_trade_no = $_GET['id'];
		$order = M ("Order")->where(array('orderid'=>$out_trade_no))->find();
	
		if(!empty($out_trade_no) && $order['order_status'] == 1)
		{
			$this->confirm_order_status($out_trade_no);
			
		}
		$this->redirect('App/Index/member', array('page_type'=>'order')); 
	}
	
	public function confirm_order_status($out_trade_no)
	{
		$order = M ("Order")->where(array('orderid'=>$out_trade_no))->find();
		if($order['order_status'] == 1){//确认发货后才能确认收货
			M ("Order")->where(array('orderid'=>$out_trade_no))->save (array('order_status'=>2));
			$tixianinfo = array();
			$tixianinfo['shouhuo'] = 7;
			$tixianinfo['tixian'] = 7;
			$tixianinfo['jine'] = 50;
			if(file_exists('./Public/Conf/tixianinfo.php'))
			{
				require './Public/Conf/tixianinfo.php';
				$tixianinfo = json_decode($tixianinfo,true); 
			}
			//确认收货  $active_time = date('Y-m-d H:i:s',strtotime("+$tixianinfo[tixian] days"));	
			$active_time = date('Y-m-d H:i:s');
			M ("Order_level")->where(array('order_id'=>$out_trade_no))->save (array('status'=>2,'active_time'=>$active_time));
			
			R ( "Api/Api/orderover", array ($out_trade_no) );
		}else{
			$this->redirect('App/Index/orderslist'); 
		}	
	}
	
	public function getorders() {
		//$uid = $_POST ['uid'];
		$uid = $_SESSION['uid'];
		$user_id = M ( "User" )->where ( array (
				"uid" => $uid 
		) )->find ();
		$result = M ( "Order" )->where ( array (
				"user_id" => $user_id ["id"] 
		) )->order ( 'id desc' )->select ();
		
		foreach($result as $key=>$info)
		{
			if($info['pay_status']==0 && $info['pay_style']=='微信支付')
			{
				$orderid = $info['orderid'];
				$totalprice = $info['totalprice'];
				$cartdata = json_decode($info['cartdata'],true);
				$result[$key]['pay_url'] = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/pay',array('totalprice'=>$totalprice,'cart_name'=>$cartdata[0]['name'],'uid'=>$uid,'orderid'=>$orderid));
			}
			
			$order_info = json_decode($info['order_info'],true);
			$result[$key]['order_info_num'] = !empty($order_info['num']) ? $order_info['num'] : '';
			$result[$key]['order_info_name'] = !empty($order_info['name']) ? $order_info['name'] : '';
			
			$result[$key]['time'] = date('Y-m-d H:i:s',strtotime(substr($info['orderid'],0,14)));
		}
		
		$this->ajaxReturn ( $result );
	}
	//退货
	public function txpublish(){
		$id = $_GET ['id'];
	
		$result = M ( "Order" )->where ( array('id'=>$id) )->find();
		if(!$result){
			exit("没有找到订单");
		}
		
		//获取openid
		import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );
		$config = M ( "Wxconfig" )->where ( array (
				"id" => "1" 
		) )->find ();
		$options = array (
				'token' => $config ["token"], // 填写你设定的key
				'encodingaeskey' => $config ["encodingaeskey"], // 填写加密用的EncodingAESKey
				'appid' => $config ["cappid"], // 填写高级调用功能的app id
				'appsecret' => $config ["cappsecret"], // 填写高级调用功能的密钥
				'partnerid' => $config ["partnerid"], // 财付通商户身份标识
				'partnerkey' => $config ["partnerkey"], // 财付通商户权限密钥Key
				'paysignkey' => $config ["paysignkey"]  // 商户签名密钥Key
				);
		
		$weObj = new Wechat ( $options );
		$tianshi = $weObj->getOauthAccessToken();
		if(!$tianshi)
		{
			$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/txpublish",$_GET);
			$url = $weObj->getOauthRedirect($callback,'','snsapi_base');
			header("Location: $url");
			exit();
			
		}
		
		if(file_exists('./Public/Conf/tixianinfo.php'))
		{
			require './Public/Conf/tixianinfo.php';
			$hbconfig = json_decode($tixianinfo,true); 
		}else{
			exit('请设置微信红包参数');
		}
		
		//红包金额以分为单位。所以乘100
		$txinfo['amount']=$result['totalprice']*100;
	   
		$where = array('id'=>$result['user_id']);
		$usersresult = M ("user")->where($where)->find();
		if(empty($usersresult))
		{
			exit('未查到该用户信息');
		}
		
		$txinfo['useropenid']= $tianshi['openid'];;
		$txinfo=array_merge($txinfo,$hbconfig);
		import ( 'RedCash', APP_PATH . 'Common/hongbao', '.class.php' );
		$cash = new RedCash($txinfo);
		$msg = $cash->sendRedCash();
		$strMsg = '';
		$returnUrl = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/orderslist');
		if ($msg['err_code'] == 'SUCCESS') {
			$strMsg = '退货成功！';
			$result = R ( "Api/Api/txpublish", array (
				$id,
			) );	
			die("<script>alert('".$strMsg."');location='".$returnUrl."';</script>");
		}else{
			$strMsg = '退货发送失败！'.$msg['err_code_des'];
			die("<script>alert('".$strMsg."');location='".$returnUrl."';</script>");
			
		}
	
	}
	
	//提现
	public function tx()
	{
		import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );
		$config = M ( "Wxconfig" )->where ( array (
				"id" => "1" 
		) )->find ();
		$options = array (
				'token' => $config ["token"], // 填写你设定的key
				'encodingaeskey' => $config ["encodingaeskey"], // 填写加密用的EncodingAESKey
				'appid' => $config ["cappid"], // 填写高级调用功能的app id
				'appsecret' => $config ["cappsecret"], // 填写高级调用功能的密钥
				'partnerid' => $config ["partnerid"], // 财付通商户身份标识
				'partnerkey' => $config ["partnerkey"], // 财付通商户权限密钥Key
				'paysignkey' => $config ["paysignkey"]  // 商户签名密钥Key
				);
		
		$weObj = new Wechat ( $options );
		$tianshi = $weObj->getOauthAccessToken();
		if(!$tianshi)
		{
			$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/tx",$_GET);
			$url = $weObj->getOauthRedirect($callback,'','snsapi_base');
			header("Location: $url");
			exit();
			
		}
		$this->init('member');
		if ($_GET ['uid']) {
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			$uid = $_GET["uid"];
			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
			$where = array();
			$where ["status"] = 0;
			$where ["level_id"] = $usersresult['id'];
			$start_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["level_id"] = $usersresult['id'];
			$over_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 2;
			$where ["level_id"] = $usersresult['id'];
			$confirm_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 3;
			$where ["level_id"] = $usersresult['id'];
			$add_over_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 0;
			$where ["uid"] = $usersresult['id'];
			$get_start_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["uid"] = $usersresult['id'];
			$get_end_price = M ( "Tx_record" )->where($where)->sum('price');

			$start_price = $start_price>0 ? $start_price : 0;
			$over_price = $over_price>0 ? $over_price : 0;
			$confirm_price = $confirm_price>0 ? $confirm_price : 0;
			$add_over_price = $add_over_price>0 ? $add_over_price : 0;
			$get_end_price = $get_end_price>0 ? $get_end_price : 0;
			$get_start_price = $get_start_price>0 ? $get_start_price : 0;
			
			$all_price = $start_price+$over_price+confirm_price+add_over_price;
			
			$all_price = bcadd($start_price, $over_price, 2);
			$all_price = bcadd($all_price, $confirm_price, 2);
			$all_price = bcadd($all_price, $add_over_price, 2);
			
			$this->assign ( "start_price", $start_price );
			$this->assign ( "over_price", $over_price );
			$this->assign ( "confirm_price", $confirm_price );
			$this->assign ( "add_over_price", $add_over_price );
			$this->assign ( "get_start_price", $get_start_price );
			$this->assign ( "get_end_price", $get_end_price );
			$this->assign ( "all_price", $all_price );
			$this->assign ( "users", $usersresult );
		
			$type_a_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>1,'id'=>$usersresult['id']));
			$type_b_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>2,'id'=>$usersresult['id']));
			$type_c_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>3,'id'=>$usersresult['id']));
			$this->assign ( "type_a_url", $type_a_url );
			$this->assign ( "type_b_url", $type_b_url );
			$this->assign ( "type_c_url", $type_c_url );
			
			$all_cnt = $usersresult['a_cnt']+$usersresult['b_cnt']+$usersresult['c_cnt'];
			$this->assign ( "all_cnt", $all_cnt );
			
			$where = array();
			$where ["uid"] = $usersresult['id'];
			$tx_record = M ( "Tx_record" )->where($where)->select();

			$this->assign ( "tx_record", $tx_record );
			
			if($usersresult['member']==1 && (empty($usersresult['ticket']) || empty($usersresult['url'])))
			{
				//$this->add_member($usersresult['id'],$usersresult['uid']);
			}
			
			$where = array();
			$where ["level_id"] = $usersresult['id'];
			$all_buy = M ( "Order_level" )->where($where)->count();
			
			$where = array();
			$where ["status"] = 0;
			$where ["level_id"] = $usersresult['id'];
			$all_over_buy = M ( "Order_level" )->where($where)->count();
			
			$all_start_buy = $all_buy - $all_over_buy;//已付款
			
			$this->assign ( "all_buy", $all_start_buy );//已付款
			$this->assign ( "all_over_buy", $all_over_buy );//未付款
			$this->assign ( "all_count_buy", $all_buy );//总付款
			
			//营业额
			/*$result = M ( "Good" )->find ();
			$all_buy_price = $result['price']*$all_buy;
			$this->assign ( "all_buy_price", $all_buy_price );*/
			$db = new Model();
            $ALL_COUNT = $db->query("SELECT sum(`totalprice`) as total FROM `wemall_order_level` inner join `wemall_order` on `wemall_order_level`.`order_id` =  `wemall_order`.`orderid` where `level_id`=$usersresult[id]");
			$all_buy_price = empty($ALL_COUNT[0]['total']) ? 0 : $ALL_COUNT[0]['total'];
			$this->assign ( "all_buy_price", $all_buy_price );
			
			//推荐人
			$l_name = $this->get_l_info($usersresult['l_id']);
			$l_name = $l_name['nickname'];
			$l_name = !empty($l_name) ? $l_name : ''.$message_name.'';
			$this->assign ( "l_name", $l_name );
			
			$ticket = R ( "Api/Api/ticket", array ($usersresult));
			$this->assign ( "ticket", $ticket['ticket'] );
			$this->assign ( "dongjia_time", $this->dongjia_time );
			$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Member&a=register&mid='.$usersresult['id'];
			$this->assign ( "member_url", $url );
			if(!$_GET["etype"]){
				$_GET["etype"] = 2;
			}
			$this->assign("etype", $_GET["etype"]);
			$this->assign("openid",$tianshi['openid']);
			$this->display ();
		} else {
			echo '请使用微信访问!';
		}
	}
	
	public function addtxorder()
	{
		$useropenid = $_POST['openid'];
		//$uid = htmlspecialchars ( $_POST ['uid'] );
		$uid = $_SESSION['uid'];
		
		$price = $_POST ['userData'] [0] [value];
		$bank_name = $_POST ['userData'] [1] [value];
		$bank_num = $_POST ['userData'] [2] [value];
		$name = $_POST ['userData'] [3] [value];
		$tel = $_POST ['userData'] [4] [value];
		$openid = $_POST ['userData'] [5] [value];
		if(empty($uid) || empty($price))/*|| empty($bank_name) || empty($bank_num) || empty($name) || empty($tel)*/
		{
			$result['error'] = true;
			$result['msg'] = '请填写提现金额';
			$this->ajaxReturn ( $result );
		}
		
		$tixianinfo = array();
		$tixianinfo['shouhuo'] = 7;
		$tixianinfo['tixian'] = 7;
		$tixianinfo['jine'] = 50;
		if(file_exists('./Public/Conf/tixianinfo.php'))
		{
			require './Public/Conf/tixianinfo.php';
			$tixianinfo = json_decode($tixianinfo,true); 
		}
		
		if($price<$tixianinfo['jine'])
		{
			$result['error'] = true;
			$result['msg'] = '提现金额必须大于'.$tixianinfo['jine'];
			$this->ajaxReturn ( $result );
		}
		
		$userdata = M ( "User" )->where ( array (
				"uid" => $uid 
		) )->find ();
		
		if ($userdata) {
			if(!$userdata['member'])
			{
				$result['error'] = true;
				$result['msg'] = '只有购买微商云订单才能提现';
				$this->ajaxReturn ( $result );
			}
			$orderresult = M ( "Order" )->where ( array (
						"user_id" => $userdata['id'] 
				) )->select ();
			$tx_result = 0;		
			foreach($orderresult as $rinfo)
			{
				if($rinfo['order_status'] > 1){
					$tx_result = 1;
				}
			}
			if($tx_result == 0)
			{
				$result['error'] = true;
				$result['msg'] = '您还没有确认收货，确认收货后才能提现';
				$this->ajaxReturn ( $result );
			}
			//判断今天的时间0点-24点
			$start_time = date("Y-m-d H:i:s",strtotime(date('Y-m-d', time())));

			$where['uid'] = $userdata['id'];
			$where['status'] = 1;
			$where["time"] = array('egt', $start_time);
			$tx_total = M ("tx_record")->field('price')->where($where)->select();
			$old_price = 0;			
			foreach($tx_total as $val){
				$old_price += $val['price'];
			}
			if($old_price+$price > 200){
				$result['error'] = true;
				$result['msg'] = '每日限额200，如须特殊服务请联系客服。';
				$this->ajaxReturn ( $result );
			}
			if($userdata['price']<$price)
			{
				$result['error'] = true;
				$result['msg'] = '余额不足，无法申请提现';
				$this->ajaxReturn ( $result );
			}
			else
			{
				$data = array();
				$data ["uid"] = $userdata['id'];
				$data ["price"] = $price;
				$data ["bank_name"] = $bank_name;
				$data ["bank_num"] = $bank_num;
				$data ["name"] = $name;
				$data ["tel"] = $tel;
				//这里修改红包自动发送
				$record['id'] = M ( "Tx_record" )->add ( $data );
				
				if(file_exists('./Public/Conf/tixianinfo.php'))
				{
					require './Public/Conf/tixianinfo.php';
					$hbconfig = json_decode($tixianinfo,true); 
				}else{
					exit('请设置微信红包参数');
				}
				
				$id = $record['id'];
				$where = array('id'=>$id);
				$tx_recordresult = M ("tx_record")->where($where)->find();
				if(empty($tx_recordresult))
				{
					exit('未查到该提现信息');
				}
			   	$userid=$tx_recordresult['uid'];
			   	//红包金额以分为单位。所以乘100
			   	$txinfo['amount']=$tx_recordresult['price']*100;
			   
				$where = array('id'=>$userid);
				$usersresult = M ("user")->where($where)->find();
				if(empty($usersresult))
				{
					exit('未查到该用户信息');
				}
				$txinfo['useropenid']= $openid;
			   	$txinfo=array_merge($txinfo,$hbconfig);
			   	import ( 'RedCash', APP_PATH . 'Common/hongbao', '.class.php' );
				$cash = new RedCash($txinfo);
				$msg = $cash->sendRedCash();
				$strMsg = '';
				if ($msg['err_code'] == 'SUCCESS') {
					//$strMsg = '红包发送成功！';
					$result = R ( "Api/Api/txpayComplete", array ($id,	) );
					//$rmsg = true;
					
					$data = array();
					$data ["id"] = $userdata['id'];
					$data ["price"] = $userdata['price']-$price;
					$data['pay_time'] = date("Y-m-d H:i:s");
					M ( "User" )->save ( $data );
					$result['error'] = false;
					$result['msg'] = '恭喜您已经提现成功';
					$this->ajaxReturn ( $result );
					
				}else{
					$strMsg = '红包发送失败！'.$msg['err_code_des'];
					/*$data = array();
					$data ["id"] = $userdata['id'];
					$data ["price"] = $userdata['price']-$price;
					M ( "User" )->save ( $data );*/
					$result['error'] = true;
					$result['msg'] = $strMsg ;
					$this->ajaxReturn ( $result );
				}
				
				
				/*$rmsg = R ( "Admin/Tx/payComplete", array ($record['id']) );
				
				if($rmsg == false){
					$result['error'] = true;
					$result['msg'] = '提现失败，请等待审核。';
					$this->ajaxReturn ( $result );
				}else{
					$data = array();
					$data ["id"] = $userdata['id'];
					$data ["price"] = $userdata['price']-$price;
					M ( "User" )->save ( $data );
					
					$result['error'] = false;
					$result['msg'] = '恭喜您已经提现成功';
					$this->ajaxReturn ( $result );
				}*/
			}
		}
		else
		{
			$result['error'] = true;
			$result['msg'] = '系统繁忙，请重新提交提现申请';
			$this->ajaxReturn ( $result );
		}
	}
	//充值
	public function cz(){
		$this->init('member');
		if ($_GET ['uid']) {
			$info = R ( "Api/Api/gettheme" );
			C ( "DEFAULT_THEME", $info ["theme"] );
			$this->assign ( "info", $info );
			$uid = $_GET["uid"];
			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
			$where = array();
			$where ["status"] = 0;
			$where ["level_id"] = $usersresult['id'];
			$start_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["level_id"] = $usersresult['id'];
			$over_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 2;
			$where ["level_id"] = $usersresult['id'];
			$confirm_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 3;
			$where ["level_id"] = $usersresult['id'];
			$add_over_price = M ( "Order_level" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 0;
			$where ["uid"] = $usersresult['id'];
			$get_start_price = M ( "Tx_record" )->where($where)->sum('price');
			
			$where = array();
			$where ["status"] = 1;
			$where ["uid"] = $usersresult['id'];
			$get_end_price = M ( "Tx_record" )->where($where)->sum('price');

			$start_price = $start_price>0 ? $start_price : 0;
			$over_price = $over_price>0 ? $over_price : 0;
			$confirm_price = $confirm_price>0 ? $confirm_price : 0;
			$add_over_price = $add_over_price>0 ? $add_over_price : 0;
			$get_end_price = $get_end_price>0 ? $get_end_price : 0;
			$get_start_price = $get_start_price>0 ? $get_start_price : 0;
			
			$all_price = $start_price+$over_price+confirm_price+add_over_price;
			
			$all_price = bcadd($start_price, $over_price, 2);
			$all_price = bcadd($all_price, $confirm_price, 2);
			$all_price = bcadd($all_price, $add_over_price, 2);
			
			$this->assign ( "start_price", $start_price );
			$this->assign ( "over_price", $over_price );
			$this->assign ( "confirm_price", $confirm_price );
			$this->assign ( "add_over_price", $add_over_price );
			$this->assign ( "get_start_price", $get_start_price );
			$this->assign ( "get_end_price", $get_end_price );
			$this->assign ( "all_price", $all_price );
			$this->assign ( "users", $usersresult );
			//充值列表
			$where ["uid"] = $usersresult['id'];
			$cz_record = M ( "Cz_record" )->where($where)->select();
			$this->assign ( "cz_record", $cz_record );
			
			$type_a_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>1,'id'=>$usersresult['id']));
			$type_b_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>2,'id'=>$usersresult['id']));
			$type_c_url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/member_info',array('type'=>3,'id'=>$usersresult['id']));
			$this->assign ( "type_a_url", $type_a_url );
			$this->assign ( "type_b_url", $type_b_url );
			$this->assign ( "type_c_url", $type_c_url );
			
			$all_cnt = $usersresult['a_cnt']+$usersresult['b_cnt']+$usersresult['c_cnt'];
			$this->assign ( "all_cnt", $all_cnt );
			
			$where = array();
			$where ["uid"] = $usersresult['id'];
			$tx_record = M ( "Tx_record" )->where($where)->select();

			$this->assign ( "tx_record", $tx_record );
			
			if($usersresult['member']==1 && (empty($usersresult['ticket']) || empty($usersresult['url'])))
			{
				//$this->add_member($usersresult['id'],$usersresult['uid']);
			}
			
			$where = array();
			$where ["level_id"] = $usersresult['id'];
			$all_buy = M ( "Order_level" )->where($where)->count();
			
			$where = array();
			$where ["status"] = 0;
			$where ["level_id"] = $usersresult['id'];
			$all_over_buy = M ( "Order_level" )->where($where)->count();
			
			$all_start_buy = $all_buy - $all_over_buy;//已付款
			
			$this->assign ( "all_buy", $all_start_buy );//已付款
			$this->assign ( "all_over_buy", $all_over_buy );//未付款
			$this->assign ( "all_count_buy", $all_buy );//总付款
			
			//营业额
			/*$result = M ( "Good" )->find ();
			$all_buy_price = $result['price']*$all_buy;
			$this->assign ( "all_buy_price", $all_buy_price );*/
			$db = new Model();
            $ALL_COUNT = $db->query("SELECT sum(`totalprice`) as total FROM `wemall_order_level` inner join `wemall_order` on `wemall_order_level`.`order_id` =  `wemall_order`.`orderid` where `level_id`=$usersresult[id]");
			$all_buy_price = empty($ALL_COUNT[0]['total']) ? 0 : $ALL_COUNT[0]['total'];
			$this->assign ( "all_buy_price", $all_buy_price );
			
			//推荐人
			$l_name = $this->get_l_info($usersresult['l_id']);
			$l_name = $l_name['nickname'];
			$l_name = !empty($l_name) ? $l_name : ''.$message_name.'';
			$this->assign ( "l_name", $l_name );
			
			$ticket = R ( "Api/Api/ticket", array ($usersresult));
			$this->assign ( "ticket", $ticket['ticket'] );
			$this->assign ( "dongjia_time", $this->dongjia_time );
			$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.'g=App&m=Member&a=register&mid='.$usersresult['id'];
			$this->assign ( "member_url", $url );
			if(!$_GET["etype"]){
				$_GET["etype"] = 2;
			}
			$this->assign("etype", $_GET["etype"]);
			$this->assign("openid",$tianshi['openid']);
			$this->display ();
		} else {
			echo '请使用微信访问!';
		}
		
	}
	public function addczorder(){

		$uid = $_SESSION['uid'];
		$price = $_POST['price'];
	
		$this->redirect('App/Index/czpay', array('price'=>$price,'uid'=>$uid)); 
		exit;
		//echo 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/czpay',array('price'=>$price,'uid'=>$uid));
			
	}
	
	public function czpay(){		
		$price = $_GET['price'];
		$uid = $_GET['uid'];
		
		import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );
		$config = M ( "Wxconfig" )->where ( array (
				"id" => "1" 
		) )->find ();
		$options = array (
				'token' => $config ["token"], // 填写你设定的key
				'encodingaeskey' => $config ["encodingaeskey"], // 填写加密用的EncodingAESKey
				'appid' => $config ["cappid"], // 填写高级调用功能的app id
				'appsecret' => $config ["cappsecret"], // 填写高级调用功能的密钥
				'partnerid' => $config ["partnerid"], // 财付通商户身份标识
				'partnerkey' => $config ["partnerkey"], // 财付通商户权限密钥Key
				'paysignkey' => $config ["paysignkey"]  // 商户签名密钥Key
				);
		
		$weObj = new Wechat ( $options );
		$tianshi = $weObj->getOauthAccessToken();
		if(!$tianshi)
		{
			$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/czpay",$_GET);
			$url = $weObj->getOauthRedirect($callback,'','snsapi_base');
			header("Location: $url");
			exit();
			
		}
		$userdata = M ( "User" )->where ( array (
				"uid" => $uid
		) )->find ();
		
		$this->assign ( "users", $userdata );
		
		if(empty($userdata))
		{
			exit('用户信息错误');
		}
		$appid = $options['appid'];
		$mch_id = $options['partnerid'];
		$out_trade_no = date ( "YmdHis" ) . mt_rand ( 1, 9 );
		$body = "充值";
		$total_fee = $price * 100;
		$notify_url = 'http://' . $_SERVER ['SERVER_NAME'];
		$spbill_create_ip = $_SERVER["REMOTE_ADDR"];
		$nonce_str = $weObj->generateNonceStr();
	
		$pay_xml = $weObj->createPackageXml($appid,$mch_id,$nonce_str,$body,$out_trade_no,$total_fee,$spbill_create_ip,$notify_url,$tianshi['openid']);
		
		$pay_xml =  $weObj->get_pay_id($pay_xml);
		
		if($pay_xml['err_code']=="ORDERPAID")
		{
			$this->redirect('App/Index/czover', array('out_trade_no'=>$out_trade_no,'uid'=>$uid,'price'=>$price)); 
			eixt();
		}
		
		$prepay_id = $pay_xml['prepay_id'];
		$jsApiObj["appId"] = $appid;
		$timeStamp = time();
	    $jsApiObj["timeStamp"] = "$timeStamp";
	    $jsApiObj["nonceStr"] = $nonce_str;
		$jsApiObj["package"] = "prepay_id=$prepay_id";
	    $jsApiObj["signType"] = "MD5";
	    $jsApiObj["paySign"] = $weObj->getPaySignature($jsApiObj);

		$url = json_encode($jsApiObj);
		
		$returnUrl = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/czover',array('out_trade_no'=>$out_trade_no,'uid'=>$uid,'price'=>$price));

		$info = R ( "Api/Api/gettheme" );
		C ( "DEFAULT_THEME", $info ["theme"] );
		
		$this->assign ("price", $price );
		$this->assign ("info", $info );
		$this->assign ( "url", $url );
		$this->assign("etype", 2);
		$this->assign ( "returnUrl", $returnUrl );
		$this->display ();
	}
	
	public function czover(){
		if(empty($_SESSION['uid']))
		{
			$uid=$_GET['uid'];
		}else
		{
			$uid=$_SESSION['uid'];
		}
		if (!$uid) {
			exit('请先关注该公众号');
		}
		
		$userdata = M ( "User" )->where ( array (
				"uid" => $uid) )->find ();
	
		if (!$userdata) {
			exit('未找到用户信息');
		}
		
		$data['out_trade_no'] = $_GET['out_trade_no'];
		$data['price'] = $_GET['price'];
		$data['status'] = 1;
		$data['uid'] = $userdata['id'];
		$data['time'] = date('Y-m-d H:i:s',time());
		M ( "Cz_record" )->add($data);
		//$updata = array();
		//$updata['uid'] = $uid;
		$price = $userdata['price'] + $_GET['price'];
		M ( "User" )->where(array('uid'=>$uid))->save ( array('price'=>$price) );
		
		$this->redirect('App/Index/cz', array('uid'=>$uid)); 
	}
	//添加订单
	public function addorder() {
		//$uid = htmlspecialchars ( $_POST ['uid'] );
		$uid = $_SESSION['uid'];
		
		$username = $_POST ['userData'] [0] [value];
		$phone = $_POST ['userData'] [1] [value];
		//$pay = $_POST ['userData'] [2] [value];
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(strpos($agent,"icroMessenger")) {
			$pay=2;
		}
		else
		{
			$pay=1;
		}
		
		//$weixin = $_POST ['userData'] [2] [value];
		//$address = $_POST ['userData'] [2] [value];
		$address = $_POST ['user_address'];
		$note = $_POST ['userData'] [6] [value];
		$totalprice = $_POST ['totalPrice'];
		$cartdata = stripslashes ( $_POST ['cartData'] );
		
		if($totalprice<=0)
		{
			exit();
		}
		
		$buy_cnt = $this->get_day_buy();
		if($buy_cnt<=0)
		{
			$msg['msg']='抱歉，今天已经没有货了，请明天再来买';
			$this->ajaxReturn($msg);die;
		}
		
		if(empty($address))
		{
			$msg['msg']='请选择地市';
			$this->ajaxReturn($msg);die;
		}
		
		$orderid = date ( "YmdHis" ) . mt_rand ( 1, 9 );
		$time = date ( "Y/m/d H:i:s" );
		switch ($pay) {
			case 0 :
				$pay_style = "货到付款";
				break;
			case 1 :
				$pay_style = "支付宝";
			case 2 :
				$pay_style = "微信支付";
				break;
		}
		
		$data ["orderid"] = $orderid;
		$data ["totalprice"] = $totalprice;
		$data ["pay_style"] = $pay_style;
		$data ["pay_status"] = "0";
		$data ["note"] = $note;
		$data ["order_status"] = '0';
		$data ["time"] = $time;
		$data ["cartdata"] = $cartdata;
		$data['adress'] = $address;
		$data['phone'] = $phone;
		$data['shouhuoname'] = $username;
		
		$userdata = M ( "User" )->where ( array (
				"uid" => $uid 
		) )->find ();
		if ($userdata) {
			$user_id = $userdata ["id"];
			$user ["id"] = $user_id;
			$user ["username"] = $username;
			$user ["phone"] = $phone;
			$user ["address"] = $address;
			$user ["weixin"] = $weixin;

			M ( "User" )->save ( $user );
			
			$data ["user_id"] = $user_id;
			M ( "Order" )->add ( $data );
			
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
			
			$wx_info = json_decode($userdata['wx_info'],true);
			
			
			$yongjin = array();
			$yongjin['yongjin_1'] = 28;
			$yongjin['yongjin_2'] = 13;
			$yongjin['yongjin_3'] = 9;
			if(file_exists('./Public/Conf/yongjin.php'))
			{
				require './Public/Conf/yongjin.php';
				$yongjin = json_decode($yongjin,true); 
			}
		
			
			if($userdata['l_id']>0)
			{
				$user_order_level = array();
				$user_order_level['order_id'] = $orderid;
				$user_order_level['status'] = 0;
				$user_order_level['level_id'] = $userdata['l_id'];
				$user_order_level['level_type'] = 1;
				$my_price = $user_order_level['price'] = round($totalprice*($yongjin['yongjin_1']/100));
				M ( "Order_level" )->add ( $user_order_level );
				
				$l_id_info = M ( "User" )->where(array('id'=>$userdata['l_id']))->find();
				
				if(strlen($l_id_info['uid'])>10)
				{
					/*$data = array();
					$data['touser'] = $l_id_info['uid'];
					$data['msgtype'] = 'text';
					$data['text']['content'] = '您的一级会员【'.$wx_info['nickname'].'】
在'.$time.'下单，预计您将获得【'.$my_price.'】积分。下家成功付款后您才可以获得积分哦！';
					$weObj->sendCustomMessage($data);*/
				}
				
			}
			
			if($userdata['l_b']>0)
			{
				$user_order_level = array();
				$user_order_level['order_id'] = $orderid;
				$user_order_level['status'] = 0;
				$user_order_level['level_id'] = $userdata['l_b'];
				$user_order_level['level_type'] = 2;
				$my_price = $user_order_level['price'] = round($totalprice*($yongjin['yongjin_2']/100));
				M ( "Order_level" )->add ( $user_order_level );
				
				$l_b_info = M ( "User" )->where(array('id'=>$userdata['l_b']))->find();
				
				if(strlen($l_b_info['uid'])>10)
				{
					/*$data = array();
					$data['touser'] = $l_b_info['uid'];
					$data['msgtype'] = 'text';
					$data['text']['content'] = '您的二级会员【'.$wx_info['nickname'].'】
在'.$time.'下单，预计您将获得【'.$my_price.'】积分。下家成功付款后您才可以获得积分哦！';
					$weObj->sendCustomMessage($data);*/
				}
			}
			
			if($userdata['l_c']>0)
			{
				$user_order_level = array();
				$user_order_level['order_id'] = $orderid;
				$user_order_level['status'] = 0;
				$user_order_level['level_id'] = $userdata['l_c'];
				$user_order_level['level_type'] = 3;
				$my_price = $user_order_level['price'] = round($totalprice*($yongjin['yongjin_3']/100));
				M ( "Order_level" )->add ( $user_order_level );
				
				$l_c_info = M ( "User" )->where(array('id'=>$userdata['l_c']))->find();
				
				if(strlen($l_c_info['uid'])>10)
				{
					/*$data = array();
					$data['touser'] = $l_c_info['uid'];
					$data['msgtype'] = 'text';
					$data['text']['content'] = '您的三级会员【'.$wx_info['nickname'].'】
在'.$time.'下单，预计您将获得【'.$my_price.'】积分。下家成功付款后您才可以获得佣金哦！';
					$weObj->sendCustomMessage($data);*/
				}
			}
			
		} else {
			/*$user ["uid"] = $uid;
			$user ["username"] = $username;
			$user ["phone"] = $phone;
			$user ["address"] = $address;
			$user_id = M ( "User" )->add ( $user );
			$data ["user_id"] = $user_id;
			M ( "Order" )->add ( $data );*/
			exit('请先关注该公众号');
		}
		
		$alipay = M ( "Alipay" )->find ();
		if ($pay == 1 && $alipay) {
			echo 'http://' . $_SERVER ['SERVER_NAME'] . __ROOT__ . '/api/wapalipay/alipayapi.php?WIDseller_email=' . $alipay ['alipayname'] . '&WIDout_trade_no=' . $orderid . '&WIDsubject=' . $orderid . '&WIDtotal_fee=' . $totalprice;
		}
		else if($pay==2)
		{
			//$cartdata = json_decode($cartdata,true);'cart_name'=>$cartdata[0]['name'],
			echo 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/pay',array('totalprice'=>$totalprice,'uid'=>$uid,'orderid'=>$orderid));
		}
	}	
	public function pay(){
		
		$totalprice = $_GET['totalprice'];
		//$cart_names = $_GET['cart_name'];
		//$openid = $_GET['uid'];
		$openid = $_SESSION['uid'];
		$orderid = $_GET['orderid'];
		
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(!strpos($agent,"Android")) {
		$this->assign ( "tbtspic", "ios.png");
		}else
		{
		$this->assign ( "tbtspic", "android.png");
		}
		if(!strpos($agent,"icroMessenger")) {
			$alipay = M ( "Alipay" )->find ();
			$url = 'http://' . $_SERVER ['SERVER_NAME'] . __ROOT__ . '/api/wapalipay/alipayapi.php?WIDseller_email=' . $alipay ['alipayname'] . '&WIDout_trade_no=' . $orderid . '&WIDsubject=' . $orderid . '&WIDtotal_fee=' . $totalprice;
		
			header("Location: $url");
			exit();
		}
		
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
		
		if(strlen($openid)<=10)
		{
			$info = $weObj->getOauthAccessToken();
			if(!$info)
			{
				$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/pay",$_GET);
				$url = $weObj->getOauthRedirect($callback,'','snsapi_base');
				header("Location: $url");
				exit();
			}
			else
			{
				$openid = $info['openid'];
			}
		}

		$order_info = M('Order')->where(array('orderid'=>$orderid))->find();
		
		if(empty($order_info))
		{
			exit('订单信息错误');
		}
		
		$cartdata = json_decode($order_info['cartdata'],true);
		$cart_name = $cartdata[0]['name'];
		$cart_num = $cartdata[0]['num'];
		$cart_price = $cartdata[0]['price'];
		$cart_goods_id = $cartdata[0]['id'];
		
		$goodsdata = M ( "Good" )->where ( array (
				"id" => $cart_goods_id
		) )->find ();
		
		
		$userdata = M ( "User" )->where ( array (
				"uid" => $_SESSION['uid']
		) )->find ();
		
		if(empty($userdata))
		{
			exit('用户信息错误');
		}
		
		$username = $userdata['username'];
		$phone = $userdata['phone'];
		$address = $userdata['address'];
		
		$this->assign ( "goodsdata", $goodsdata );
		$this->assign ( "username", $username );
		$this->assign ( "phone", $phone );
		$this->assign ( "address", $address );
		$this->assign ( "cart_name", $cart_name );
		$this->assign ( "cart_num", $cart_num );
		$this->assign ( "cart_price", $cart_price );
		
		$coptions = array (
				'token' => $config ["token"], // 填写你设定的key
				'encodingaeskey' => $config ["encodingaeskey"], // 填写加密用的EncodingAESKey
				'appid' => $config ["cappid"], // 填写高级调用功能的app id
				'appsecret' => $config ["cappsecret"], // 填写高级调用功能的密钥
				'partnerid' => $config ["partnerid"], // 财付通商户身份标识
				'partnerkey' => $config ["partnerkey"], // 财付通商户权限密钥Key
				'paysignkey' => $config ["paysignkey"]  // 商户签名密钥Key
				);
		
		$cweObj = new Wechat ( $coptions );
		$cinfo = $cweObj->getOauthAccessToken();
		if(!$cinfo)
		{
			$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/pay",$_GET);
			$url = $cweObj->getOauthRedirect($callback,'','snsapi_base');
			header("Location: $url");
			exit();
		}
		
		$appid = $coptions['appid'];
		$mch_id = $coptions['partnerid'];
		$out_trade_no = $orderid;
		$body = $cart_name;
		$total_fee = $cart_price*$cart_num*100;
		$notify_url = 'http://' . $_SERVER ['SERVER_NAME'];
		$spbill_create_ip = $_SERVER["REMOTE_ADDR"];
		$nonce_str = $cweObj->generateNonceStr();
		$copenid = $cinfo['openid'];
	
		
		$pay_xml = $cweObj->createPackageXml($appid,$mch_id,$nonce_str,$body,$out_trade_no,$total_fee,$spbill_create_ip,$notify_url,$copenid);
		
		$pay_xml =  $cweObj->get_pay_id($pay_xml);
		
		if($pay_xml['err_code']=="ORDERPAID")
		{
			$this->redirect('App/Index/payover', array('out_trade_no'=>$out_trade_no,'uid'=>$_SESSION['uid'])); 
			eixt();
		}
		
		$prepay_id = $pay_xml['prepay_id'];
		
		$jsApiObj["appId"] = $appid;
		$timeStamp = time();
	    $jsApiObj["timeStamp"] = "$timeStamp";
	    $jsApiObj["nonceStr"] = $nonce_str;
		$jsApiObj["package"] = "prepay_id=$prepay_id";
	    $jsApiObj["signType"] = "MD5";
	    $jsApiObj["paySign"] = $cweObj->getPaySignature($jsApiObj);

		$url = json_encode($jsApiObj);
		$returnUrl = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/payover',array('out_trade_no'=>$out_trade_no,'uid'=>$_SESSION['uid']));

		
		$info = R ( "Api/Api/gettheme" );
		C ( "DEFAULT_THEME", $info ["theme"] );
		
		
		$this->assign ( "price", $cart_price*$cart_num );
		$this->assign ( "info", $info );
		$this->assign ( "url", $url );
		$this->assign ( "returnUrl", $returnUrl );
		$this->display ();
	}
	
	public function payover()
	{
	
		/*if(empty($_SESSION['uid']))
		{
			exit('请先关注该公众号');
		}*/
		if(empty($_SESSION['uid']))
		{
			$uid=$_GET['uid'];
		}else
		{
			$uid=$_SESSION['uid'];
		}
		if (!$uid) {
			exit('请先关注该公众号');
		}
		
		$out_trade_no = $_GET['out_trade_no'];
		$order_info = M ("Order")->where(array('orderid'=>$out_trade_no))->find();
		if(empty($order_info))
		{
			exit('未找到订单信息');
		}
		$userdata = M ( "User" )->where ( array (
				"uid" => $uid,"id" => $uid,'_logic'=>'or') )->find ();
	
		if (!$userdata) {
			exit('未找到用户信息');
		}
		$user_data = $userdata;
		
		//R ( "Api/Api/orderover", array ($out_trade_no) );
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
				    /*更新付款时间*/
				    $where = array();
				    $where['id'] = $user_info['id'];
				    $where['pay_time'] = date("Y-m-d H:i:s");
				    M('User')->where($where)->save();
				    
					$data = array();
					$data['touser'] = $user_info['uid'];
					$data['msgtype'] = 'text';
					$data['text']['content'] = '【'.$wx_info['nickname'].'】在'.date('Y-m-d H:i:s').'加入您的队伍，已经成为您的'.$level_text.'级员工，从此开始为您赚米，您获得的【￥'.$info['price'].'】积分，详情点击个人中心，我的主页查看。';
					$weObj->sendCustomMessage($data);
				}
			}
		}
		
		if($userdata["member"]!=1)
		{
			$user_id = $userdata ["id"];
			$member_obj = D ( "Member" );
			$result = $member_obj->add_meber($user_id);

			//生成分享图片
			$url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/get_pic',array('uid'=>$uid));
		
			$oCurl = curl_init();
			curl_setopt($oCurl, CURLOPT_URL, $url);
			curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($oCurl, CURLOPT_TIMEOUT,1);
			$sContent = curl_exec($oCurl);
			$aStatus = curl_getinfo($oCurl);
			curl_close($oCurl);
		
		}
		
		$data['id']=$userdata['id'];
		$data['jifen']=$order_info['totalprice'];
		$data['pay_time'] = date("Y-m-d H:i:s");
		$userdata = M ( "User" )->save($data);
		
		if (! empty ( $out_trade_no )) {
			M ("Order")->where(array('orderid'=>$out_trade_no))->save (array('pay_status'=>1));
			M ("Order_level")->where(array('order_id'=>$out_trade_no))->save (array('status'=>1));
			//-----------------		
			//微云莱网络默认自动发货 有问题请联系QQ 53867784
			//如果是会员商品自动发货
			$cart_info = json_decode($order_info['cartdata']);
			
			R ( "Api/Api/autofahuo", array ($out_trade_no,) );
			
			/**
			lbh  修该用户 级别 和到期时间
			每份30天 ， 购买3个月 赠一个月
			**/
			if($order_info[order_status] == 0){
				$this_time = time();
				$add_time = ($cart_info[0]->num + floor($cart_info[0]->num/3)) * 300 * 240 * 600 * 600;
				$data = array();
				$data['member'] = 1;
				$data['id']=$user_data['id'];
				$data['limit_time'] = $user_data['limit_time'] > $this_time ? $user_data['limit_time'] + $add_time : $this_time + $add_time;
				M("User")->save($data);
			}
			//end自动发货
		}
		if(strpos($agent,"icroMessenger")) {
			$this->redirect('App/Index/member', array('uid'=>$uid,'page_type'=>'order')); 

		}else{
			echo "<script>alert('支付成功');location.href='index.php?g=App&m=Index&a=member' </script>";
			//$this->redirect('App/Index/member', array('uid'=>$uid));
			//redirect('http://' . $_SERVER ['SERVER_NAME'].'/paysuccess/success.html');
		}
		
	}
	
	public function get_pic()
	{
		if($_GET['uid'])
		{
			$usersresult = M ( "User" )->where ( array (
					"uid" => $_GET['uid']
			) )->find ();
			
			$ticket = R ( "Api/Api/ticket", array (
					$usersresult 
			) );
		}
	}
	
	// app start
	public function appregister() {
		$username = $_POST ["username"];
		$password = $_POST ["password"];
		$phone = $_POST ["phone"];
		
		if ($username && $password && $phone) {
			$find = M ( "User" )->where ( array (
					"phone" => $phone 
			) )->select ();
			if (! $find) {
				$data ["username"] = $username;
				$data ["phone"] = $phone;
				$data ["password"] = md5 ( $password );
				$data ["uid"] = date ( "His" ) . mt_rand ( 1, 9 );
				$data ["time"] = date ( "Y/m/d H:i:s" );
				
				$result = M ( "User" )->add ( $data );
				if ($result) {
					$this->ajaxReturn ( $result );
				}
			}
		}
	}
	public function applogin() {
		$phone = $_POST ["phone"];
		$password = md5 ( $_POST ["password"] );
		
		if ($phone && $password) {
			$result = M ( "User" )->where ( array (
					"phone" => $phone,
					"password" => $password 
			) )->find ();
			if ($result) {
				$this->ajaxReturn ( $result );
			}
		}
	}
	public function appgetgood() {
		$result = M ( "Good" )->select ();
		if ($result) {
			$this->ajaxReturn ( $result );
		}
	}
	public function appdoaddress() {
		$do = $_POST ["do"];
		//$uid = $_POST ["uid"];
		$uid = $_SESSION ["uid"];
		
		switch ($do) {
			case 1 :
				$result = M ( "User" )->where ( array (
						"uid" => $uid 
				) )->find ();
				if ($result) {
					$this->ajaxReturn ( $result );
				}
				break;
			case 2 :
				$address = $_POST ["address"];
				$data ["address"] = $address;
				$result = M ( "User" )->where ( array (
						"uid" => $uid 
				) )->save ( $data );
				if ($result) {
					$this->ajaxReturn ( $result );
				}
				break;
			default :
				;
				break;
		}
	}
	public function appdoorder() {
		$do = $_POST ["do"];
		//$uid = $_POST ["uid"];
		$uid = $_SESSION ["uid"];
		
		switch ($do) {
			case 1 :
				$cartdata = $_POST ["cartdata"];
				$note = $_POST ["note"];
				$cartarray = json_decode ( $cartdata, true );
				$totalprice = 0;
				for($i = 0; $i < count ( $cartarray ); $i ++) {
					unset ( $cartarray [$i] ["id"] );
					unset ( $cartarray [$i] ["image"] );
					$totalprice += $cartarray [$i] ["num"] * $cartarray [$i] ["price"];
				}
				$cartdata = json_encode ( $cartarray );
				$orderid = date ( "YmdHis" ) . mt_rand ( 1, 9 );
				$time = date ( "Y/m/d H:i:s" );
				$user = M ( "User" )->where ( array (
						"uid" => $uid 
				) )->find ();
				
				$data ["orderid"] = $orderid;
				$data ["totalprice"] = $totalprice;
				$data ["pay_style"] = "货到付款";
				$data ["pay_status"] = "0";
				$data ["note"] = $note;
				$data ["order_status"] = '0';
				$data ["time"] = $time;
				$data ["cartdata"] = $cartdata;
				$data ["user_id"] = $user ["id"];
				
				$result = M ( "Order" )->add ( $data );
				if ($result) {
					$this->ajaxReturn ( $result );
				}
				
				break;
			case 2 :
				$id = M ( "User" )->where ( array (
						"uid" => $uid 
				) )->find ();
				$id = $id ["id"];
				
				$result = M ( "Order" )->where ( array (
						"user_id" => $id 
				) )->select ();
				if ($result) {
					$this->ajaxReturn ( $result );
				}
				break;
			case 3 :
				$orderid = $_POST ["orderid"];
				$result = M ( "Order" )->where ( array (
						"orderid" => $orderid 
				) )->find ();
				
				$user = M ( "User" )->where ( array (
						"uid" => $uid 
				) )->find ();
				
				$result = array_merge ( $result, $user );
				
				if ($result) {
					$this->ajaxReturn ( $result );
				}
				
				break;
			default :
				;
				break;
		}
	}
	public function trial() {
		$this->init('member');
		if ($_GET ['uid']) {
			$user_info = M('User')->where(array('uid'=>$_GET['uid']))->find();
			if(!$user_info) die("用户不存在");
			$this_time = time();
			$rurl = U("member");
			if($this_time - $user_info['trial_time'] < (30 * 60 * 60 * 24)){
				die("<script>alert('30天内只允许申请一次临时VIP！');location='{$rurl}';</script>");
			}
			if($user_info['limit_time'] - $this_time > 0 and $user_info['member'] == 1 ){
				die("<script>alert('您已经是VIP会员，无需申请试用！');location='{$rurl}';</script>");
			}
			$data = array();
			$data['id'] = $user_info['id'];
			$data['limit_time'] = $this_time + 60 * 60 * 24 * 3;
			$data['member']	 = "2";
			$data['trial_time'] = $this_time;
			M("User")->save($data);
			//echo D()->_sql();
			//成为试用vip生成二维码
//微云莱网络 旺旺ID: jie6059	 有问题请联系QQ 53867784		
			$user_id = $user_info ["id"];
			$member_obj = D ( "Member" );
			$result = $member_obj->add_meber($user_id);

			//生成分享图片
			$url = 'http://' . $_SERVER ['SERVER_NAME']. U('App/Index/get_pic',array('uid'=>$uid));
		
			$oCurl = curl_init();
			curl_setopt($oCurl, CURLOPT_URL, $url);
			curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($oCurl, CURLOPT_TIMEOUT,1);
			$sContent = curl_exec($oCurl);
			$aStatus = curl_getinfo($oCurl);
			curl_close($oCurl);
		//生成二维码结束
			die("<script>alert('恭喜！您已获得3天VIP试用！');location='{$rurl}';</script>");
		} else {
			echo '请使用微信访问!';
		}
	}
	public function my_ticket()
	
	{
		$this->init('member');
		
		if ($_GET ['uid']) {
			$uid = $_GET["uid"];

			$usersresult = R ( "Api/Api/getuser", array (
					$uid 
			) );
			$ticket = R ( "Api/Api/ticket", array ($usersresult));
		//	echo '<h1 align="center"><span style="font-size:42px;">已购买,二维码永久有效.试用会员只支持3天</span></h1>';
			echo '<h1 align="center"><span style="color:#E53333;font-size:40px;">长按以下图片→保存到手机相册</span></h1>';
			echo '<h1 align="center"><span style="color:#E53333;font-size:35px;">然后分享到朋友圈，只要她们下单你会得到提成。</span></h1>';
			echo "<div align=center><img src='{$ticket['ticket']}' style='width:90%;height:90%;' /></div>";
			//$this->assign ( "ticket", $ticket['ticket'] );
			//$this->display ();
		} else {
			echo '请使用微信访问!';
		}
	}
	
	public function test(){
		//$member_obj = D ( "Member" );
		//$result = $member_obj->add_meber('6');
		echo file_get_contents("https://mp.weixin333.qq.com/cgi-bin/showqrcode?ticket=gQGn8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2traTRJOTNsbXBYalNtMnhaMlFlAAIE7g23VQMEAAAAAA==");
	}
	public function fensi()
	{
		$this->get_weixininfo("fensi");
		$fsurl="http://".$_SERVER['SERVER_NAME']."/hufen/list.php?uid=".$_GET['uid'];
		header("Location: $fsurl");
				exit();
	}

	public function get_weixininfo($type='index')
	{
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if(strpos($agent,"icroMessenger") ) {
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
			
			$info = $weObj->getOauthAccessToken();
			if(!$info)
			{
				$callback = 'http://' . $_SERVER ['SERVER_NAME']. U("App/Index/$type",$_GET);
				$url = $weObj->getOauthRedirect($callback,'','snsapi_base');
				header("Location: $url");
				exit();
			}
			else
			{
				$_SESSION['uid'] = $_POST['uid'] = $_GET['uid'] = $info['openid'];
			}
		}
	
		if(!empty($_SESSION["uid"]) && empty($_GET['uid']))
		{
			$_GET['uid'] = $_SESSION["uid"];
		}
	}

}