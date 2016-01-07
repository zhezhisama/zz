<?php

class TxAction extends PublicAction {

	function _initialize() {

		parent::_initialize ();

	}

	public function index() {

		import ( 'ORG.Util.Page' );

		$m = D ( "Tx_record" );

		

		$count = $m->count (); // 查询满足要求的总记录数

		$Page = new Page ( $count, 10 ); // 实例化分页类 传入总记录数和每页显示的记录数

		$Page -> setConfig('header', '条记录');

        $Page -> setConfig('theme', '<li><a>%totalRow% %header%</a></li> <li>%upPage%</li> <li>%downPage%</li> <li>%first%</li>  <li>%prePage%</li>  <li>%linkPage%</li>  <li>%nextPage%</li> <li>%end%</li> ');//(对thinkphp自带分页的格式进行自定义)

		$show = $Page->show (); // 分页显示输出

		

		$result = $m->limit ( $Page->firstRow . ',' . $Page->listRows )->order("id desc")->relation(true)->select ();

		$this->assign ( "result", $result );

		$this->assign ( "page", $show ); // 赋值分页输出

		$this->display ();

	}

	public function del(){

		$result = R ( "Api/Api/deltx", array (

				$_GET ['id'],

		) );

		$this->success ( "操作成功" );

	}

	public function payComplete(){

		
	if(file_exists('./Public/Conf/tixianinfo.php'))

		{

			require './Public/Conf/tixianinfo.php';

			$hbconfig = json_decode($tixianinfo,true); 

		}else{
			exit('请设置微信红包参数');
		}

			$id = $_GET ["id"];
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
		   $txinfo['useropenid']=$usersresult['uid'];
		   $txinfo=array_merge($txinfo,$hbconfig);
		   import ( 'RedCash', APP_PATH . 'Common/hongbao', '.class.php' );
			$cash = new RedCash($txinfo);
	    	$msg = $cash->sendRedCash();
	    	$strMsg = '';
	    	if ($msg['err_code'] == 'SUCCESS') {
	    		$strMsg = '红包发送成功！';
	    		$result = R ( "Api/Api/txpayComplete", array ($_GET ['id'],	) );
	    		$rmsg = true;
			}else{
	    		$strMsg = '红包发送失败！'.$msg['err_code_des'];
	    		$rmsg = false;
			}
		//return $rmsg;
		$this->success ($strMsg);

	}

}