<?php

class WeixinAction extends PublicAction {

	function _initialize() {

		parent::_initialize ();

	}

	public function index() {

		$config = M ( "Wxconfig" )->find ();

		$this->assign ( "config", $config );

		$this->assign ( "url", 'http://' . $_SERVER ['HTTP_HOST'] . U('Admin/Wechat/index') );

		

		$menu = M ( "Wxmenu" )->select ();

		$this->assign ( "menu", $menu );

		

		$message = M ( "Wxmessage" )->select ();

		$this->assign ( "message", $message );

		$this->display ();

	}

	public function setconfig() {
		/*print_r($_POST);
		exit;*/
		$result = M ( "Wxconfig" )->where ( array (

				"id" => "1" 

		) )->save ( $_POST );

		$this->success ( "配置成功!" );

	}

	public function addmenu() {

		if ($_POST ["menu_id"]) {

			$_POST ["status"] = '1';

			$result = M ( "Wxmenu" )->save ( $_POST );

			if ($result) {

				$this->success ( "修改分类单成功!" );

			}

		} else {

			$_POST ["status"] = '1';

			unset ( $_POST ["menu_id"] );

			$result = M ( "Wxmenu" )->add ( $_POST );

			if ($result) {

				$this->success ( "添加分类成功!" );

			}

		}

	}

	public function delmenu() {

		$id = $_GET ["id"];

		$result = M ( "Wxmenu" )->where ( array (

				"menu_id" => $id 

		) )->delete ();

		if ($result) {

			$this->success ( "删除菜单成功!" );

		}

	}

	public function addmessage() {

		$data = $_POST;

		if ($_FILES ['picurl'] ['name'] !== '') {

			$img = $this->upload ();

			$picurl = $img [0] [savename];

			$data ["picurl"] = $picurl;

		}

		

		if ($_POST ["message_id"] == 0) {

			unset ( $data ["message_id"] );

			$result = M ( "Wxmessage" )->add ( $data );

		} else {

			$data ["id"] = $data ["message_id"];

			unset ( $data ["message_id"] );

			$result = M ( "Wxmessage" )->save ( $data );

		}

		

		if ($result) {

			$this->success ( "操作成功!" );

		}

	}

	public function delmessage(){

		$result = M("Wxmessage")->where(array("id"=>$_GET["id"]))->delete();

		if ($result) {

			$this->success("删除成功!");

		}

	}
	
	public function qunmessage(){
	
	    $result = M("Wxmessage")->where(array("id"=>$_GET["id"]))->find();
	    
	    $users = M("User")->field('uid')->where(array('dummy'=>0))->select();
	    
	   
	    if($result['type'] == 1){ //此为发送文本消息
	        if(!empty($result['picurl'])){ //发送图片消息
	         
	           exit;
	            
	        }
	    }else{
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
	        $newsArr[0] = array(
	             
	            'title' => $result["title"],
	             
	            'description' => $result["description"],
	             
	            'picurl' => 'http://' . $this->_server ( 'HTTP_HOST' ) . __ROOT__. '/Public/Uploads/'.$result["picurl"],
	             
	            'url' => $result["url"]
	             
	        );
	        foreach($users as $val ){
	            $data = array();
	            $data['touser'] = $val["uid"];
	            $data['msgtype'] = 'news';
	            $data['news']['articles']= $newsArr;
	            $weObj->sendCustomMessage($data);
	        }
	    }

	    if ($result) {
	
	        $this->success("推送成功");
	
	    }
	
	}

}

