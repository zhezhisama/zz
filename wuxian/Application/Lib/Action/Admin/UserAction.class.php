<?php

class UserAction extends PublicAction {

	function _initialize() {

		parent::_initialize ();

	}

	public function index() {

		import ( 'ORG.Util.Page' );

		$m = M ( "User" );
        $id = (int)$_GET['id'];
        $type = (int)$_GET['type'];
        $sort = isset($_GET['sort']) ? "pay_time desc,id desc" :"id desc" ;
        $level = $_GET['level'];
        $level_id = $_GET['level_id'];
        $asort = isset($_GET['asort']) ? $_GET['asort'] : '' ;
        
        $where = array();
        
        //搜索下级条件
        switch ($level){
            case 1:
                $where['l_id'] = $level_id;
                break;
            case 2:
                $where['l_b'] = $level_id;
                break;
            case 3:
                $where['l_c'] = $level_id;
                break;
        }
        //搜索上级条件
	    switch ($type){
	        case 1://无上级
	            $where['l_id'] = 0; 
	            $where['l_b'] = 0;
	            $where['l_c'] = 0;
	            break;
	        case 2://一位上级
	            $where['l_id'] = array('gt',0);
	            $where['l_b'] = 0;
	            $where['l_c'] = 0;
	            break;
	        case 3://二位上级
	            $where['l_id'] = array('gt',0); 
	            $where['l_b'] = array('gt',0);
	            $where['l_c'] = 0;
	            break;
	        case 4://三位上级
	            $where['l_id'] = array('gt',0);
	            $where['l_b'] = array('gt',0);
	            $where['l_c'] = array('gt',0);
	            break;
	        default:
	           break;
	    }
        /* //搜索下级数量排序
        switch($asort){
            case 1:
                $sort = "a_cnt asc";
                break;
            case 2:
                $sort = "b_cnt asc";
                break;
            case 3:
                $sort = "c_cnt asc";
                break;
        } */
        
        
        //搜索固定id
        if($id)
            $where['id'] = $id;
     
		$count = $m->where($where)->count (); // 查询满足要求的总记录数

		$Page = new Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数

		$Page -> setConfig('header', '条记录');

        $Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li>  <li>%linkPage%</li>  <li>%nextPage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)

		$show = $Page->show (); // 分页显示输出

		$result = $m->where($where)->limit ( $Page->firstRow . ',' . $Page->listRows )->order($sort)->select ();
        $tx_id = array();//当前用户id
        $users = array();
        foreach ($result as $key=>$val){
             $tx_id[] = $val['id'];
             $users[$val['id']] = $val;
        }
        $tx_id = implode(',',$tx_id);
        $tx_where['uid'] = array('in', $tx_id );
        $record = M('tx_record')->field('id,uid,price')->where($tx_where)->select();
        
        $tx_user = array(); //定义提现uid为键值的数组
        foreach ($record as $rkey=>$rval){
            $tx_user[$rval['uid']]['tx_price'] +=$rval['price'];
        }
    
        foreach ($users as $ukey=>$uval){
             
            if( isset($tx_user[$ukey]) ){
                $users[$ukey]['tx_price'] = $tx_user[$ukey]['tx_price'];    
            }else{
                $users[$ukey]['tx_price'] = 0;
            }
        }
  
        $this->assign ( "result", $users );
		$this->assign ( "type", $type );
		//$this->assign('get',$_GET);
		$this->assign ( "page", $show ); // 赋值分页输出

		$this->display ();

	}

	/**
	 * 添加下级用户
	 */
	public function adduser() {
	    $l_id = $_GET ["l_id"];
	    
	    import ( 'ORG.Util.Page' );
	    $m = M("dummy_user");
		//查询所有虚拟用户
		$where['status'] = 0;
		$count = $m->where($where)->count (); // 查询满足要求的总记录数
		
		$Page = new Page ( $count, 12 ); // 实例化分页类 传入总记录数和每页显示的记录数
		
		$Page -> setConfig('header', '条记录');
		
		$Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li>  <li>%linkPage%</li>  <li>%nextPage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)
		
		$show = $Page->show (); // 分页显示输出
		
		$result = $m->where($where)->limit ( $Page->firstRow . ',' . $Page->listRows )->order('id asc')->select ();
	    
		$this->assign ( "l_id", $l_id );
		$this->assign ( "result", $result ); // 赋值分页输出	
		$this->display ();
	}
	/**
	 * 选中虚拟用户 插入user表中
	 */
	public function insertuser() {
		
		$l_id = !empty($_GET['l_id']) ? $_GET['l_id'] : 0;//一级
		$id = (int)$_GET['id'];    //虚拟用户id
	
		//查找虚拟用户
		$where['id'] = $id;
		$where['status'] = '0';
		$dummy = M("dummy_user")->where($where)->find();
		if(empty($dummy)){
		    $this->error("没有找到虚拟用户");
		}
		
		$info['nickname']		= $dummy['nickname'];
		$info['sex']			= $dummy['sex'];
		$info['city']			= $dummy['city'];
		$info['province']		= $dummy['province'];
		$info['country']		= $dummy['country'];
		$info['subscribe'] 		= 1;
		$info['subscribe_time'] = time();
		$info['openid'] 		= "";
		$info['remark'] 		= "";
		$info['groupid'] 		= 0;
		$info['language']  		= "zh_CN";
		$info["headimgurl"] = 'http://'.$_SERVER['HTTP_HOST'].'/Public/Uploads/'.$dummy['headimgurl'];

		
		$data['wx_info'] = json_encode($info);
		$data['dummy'] = 1;
		$data['l_id'] = $l_id;
		
		//增加上级分销人数
		if($l_id){//增加一级人数
			$where["id"] = $l_id;
			$results =  M ( "User" )->where($where)->find ();
			$a_info = array();
			$a_info['id'] = $l_id;
			$a_info['a_cnt'] = $results['a_cnt']+1;
			$user_id = M ( "User" )->save ( $a_info );
			
		}
		if($results['l_id']){
			$where["id"] = $results['l_id'];
			$b_results =  M ( "User" )->where($where)->find ();
			$b_info = array();
			$b_info['id'] = $b_results['id'];
			$b_info['b_cnt'] = $b_results['b_cnt']+1;
			
			$user_id = M ( "User" )->save ( $b_info );
			$data["l_b"] = $b_results['id'];
		}
		if($b_results['l_id']){
			$where["id"] = $b_results['l_id'];
			$c_results =  M ( "User" )->where($where)->find ();
			$c_info = array();
			$c_info['id'] = $c_results['id'];
			$c_info['c_cnt'] = $c_results['c_cnt']+1;
			$user_id = M ( "User" )->save ( $c_info );
			$data["l_c"] = $c_results['id'];
		}
		$data['uid'] = "tianshiweishang".time();
		$result = M ( "User" )->add ( $data );
	       
		if ($result) {
            //添加操作成功更改虚拟用户表状态
            $dummy_data['id'] = $id;
            $dummy_data['status'] = 1;
            M("dummy_user")->save($dummy_data);
			$this->success ( "操作成功!" , U('Admin/User/index') );

		}
	}
	/**
	*	推送积分
	*/
	public function pushpoints(){
		$id = $_GET ["id"];
		
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
		$userinfo = M("User")->where(array('id'=>$id))->find();
		if($userinfo['member'] == 1){
		    $this->error("该会员已经推送过积分不支持该操作");
		}
		$info = json_decode($userinfo['wx_info'],true);//一级成员信息
	    $data['id'] = $id;
	    $data['member'] = 1;
	    M('User')->save($data);
	    //会员添加订单
	    $orderid = date ( "YmdHis" ) . mt_rand ( 1, 9 );
	    $cartdata = '[{"name":"微商云订单","num":"1","price":"29.9","id":"22"}]';
	    $data["user_id"] = $id;
	    $data["orderid"] = $orderid;
	    $data["totalprice"] = "29.9";
	    $data["pay_style"] = "微信支付";
	    $data["pay_status"] = "1";
	    $data["note"] = '虚拟用户推送';
	    $data["order_status"] = '3';
	    $data["time"] = date ( "Y/m/d H:i:s" );
	    $data["cartdata"] = $cartdata;
	    $data['adress'] = "";
	    $data['phone'] = "";
	    $data['shouhuoname'] = $info['nickname'];
	    M ( "Order" )->add ( $data );
	    
	    
		if($userinfo['l_id']){//增加一级人数
			$where["id"] = $userinfo['l_id'];
			$results =  M ( "User" )->where($where)->find ();
			
			if(strlen($results['uid'])>10 && $results['dummy'] == 0)
			{
			    $user_order_level = array();
			    $user_order_level['order_id'] = $orderid;
			    $user_order_level['status'] = 3;
			    $user_order_level['level_id'] = $results['id'];
			    $user_order_level['level_type'] = 1;
			    $user_order_level['price'] = 12;
			    $user_order_level['active_time'] = date ( "Y-m-d H:i:s" );
			    M ( "Order_level" )->add ( $user_order_level );
			    
			    
			    $a_info['id'] = $results['id'];
				$a_info['price'] = $results['price']+12;
				$a_info['pay_time'] = date('Y-m-d H:i:s');
				$user_id = M ( "User" )->save ( $a_info );
				
				$data = array();
				$data['touser'] = $results["uid"];
				$data['msgtype'] = 'text';
				$data['text']['content'] = '【'.$info['nickname'].'】在'.date('Y-m-d H:i:s').'加入您的队伍，已经成为您的一级员工，从此开始为您赚米，您获得的【￥12】积分，详情点击个人中心，我的主页查看。';
				$weObj->sendCustomMessage($data);

			}
			
		}
		if($userinfo['l_b']){
			$where["id"] = $userinfo['l_b'];
			$b_results =  M ( "User" )->where($where)->find ();
			
			if(strlen($b_results['uid'])>10 && $b_results['dummy'] == 0 )
			{
			    $user_order_level = array();
			    $user_order_level['order_id'] = $orderid;
			    $user_order_level['status'] = 3;
			    $user_order_level['level_id'] = $results['id'];
			    $user_order_level['level_type'] = 2;
			    $user_order_level['price'] = 3;
			    $user_order_level['active_time'] = date ( "Y-m-d H:i:s" );
			    M ( "Order_level" )->add ( $user_order_level );
			    
			    
				$b_info['id'] = $b_results['id'];
				$b_info['price'] = $b_results['price']+3;
				$b_info['pay_time'] = date('Y-m-d H:i:s');
				$user_id = M ( "User" )->save ( $b_info );
				$data = array();
				$data['touser'] = $b_results["uid"];
				$b_info = json_encode($b_results['wx_info']);//一级成员信息
				$data['msgtype'] = 'text';
				$data['text']['content'] = '【'.$info['nickname'].'】在'.date('Y-m-d H:i:s').'加入您的队伍，已经成为您的二级员工，从此开始为您赚米，您获得的【￥3】积分，详情点击个人中心，我的主页查看。';
				$weObj->sendCustomMessage($data);

			}
		}
		if($userinfo['l_c']){
			$where["id"] = $userinfo['l_c'];
			$c_results =  M ( "User" )->where($where)->find ();
			
			if(strlen($c_results['uid'])>10 && $c_results['dummy'] == 0)
			{
			    $user_order_level = array();
			    $user_order_level['order_id'] = $orderid;
			    $user_order_level['status'] = 3;
			    $user_order_level['level_id'] = $results['id'];
			    $user_order_level['level_type'] = 1;
			    $user_order_level['price'] = 2;
			    $user_order_level['active_time'] = date ( "Y-m-d H:i:s" );
			    M ( "Order_level" )->add ( $user_order_level );
			    
				$c_info['id'] = $c_results['id'];
				$c_info['price'] = $c_results['price']+2;
				$c_info['pay_time'] = date('Y-m-d H:i:s');
				$user_id = M ( "User" )->save ( $c_info );
				$data = array();
				$data['touser'] = $c_results["uid"];
				$c_info = json_encode($c_results['wx_info']);//一级成员信息
				$data['msgtype'] = 'text';
				$data['text']['content'] = '【'.$info['nickname'].'】在'.date('Y-m-d H:i:s').'加入您的队伍，已经成为您的三级员工，从此开始为您赚米，您获得的【￥2】积分，详情点击个人中心，我的主页查看。';
				$weObj->sendCustomMessage($data);

			}
		}
		
		$this->success("推送积分成功");
	}
	/**
	 * //上传方法
	 */
	public function upexcel(){
		
	    header("Content-Type:text/html;charset=utf-8");

	    $info = $this->upload();
	    
	    $info = $info[0];
	    
	    var_dump($info);
	    
	    die();
	    
	    $filename = $info['savepath'].$info['savename'];
	    
	    $exts = $info['extension'];
	    	    
	    if(!$info) {// 上传错误提示错误信息
	    
	        $this->error($upload->getError());
	    
	    }else{// 上传成功
	    
	        $this->goods_import($filename, $exts);
	    
	        $this->success ( "上传成功" );
	    
	    }
	}
	
	/**
	 * @param filename 上传的文件
	 * $param exts 文件类型
	 */
	
	public function goods_import($filename, $exts='xls')
	
	{
	
	    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
	
	    require_once APP_PATH.'PHPExcel/PHPExcel.php';
	
	    require_once APP_PATH.'PHPExcel/PHPExcel/Reader/Excel5.php';     // 用于其他低版本xls
	
	    require_once APP_PATH.'PHPExcel/PHPExcel/Reader/Excel2007.php'; // 用于 excel-2007 格式
	
	    //创建PHPExcel对象，注意，不能少了\
	
	    $PHPExcel=new PHPExcel();
	
	    //如果excel文件后缀名为.xls，导入这个类
	
	    if($exts == 'xls'){
	
	        $PHPReader=new PHPExcel_Reader_Excel5();
	
	    }elseif($exts == 'xlsx'){
	
	        $PHPReader=new PHPExcel_Reader_Excel2007();
	
	    }else{
	        exit('错误的格式');
	    }
	
	    //载入文件
	
	    $PHPExcel=$PHPReader->load($filename);
	
	    //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
	
	    $currentSheet=$PHPExcel->getSheet(0);
	
	    //获取总列数
	
	    $allColumn=$currentSheet->getHighestColumn();
	
	    //获取总行数
	
	    $allRow=$currentSheet->getHighestRow();
	
	    //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
	
	    for($currentRow=2;$currentRow<=$allRow;$currentRow++){
	
	        //从哪列开始，A表示第一列

	        for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
	
	            //数据坐标
	
	            $address=$currentColumn.$currentRow;
	
	            //读取到的数据，保存到数组$arr中
	
	            $data[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
	
	        }
	
		}	
	
	
	    foreach($data as $info)
	    {
	        
	        $nickname      = $info['A'];
	        $country       = trim($info['C']);
	        $province      = trim($info['D']);
	        $city          = trim($info['E']);
	        $headimgurl    = trim($info['F']);
	        $sex_text = trim($info['B']);
	        switch ($sex_text){
	            case "男":
	                $sex = 1;
	                break;
	            case "女":
	                $sex = 2;
	                break;
	            case "保密":
	                $sex = 0;
	                break;
	            default:    
	                $sex = 0;
	                break;
	        }
	        
	        if(!empty($nickname) && !empty($headimgurl))
	        {
	            $data['nickname'] = $nickname;
	            $data['sex'] = $sex;
	            $data['country'] = $country;
	            $data['province'] = $province;
	            $data['city'] = $city;
	            $data['headimgurl'] = $headimgurl;
	            $result = M( "dummy_user" )->add($data);
	
	        }
	
	    }
	
	}
	
	
	
}