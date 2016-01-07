<?php
// 后台用户模块
class MemberAction extends CommonAction {

    /* *
    * 配置文件
    * 版本：3.3
    * 日期：2012-07-19
    */
    
    public function alipay_config($type_id = '') {

       //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//        $typeid=I('WIDtype');
//        if(!isset($typeid)){
//            $this->error('操作错误，如需帮助请联系管理员。');
//        }
        $map['id']=array('eq',$type_id);
        $map['status']=array('eq',1);

        $paytypelist=D('Paytype')->where($map)->find();

        if(empty($paytypelist)){
            $this->error('本站暂未开启在线支付功能，如需帮助请联系管理员。');
        }
       $alipay_config['pay_account']		= $paytypelist['pay_account'];
       
        //合作身份者id，以2088开头的16位纯数字
       $alipay_config['partner']		= $paytypelist['pay_parter'];

       //安全检验码，以数字和字母组成的32位字符
       $alipay_config['key']			= $paytypelist['pay_key'];

       $alipay_config['payid']		    = $paytypelist['id'];
       $alipay_config['payname']		= $paytypelist['payname'];
       
   
       //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


       //签名方式 不需修改
       $alipay_config['sign_type']    = strtoupper('MD5');

       //字符编码格式 目前支持 gbk 或 utf-8
       $alipay_config['input_charset']= strtolower('utf-8');

       //ca证书路径地址，用于curl中ssl校验
       //请保证cacert.pem文件在当前文件夹目录中
       $alipay_config['cacert']    = getcwd().'\\Data\\cacert.pem';

       //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
       $alipay_config['transport']    = 'http';
       
       return $alipay_config;
    }
    
    /* *
    * 功能：标准双接口接入页
    * 版本：3.3
    * 修改日期：2012-07-23
    */
    public function alipayapi(){
        $this->checkUser();
        //验证码
        if(isset($_POST['verify'])){
           if($_SESSION['verify'] != md5($_POST['verify'])) {
                $this->error('验证码错误！');
            } 
        }
        $typeid = $_POST['WIDtype'];
        if(!isset($typeid)){
            $this->error('请选择支付方式');
        }
       
        $alipay_config=$this->alipay_config($typeid);
        
        if($typeid == 1){//阿里支付
            /**************************请求参数**************************/
            //支付类型
            $payment_type = "1";
            //必填，不能修改
            //服务器异步通知页面路径
            //$notify_url = U('Member/notify_url','','','',true);
            $notify_url = 'http://'.$_SERVER['HTTP_HOST'].__APP__.'/Member/notify_url';//U('Member/notify_url','','','',true);
            //需http://格式的完整路径，不能加?id=123这类自定义参数
            
            //页面跳转同步通知页面路径
            $return_url = 'http://'.$_SERVER['HTTP_HOST'].__APP__.'/Member/return_url';//U('Member/return_url','','','',true);
            //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
            
            //卖家支付宝帐户
            //$seller_email = $_POST['WIDseller_email'];
            $seller_email =$alipay_config['pay_account'];
            //必填
            
            //商户订单号
            //$out_trade_no = $_POST['WIDout_trade_no'];
            $out_trade_no =create_sn(); //date('YmdHis').rand(0,9999);
            //商户网站订单系统中唯一订单号，必填
            
            //订单名称
            //        $subject = $_POST['WIDsubject'];
            $subject="在线充值";
            //必填
            
            //付款金额
            $price = $_POST['WIDprice'];
            if(!preg_match('/^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$/i',$price)) {
                $this->error( '充值金额必须为整数或小数(保留两位小数)');
            }
            //必填
            
            //商品数量
            $quantity = "1";
            //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
            //物流费用
            $logistics_fee = "0.00";
            //必填，即运费
            //物流类型
            $logistics_type = "EXPRESS";
            //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
            //物流支付方式
            $logistics_payment = "SELLER_PAY";
            //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
            //订单描述
            $body = $_POST['WIDbody'];
            //商品展示地址
            $show_url = $_POST['WIDshow_url'];
            //需以http://开头的完整路径，如：http://www.xxx.com/myorder.html
            
            //收货人姓名
            $receive_name = $_POST['WIDreceive_name'];
            //如：张三
            
            //收货人地址
            $receive_address = $_POST['WIDreceive_address'];
            //如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
            
            //收货人邮编
            $receive_zip = $_POST['WIDreceive_zip'];
            //如：123456
            
            //收货人电话号码
            $receive_phone = $_POST['WIDreceive_phone'];
            //如：0571-88158090
            
            //收货人手机号码
            $receive_mobile = $_POST['WIDreceive_mobile'];
            //如：13312341234
            
            
            /************************************************************/
            
            //构造要请求的参数数组，无需改动
            $parameter = array(
                "service" => "trade_create_by_buyer",
                "partner" => trim($alipay_config['partner']),
                "payment_type"	=> $payment_type,
                "notify_url"	=> $notify_url,
                "return_url"	=> $return_url,
                "seller_email"	=> $seller_email,
                "out_trade_no"	=> $out_trade_no,
                "subject"	=> $subject,
                "price"	=> $price,
                "quantity"	=> $quantity,
                "logistics_fee"	=> $logistics_fee,
                "logistics_type"	=> $logistics_type,
                "logistics_payment"	=> $logistics_payment,
                "body"	=> $body,
                "show_url"	=> $show_url,
                "receive_name"	=> $receive_name,
                "receive_address"	=> $receive_address,
                "receive_zip"	=> $receive_zip,
                "receive_phone"	=> $receive_phone,
                "receive_mobile"	=> $receive_mobile,
                "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
            );
            
            //建立请求
            $alipaySubmit = new AlipaySubmit($alipay_config);
            $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认并支付");
            //        echo $html_text;
            
            $model=M('Payment');
            $data['memberid']=session('id');
            $data['membername']=session('account');
            $data['payno']=$out_trade_no;
            $data['businesstype']=$subject;
            $data['paytypeid']=$alipay_config['payid'];
            $data['paytypename']=$alipay_config['payname'];
            $data['paymoney']=$price;
            $data['type']=1;//1是金钱，2是积分
            $data['payip']=get_client_ip();
            $data['paybody']= $_POST['WIDbody'];
            $data['status']=3;
            $data['create_time']=time();
            if(false===$model->add($data)){
                $this->error('操作失败');
            }
            $this->assign('paymoney',$price);
            $this->assign('paytypename',$alipay_config['payname']);
            $this->assign('html_text',$html_text);
            
        }elseif($typeid == 2){ //微信
            $price = $_POST['WIDprice'];
            
            import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );
            
            $options = array (
                //'token' => $alipay_config["token"], // 填写你设定的key
                //'encodingaeskey' => $alipay_config["encodingaeskey"], // 填写加密用的EncodingAESKey
                'appid' => 'wx0d502aa5ef3e60b0', //$alipay_config["pay_account"], // 填写高级调用功能的app id
                'appsecret' => '08844e4bafaf557818c520598dc7a029', // 填写高级调用功能的密钥$alipay_config["appsecret"]
                'partnerid' => '1280963601', // 财付通商户身份标识$alipay_config["partner"]
                'partnerkey' =>  'tianshiweishang8tianshimama8zhou',// 财付通商户权限密钥Key$alipay_config["key"]
                'paysignkey' => '08844e4bafaf557818c520598dc7a029' // 商户签名密钥Key
            );            
            $weObj = new Wechat ( $options );
            
            $appid = $options['appid'];
            $mch_id = $options['partnerid'];
            $out_trade_no = create_sn();
            $body = '积分充值';
            $total_fee = $price*100;
            $notify_url = 'http://' . $_SERVER ['SERVER_NAME'].U('Member/wxNotify_url',array('out_trade_no'=>$out_trade_no));
            $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
            $nonce_str = $weObj->generateNonceStr();
            
            $pay_xml = $weObj->createNativePackageXml($appid,$mch_id,$nonce_str,$body,$out_trade_no,$total_fee,$notify_url,$spbill_create_ip);
            
            $pay_xml =  $weObj->get_pay_id($pay_xml);
            if($pay_xml['err_code']=="ORDERPAID")
            {
                $this->error('商品已支付');
                eixt();
            }
            
            $model=M('Payment');
            $data['memberid']=session('id');
            $data['membername']=session('account');
            $data['payno']=$out_trade_no;
            $data['businesstype']=$subject;
            $data['paytypeid']=$alipay_config['payid'];
            $data['paytypename']=$alipay_config['payname'];
            $data['paymoney']= $price;
            $data['type']=1;//1是金钱，2是积分
            $data['payip']=get_client_ip();
            $data['paybody']=$_POST['WIDbody'];
            $data['status']=3;
            $data['create_time']=time();
            $pay_id = $model->add($data);
            if(false === $pay_id){
                $this->error('操作失败');
            }
            $this->assign('pay_id',$pay_id);
            $this->assign('paymoney',$price);
            $this->assign('paytypename',$alipay_config['payname']);
            $this->assign('code_url',$pay_xml['code_url']);
            
        }
        
        $this->seo('支付确认', '', '', 'pay');
        C('TOKEN_ON',false);//关闭表单令牌
        $this->display();
 
    }
    
    Public function wxNotify_url(){
        import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );
        
        $options = array (
            //'token' => $alipay_config["token"], // 填写你设定的key
            //'encodingaeskey' => $alipay_config["encodingaeskey"], // 填写加密用的EncodingAESKey
            'appid' => 'wx0d502aa5ef3e60b0', //$alipay_config["pay_account"], // 填写高级调用功能的app id
            'appsecret' => '08844e4bafaf557818c520598dc7a029', // 填写高级调用功能的密钥$alipay_config["appsecret"]
            'partnerid' => '1280963601', // 财付通商户身份标识$alipay_config["partner"]
            'partnerkey' =>  'tianshiweishang8tianshimama8zhou',// 财付通商户权限密钥Key$alipay_config["key"]
            'paysignkey' => '08844e4bafaf557818c520598dc7a029' // 商户签名密钥Key
        );
        $weObj = new Wechat ( $options );
        //$verify_result = $weObj->checkOrderSignature();
        
    
            //获取微信的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
        $out_trade_no = $_GET['out_trade_no'];
            
            //该判断表示买家已经确认收货，这笔交易完成
             
            $model=M('Payment');
            $map['payno']=array('eq',$out_trade_no);
            $paydata=$model->where($map)->find();
            if($paydata['status']!=1&&$paydata['status']!=0){
                $total_fee = $paydata['paymoney'];
                $model->where($map)->setField('status', 1);
                $member=M('Member');
                $mapmember['id']=array('eq',$paydata['memberid']);
                $mapmember['account']=array('eq',$paydata['membername']);
                $member->where($mapmember)->setInc('amount',$total_fee);
            }
    
        $this->success('支付成功',U('Member/index'));
       
        
    }
    
    /* *
    * 功能：支付宝服务器异步通知页面
    * 版本：3.3
    * 日期：2012-07-23
    */
    public function notify_url() {
       
//        $this->checkUser();
        $alipay_config=$this->alipay_config();
        
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        if($verify_result) {//验证成功

                //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

                //商户订单号
                $out_trade_no = $_POST['out_trade_no'];

                //支付宝交易号
                $trade_no = $_POST['trade_no'];

                //交易状态
                $trade_status = $_POST['trade_status'];
                
                //交易金额
                $total_fee = $_POST['total_fee'];

                if($trade_status == 'WAIT_BUYER_PAY') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
                
                $model=M('Payment');
                $map['payno']=array('eq',$out_trade_no);
                $paydata=$model->where($map)->find();
                if($paydata['status']!=2&&$paydata['status']!=1&&$paydata['status']!=0){
                    $model->where($map)->setField('status', 2);
                }
                   
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
//                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录1");
            }else if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
                
                $model=M('Payment');
                $map['payno']=array('eq',$out_trade_no);
                $paydata=$model->where($map)->find();
                if($paydata['status']!=4&&$paydata['status']!=1&&$paydata['status']!=0){
                    $model->where($map)->setField('status', 4);
                }
                    
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
//                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录2");
            }else if($trade_status == 'WAIT_BUYER_CONFIRM_GOODS') {
                //该判断表示卖家已经发了货，但买家还没有做确认收货的操作
                        
                $model=M('Payment');
                $map['payno']=array('eq',$out_trade_no);
                $paydata=$model->where($map)->find();
                if($paydata['status']!=4&&$paydata['status']!=1&&$paydata['status']!=0){
                    $model->where($map)->setField('status', 4);
                }
                    
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
//                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录3");
            }else if($trade_status == 'TRADE_FINISHED') {
                //该判断表示买家已经确认收货，这笔交易完成
                       
                    $model=M('Payment');
                    $map['payno']=array('eq',$out_trade_no);
                    $paydata=$model->where($map)->find();
                    if($paydata['status']!=1&&$paydata['status']!=0){
                        $model->where($map)->setField('status', 1);
                        $member=M('Member');
                        $mapmember['id']=array('eq',$paydata['memberid']);
                        $mapmember['account']=array('eq',$paydata['membername']);
                        $member->where($mapmember)->setInc('amount',$total_fee); 
                    }
                
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
//                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录4");
            }
            else {
                
                //其他状态判断
                echo "success";

                //调试用，写文本函数记录程序运行情况是否正常
//                logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录5");
            }

                
        }
        else {
            
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
//            logResult("这里写入想要调试的代码变量值，或其他运行的结果记录6");
        }
        
    }
    /* * 
    * 功能：支付宝页面跳转同步通知页面
    * 版本：3.3
    * 日期：2012-07-23
    */
    public function return_url() {
//        $this->checkUser();
        $alipay_config=$this->alipay_config();
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {//验证成功
               
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];

            //支付宝交易号
            $trade_no = $_GET['trade_no'];

            //交易状态
            $trade_status = $_GET['trade_status'];


            if($trade_status == 'WAIT_SELLER_SEND_GOODS') {
                        //判断该笔订单是否在商户网站中已经做过处理
                                
            }
            else if($trade_status == 'TRADE_FINISHED') {
                        //判断该笔订单是否在商户网站中已经做过处理
                     $this->success('交易成功，订单号：'.$out_trade_no,U('Member/paylist'));          
            }
            else {
                echo "trade_status=".$_GET['trade_status'];
            } 

               
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }
        
        
    }
    
    /* *
     * 微信扫码
     */
    public function wxpayapi(){
        $this->checkUser();
        
        if(!$_GET['payid']){
            $this->error("没有找到相关支付订单");
        }
        $payment_info =  M('Payment')->where(array('id'=>$_GET['payid']))->find();
        
        $alipay_config=$this->alipay_config(2);

        $price = $payment_info['discount']+$payment_info['paymoney'];

        import ( 'Wechat', APP_PATH . 'Common/Wechat', '.class.php' );

        $options = array (
            //'token' => $alipay_config["token"], // 填写你设定的key
            //'encodingaeskey' => $alipay_config["encodingaeskey"], // 填写加密用的EncodingAESKey
            'appid' => 'wx0d502aa5ef3e60b0', //$alipay_config["pay_account"], // 填写高级调用功能的app id
            'appsecret' => '08844e4bafaf557818c520598dc7a029', // 填写高级调用功能的密钥$alipay_config["appsecret"]
            'partnerid' => '1280963601', // 财付通商户身份标识$alipay_config["partner"]
            'partnerkey' =>  'tianshiweishang8tianshimama8zhou',// 财付通商户权限密钥Key$alipay_config["key"]
            'paysignkey' => '08844e4bafaf557818c520598dc7a029' // 商户签名密钥Key
        );
        $weObj = new Wechat ( $options );

        $appid = $options['appid'];
        $mch_id = $options['partnerid'];
        $out_trade_no = $payment_info['payno'];
        $body = '积分充值';
        $total_fee = $price*100;
        $notify_url = 'http://' . $_SERVER ['SERVER_NAME'].U('Member/wxNotify_url',array('out_trade_no'=>$out_trade_no));
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        $nonce_str = $weObj->generateNonceStr();

        $pay_xml = $weObj->createNativePackageXml($appid,$mch_id,$nonce_str,$body,$out_trade_no,$total_fee,$notify_url,$spbill_create_ip);

        $pay_xml =  $weObj->get_pay_id($pay_xml);
        if($pay_xml['err_code']=="ORDERPAID")
        {
            $this->error('商品已支付');
            eixt();
        }

        $this->assign('pay_id',$_GET['payid']);
        $this->assign('paymoney',$price);
        $this->assign('paytypename',$alipay_config['payname']);
        $this->assign('code_url',$pay_xml['code_url']);
    
 
        $this->seo('支付确认', '', '', 'pay');
        C('TOKEN_ON',false);//关闭表单令牌
        $this->display('alipayapi');
    
    }
    //充值
    public function pay() {
		
        $this->checkUser();
        $map['status']=array('eq',1);
		$map['payname']=array('eq','微信'); //支付宝隐藏 只要微信 php@zz
        $paytypelist=D('Paytype')->where($map)->select();
        $this->assign(paytypelist,$paytypelist);
        $this->seo('在线充值', '', '', 'pay');
        $this->display();
    }
    //充值记录
    public function paylist() {
		
    	$mem_info['nickname'] = $_SESSION['YFIndex_']['nickname'];
    	$mem_info['thumb'] = $_SESSION['YFIndex_']['avatar'];
    	 
    	$this->assign("mem_info", $mem_info);
    	
        $this->checkUser();
        if(isset($_POST['name'])){
            $map['payno'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Payment');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('create_time desc')->select();
    
        foreach ($list as $key => $value) {
            $alipay_config=$this->alipay_config($value['paytypeid']);
            if($value['status'] != 1 ){
                if($value['paytypeid'] == 1){//支付宝支付
                    //支付类型
                    $payment_type = "1";
                    //必填，不能修改
                    //服务器异步通知页面路径
                    //$notify_url = U('Member/notify_url','','','',true);
                    $notify_url = 'http://'.$_SERVER['HTTP_HOST'].__APP__.'/Member/notify_url';//U('Member/notify_url','','','',true);
                    //需http://格式的完整路径，不能加?id=123这类自定义参数
                    
                    //页面跳转同步通知页面路径
                    $return_url = 'http://'.$_SERVER['HTTP_HOST'].__APP__.'/Member/return_url';//U('Member/return_url','','','',true);
                    //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
                    
                    //卖家支付宝帐户
                    //$seller_email = $_POST['WIDseller_email'];
                    $seller_email =$alipay_config['pay_account'];
                    //必填
                    
                    //商户订单号
                    //$out_trade_no = $_POST['WIDout_trade_no'];
                    $out_trade_no =create_sn(); //date('YmdHis').rand(0,9999);
                    //商户网站订单系统中唯一订单号，必填
                    //订单名称
                    $subject=$value['businesstype'];
                    
                    //付款金额
                    $price = $value['paymoney']+$value['discount'];
                    
                    //商品数量
                    $quantity = "1";
                    //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
                    //物流费用
                    $logistics_fee = "0.00";
                    //必填，即运费
                    //物流类型
                    $logistics_type = "EXPRESS";
                    //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
                    //物流支付方式
                    $logistics_payment = "SELLER_PAY";
                    //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
                    //订单描述
                    $body = $value['paybody'];
                    
                    
                    //构造要请求的参数数组，无需改动
                    $parameter = array(
                        "service" => "trade_create_by_buyer",
                        "partner" => trim($alipay_config['partner']),
                        "payment_type"	=> $payment_type,
                        "notify_url"	=> $notify_url,
                        "return_url"	=> $return_url,
                        "seller_email"	=> $seller_email,
                        "out_trade_no"	=> $out_trade_no,
                        "subject"	=> $subject,
                        "price"	=> $price,
                        "quantity"	=> $quantity,
                        "logistics_fee"	=> $logistics_fee,
                        "logistics_type"	=> $logistics_type,
                        "logistics_payment"	=> $logistics_payment,
                        "body"	=> $body,
                        "show_url"	=> $show_url,
                        "receive_name"	=> $receive_name,
                        "receive_address"	=> $receive_address,
                        "receive_zip"	=> $receive_zip,
                        "receive_phone"	=> $receive_phone,
                        "receive_mobile"	=> $receive_mobile,
                        "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
                    );
                    
                    //建立请求
                    $alipaySubmit = new AlipaySubmit($alipay_config);
                    $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "付款");
                    $list[$key]['pay_btn']=$html_text;
                    
                    
                }else{//微信支付
                    $url = U("Member/wxpayapi", array('payid'=> $value['id']));
                    
                    $html_text = "<a href='".$url."' >去支付</a>";
                 
                    $list[$key]['pay_btn'] = $html_text;
                }
            }
        
        }
        
        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        $this->seo('充值记录', '', '', 'paylist');
        C('TOKEN_ON',false);//关闭表单令牌
        $this->display(); 
        
    }
    
    //@pq 查询微信支付状态
    public function checkStatus(){
        $payid = I("post.payid");
        $data = array('error'=> 0 , 'message'=> '');
        if(!payid){
            $data['error'] = 1;
            $data['message'] = '没有找到支付信息';
            $this->ajaxReturn($data);
        }
        
        $payment = M('Payment')->where(array('id'=>$payid))->find();
        
        if($payment['status'] == 1){
            $data['error'] = 0;
            $data['message'] = '支付成功';
        }else{
            $data['error'] = 1;
            $data['message'] = '继续查询';
        }
        $this->ajaxReturn($data);
    }
     //积分兑换
    public function change() {
        
        $this->checkUser();
        $rmb_change_intergral=C('RMB_CHANGE_INTERGRAL');
        $map['id']=array('eq',session('id'));
        $map['account']=array('eq',session('account'));
        $memberdata=D('member')->where($map)->find();
        
        $this->assign(rmb_change_intergral,$rmb_change_intergral);
        $this->assign(memberdata,$memberdata);
        $this->seo('积分兑换', '', '', 'change'); 
        $this->display();
    }
    //金钱兑换积分
    public function payspend() {
        $this->checkUser();
        $value=I('post.value');
        if(intval($value)<=0){
            $this->error('请输入兑换金额!');
        }
        //兑换金额不能大于账户余额
        $map['id']=session('id');
        $map['account']=session('account');
        $amount=D('Member')->where($map)->getField('amount');
        if(intval($value)>$amount){
            $this->error('您的账户余额不足!');
        }
        
        $model=M('Payspend');
        $data['memberid']=session('id');
        $data['membername']=session('account');
        $data['type']=1;
        $data['value']=$value;
        $data['msg']="购买积分";
        $data['create_time']=time();
        if(false!==$model->add($data)){
            $rmb_change_intergral=C('RMB_CHANGE_INTERGRAL');
            $member=M('Member')->where($map)->setDec('amount', $data['value']);//帐户余额减少
            $member=M('Member')->where($map)->setInc('intergral', $data['value']*$rmb_change_intergral);//积分点数增加
            $this->success('操作成功');
        }else{
             $this->error('操作失败');
        }
    }
    
    //消费记录
    public function spendlist() {

        $this->checkUser();
        
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Payspend');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('create_time desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        $this->seo('消费记录', '', '', 'spendlist');
        
        $this->display(); 
        
    }
	
	

	
    // 会员登录
    public function login() {
        $code = $_GET['code'];
        if($code){
            
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx10a648e64fec1e88&secret=38f5d9272339117100a3824691541b69&code='.$code.'&grant_type=authorization_code';
            
        }else{
            $authurl = "https://open.weixin.qq.com/connect/qrconnect?appid=wx10a648e64fec1e88&redirect_uri=http%3a%2f%2fwww.tianshiweishang.com%2findex.php%3fm%3dMember%26a%3dlogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
            header("Location: $authurl");
        }
        //获取access_token
        $token_result = $this->Post('' , $get_token_url);
        $token_json_obj = json_decode($token_result,true);

        //获取用户信息
        $user_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token_json_obj['access_token'].'&openid='.$token_json_obj['openid'];
        $user_result = $this->Post('' , $user_url);
        $user_json_obj = json_decode($user_result,true);

        //查找用户openid 如果查找到登陆
        $map=array();
        $map['openid']= $user_json_obj['openid'];
        //$map["status"]=array('eq',1);
        
        $Member=M('Member');
        $authInfo=$Member->where($map)->find();
        //查找到用户
        if(false == $authInfo) {//注册
		
            if(!$user_json_obj['openid']){
                $this->error("错误" , U('Member/index'));
            }
            //$this->error('该用户已被冻结');
            //添加用户并且存入session
            $User=D("Member");
            $data['account']        = 'tianshi'.time();
            $data['nickname']       = $user_json_obj['nickname'];
            $data['openid']         = $user_json_obj['openid'];
            $data['thumb']          = $user_json_obj['headimgurl'];
			//新用户注册 赠送1000金币 php@zz
			$data['intergral']      = 1000;
            $data['create_time']    = time();
            $data['updata_time']    = time();
            $data['status']         = 1;
			$data['password']       = "";
            //写入帐号数据
			
			$username = $data['nickname'];
			$email = $data['create_time']."@qq.com";
			$password = "";
			
			import("@.ORG.UcService");//导入UcService.class.php类
			$ucService = new UcService;//实例化UcService类
			$uid = $ucService->register($username, $password, $email);//注册到UCenter
			

		//		if($vo = $User->create()){
		//			if($User->add()){
		//				
		//			}else{
		//				$this->error('注册失败!');
		//			}
		//		}else{
		//			$this->error();
		//		}



						
			
			
            if($result =$User->add($data)) {
                session('id', $result);
                session('account', $data['account']);
                session('nickname', $data['nickname']);
                session('avatar', $data['thumb']);
				
				
				$this->success('登陆成功！',U('Member/information'));
            }else{
                $this->error('微信登陆失败！');
            }
    
        }else {
            
            //登陆更新状态
            session('id', $authInfo['id']);
            session('account', $authInfo['account']);
            session('nickname', $authInfo['nickname']);
            session('avatar', $authInfo['thumb']);
            session('lastLoginTime', $authInfo['last_login_time']);
            session('login_count', $authInfo['login_count']);
            
            //保存登录信息
            $Member=M('Member');
            $ip=get_client_ip();
            $time=time();
            $data = array();
            $data['id']=$authInfo['id'];
            $data['last_login_time']=$time;
            $data['login_count']=array('exp','login_count+1');
            $data['last_login_ip']=$ip;
			
			$username = $authInfo['nickname'];
			$email = $data['last_login_time']."@qq.com";
			$password = "";
			
			
			import("@.ORG.UcService");//导入UcService.class.php类
			$ucService = new UcService;//实例化UcService类
			$uid = $ucService->register($username, $password, $email);//注册到UCenter
			
			$M = D('Member');
			$M->create();
			$M->add();
			
			
			
            $Member->save($data);
            
            if(session('?_curUrl_')){
                $this->success('登录成功！', U('Member/information'));
            }else{
                $this->success('登录成功！', Cookie::get('_curUrl_'));
            }
        
        }
		/*if(session('?account')) {
			  session(null); 
            $this->redirect('Member/index');
			
        }else{

            $this->seo('会员登录', '', '', 'login');
            $this->display();
        }*/
    }
    public function Post($curlPost,$url){
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
     //登录验证
    public function checkLogin() {
        
        $account=I('post.account');
        $password=I('post.password');
        if(empty($account)) $this->error('帐号错误!');
        if(empty($password)) $this->error('密码错误!');
        
        //验证码
        if(isset($_POST['verify'])){
           if($_SESSION['verify'] != md5($_POST['verify'])) {
                $this->error('验证码错误！');
            }
        }else{
            //印象码
            $YinXiangMa_response=  $this->YinXiangMa_ValidResult(@$_POST['YinXiangMa_challenge'],@$_POST['YXM_level'][0],@$_POST['YXM_input_result']);
            if($YinXiangMa_response !== "true") {
                $this->ajaxReturn('验证码错误','验证码错误',0);
            }
        }
        
        //生成认证条件
        $map=array();
        // 支持使用绑定帐号登录
        $map['account']= $account;
        $map["status"]=array('eq',1);
        
        
        $Member=M('Member');
        $authInfo=$Member->where($map)->find();
        
        //使用用户名、密码和状态的方式进行认证
        if(false == $authInfo) {
            $this->error('用户名或密码错误！');
        }else {
            if($authInfo['password'] != md5($password)) {
                $this->error('用户名或密码错误！');
            }

            session('id', $authInfo['id']);
            session('account', $authInfo['account']);
            session('nickname', $authInfo['nickname']);
            session('email', $authInfo['email']);
            session('avatar', $authInfo['thumb']);
            session('lastLoginTime', $authInfo['last_login_time']);
            session('login_count', $authInfo['login_count']);

            //保存登录信息
           
            $ip=get_client_ip();
            $time=time();
            $data = array();
            $data['id']=$authInfo['id'];
            $data['last_login_time']=$time;
            $data['login_count']=array('exp','login_count+1');
            $data['last_login_ip']=$ip;
            $Member->save($data);
            
            if(session('?_curUrl_')){
                $this->success('登录成功！', U('Member/add'));
            }else{
                $this->success('登录成功！', Cookie::get('_curUrl_'));
            }

        }
    }
    //会员退出
    public function logout() {
        if(session('?account')) {
            session(null); 
            $this->success('登出成功！',U('Member/login'));
        }else {
            $this->error('已经登出！',U('Member/login'));
        }
    }
    //会员注册
    public function register() {
        if(session('?account')) {
            $this->redirect('Member/index');
        }else{
            $this->seo('会员注册', '', '', 'register');
            $this->display();
        }
    }
    //注册验证
    public function checkRegister() {

        //验证码
        if(isset($_POST['verify'])){
           if($_SESSION['verify'] != md5($_POST['verify'])) {
                $this->error('验证码错误！');
            }
        }else{
            //印象码
            $YinXiangMa_response=  $this->YinXiangMa_ValidResult(@$_POST['YinXiangMa_challenge'],@$_POST['YXM_level'][0],@$_POST['YXM_input_result']);
            if($YinXiangMa_response !== "true") {
                $this->ajaxReturn('验证码错误','验证码错误',0);
            }
        }
        $_POST['account']=I('post.account');
        $_POST['password']=I('post.password');
        $_POST['email']=I('post.email');
        
        if(!preg_match('/^[a-zA-Z0-9_]{3,30}$/i',$_POST['account'])) {
            $this->error( '用户名必须是字母、下划线、数字组成，且3位以上！');
        }
        $_POST['password'] = md5(trim($_POST['password']));
        
        // 创建数据对象
        $User=D("Member");
        
        if(!$User->create()) {
            $this->error($User->getError());
        }else{
            //写入帐号数据
            if($result =$User->add()) {
                $this->success('注册成功！');
            }else{
                $this->error('注册失败！');
            }
        }

    }
    //帐号验证
    public function checkAccount() {

        $account=I('post.account');
        
        //检测用户名是否冲突
        
        if(!preg_match('/^[a-zA-Z0-9_]{3,30}$/i',$account)) {
            $this->error( '用户名必须是字母、下划线、数字组成，且3位以上！');
        }
        
        $User = M("Member");
        $result  =  $User->getByAccount($account);
        if($result) {
            $this->error('很抱歉，用户名已经存在！');
        }else {
            $this->success('恭喜您，用户名可以使用！');
        }
    }
    //验证登录状态
    protected function checkUser() {
        if(!session('?account')){
            $this->error('没有登录',U('Member/login'));
        }
    }
     //会员中心
    public function index() {
        $this->checkUser();
        $map['id']=array('eq',session('id'));
        $Member=M('Member');
        $data=$Member->where($map)->find();
        $this->data=$data;

        $this->seo('个人中心', '', '', 'index');
        $this->display();
        
    }
    //会员资料显示
    public function information() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
		$this->assign('udata',$udata);

        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);
        		
		
		
        $this->seo('修改资料', '', '', 'information');
        $this->display();
    }
	
	 //个人中心显示 php@zz
    public function information_info() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
      
		$this->assign('udata',$udata);
		
		$start_time = strtotime(date("Y-m-d 0:0:0"));
		$end_time = strtotime(date("Y-m-d 23:59:59"));
		$id = $udata['id'];
		$select1['id'] = $id;
		$row1 = $User->where($select1)->select();
		$update_time = $row1[0]['update_time'];
		if($update_time<$start_time || $update_time>$end_time){
			$this->assign('tg',1);
		}else{
			$this->assign('tg',2);
		}
        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);
       
        $this->seo('修改资料', '', '', 'information');
		
		$search1['memberid'] = array('eq',$udata['id']);
		$search1['publish_type_id'] = array('eq',44);
		
		$res1 = M("weixin")->where($search1)->select();
		$total1 = count($res);
		
		$search2['memberid'] = array('eq',$udata['id']);
		$search2['publish_type_id'] = array('eq',48);
		
		$res2 = M("weixin")->where($search2)->select();
		$total2 = count($res2);
		
		$search3['memberid'] = array('eq',$udata['id']);
		$search3['publish_type_id'] = array('eq',47);
		
		$res3 = M("weixin")->where($search3)->select();
		$total3 = count($res3);
		
		$search4['memberid'] = array('eq',$udata['id']);
		$search4['publish_type_id'] = array('eq',1);
		
		$res4 = M("weixin")->where($search4)->select();
		$total4 = count($res4);
				
		
		$this->assign("total1",$total1);
		$this->assign("total2",$total2);
		$this->assign("total3",$total3);
		$this->assign("total4",$total4);
		
        $this->display();
    }
	
	 //余额兑换金币 php@zz
    public function information_dh() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
      
		$this->assign('udata',$udata);

        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);
       
        $this->seo('修改资料', '', '', 'information');
        $this->display();
    }
	//兑换后金币上传数据库 php@zz
    public function chkduihuan() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
		$this->assign('udata',$udata);
		$id = I('id');
		$row = $User->where("id=$id")->select();
		/*echo "<pre>";
		print_r($row);
		echo "</pre>";*/
		$total_money = $row[0]['amount'];
		$total_gold = $row[0]['intergral'];
		$use_money = I("money");
		$add_gold = I("dhmoney");		
		if($use_money<=$total_money){
			$num_money = $total_money - $use_money;
			$num_gold = $total_gold + $add_gold;
			$data['amount'] = $num_money;
			$data['intergral'] = $num_gold;
			$res = $User->where("id=$id")->save($data);
			if($res){
				$this->success('恭喜您,兑换成功');
			}else{
				$this->error('对不起,您的兑换失败,请重试');
			}
		}else{
			$this->error('对不起,您的余额不足');
		}
        //$this->seo('修改资料', '', '', 'information');
    }
	
	
	 //金币兑换余额 php@zz
    public function information_hd() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
      
		$this->assign('udata',$udata);

        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);
       
        $this->seo('修改资料', '', '', 'information');
        $this->display();
    }
	//兑换后余额上传数据库 php@zz
    public function chkduihuan_hd() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
		$this->assign('udata',$udata);
		$id = I('id');
		$row = $User->where("id=$id")->select();
		/*echo "<pre>";
		print_r($row);
		echo "</pre>";*/
		$total_money = $row[0]['amount'];
		$total_gold = $row[0]['intergral'];
		$use_money = I("money");
		$add_gold = I("dhmoney");		
		if($use_money<=$total_gold){
			$num_money = $total_gold - $use_money;
			$num_gold = $total_money + $add_gold;
			$data['amount'] = $num_gold;
			$data['intergral'] = $num_money;
			$res = $User->where("id=$id")->save($data);
			if($res){
				$this->success('恭喜您,兑换成功');
			}else{
				$this->error('对不起,您的兑换失败,请重试');
			}
		}else{
			$this->error('对不起,您的余额不足');
		}
        //$this->seo('修改资料', '', '', 'information');
    }
	
	 //余额提现 php@zz
    public function information_tx() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));
        list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
      
		$this->assign('udata',$udata);

        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);
       
        $this->seo('修改资料', '', '', 'information');
        $this->display();
    }
	
	
	
    //修改资料
    public function checkInformation() {
        $this->checkUser();
        $this->_upload();
        
        $id=I('id',0,'intval');
        $nickname=I('nickname');
        $email=I('email');
        $wxaccount=I('wxaccount');
        $gender=I('gender');
        $year=I('year') ? I('year') : '00';
        $month=I('month') ? I('month') : '00';
        $day=I('day') ? I('day') : '00';
        $birthday = $year.'-'.$month.'-'.$day;
        $province=I('province');
        $city=I('city');
		$tuijian_id=I('tuijian_id');
        
        $User=D("Member");
        $data=array(
            'nickname'=>$nickname,
            'email'=>$email,
            'wxaccount'=>$wxaccount,
            'gender'=>$gender,
            'birthday'=>$birthday,
            'province'=>$province,
            'city'=>$city,
			'tuijian_id'=>$tuijian_id
        );

        $result	=$User->where(array('id'=>$id))->save($data);
        if(false !== $result) {
            $this->success('资料修改成功！');
        }else{
            $this->error('资料修改失败!');
        }
    }
    //会员资料显示
    public function avatar() {
        $this->checkUser();
        $User=M("Member");
        $udata=$User->getById(session('id'));

        $this->assign('udata',$udata);
        $this->seo('修改头像', '', '', 'avatar');
        $this->display();
    }
    //修改资料
    public function changeAvatar() {
        $this->checkUser();
        $this->_upload();
        
        $id=I('id',0,'intval');
        $thumb=I('thumb'); 
        $User=D("Member");
        $data=array(
            'thumb'=>$thumb,
        );

        $result =$User->where(array('id'=>$id))->save($data);
        if(false !== $result) {
            session('avatar', $thumb);
            $this->success('头像修改成功！');
        }else{
            $this->error('头像修改失败!');
        }
    }
    //修改密码
    public function password() {
        $this->checkUser();
        
        $this->seo('修改密码', '', '', 'password');
        $this->display();
    }
    //更换密码
    public function checkPassword() {
	$this->checkUser();
    
        //验证码
        if(isset($_POST['verify'])){
           if($_SESSION['verify'] != md5($_POST['verify'])) {
                $this->error('验证码错误！');
            } 
        }else{
            //印象码
            $YinXiangMa_response=  $this->YinXiangMa_ValidResult(@$_POST['YinXiangMa_challenge'],@$_POST['YXM_level'][0],@$_POST['YXM_input_result']);
            if($YinXiangMa_response !== "true") {
                $this->ajaxReturn('验证码错误','验证码错误',0);
            }
        }
        $id=  session('id');
        if(empty($id)){
            $this->error('没有登录',U('Member/login'));
        }
        
        $password=I('password','');
        if(''===$password){
            $this->error('新密码不能为空！');
        }
        
        $oldpassword=I('oldpassword','');
        if(''===$oldpassword){
            $this->error('旧密码不能为空！');
        }
        
        //检查用户
        $User=M("Member");
        if(!$User->where(array('id'=>$id,'password'=>  pwdHash($oldpassword)))->find()) {
            $this->error('旧密码输入错误！');
        }else {
            $data=array(
                'password'=>  pwdHash($password),
            );
            if(false!==$User->where(array('id'=>$id))->save($data)){
                $this->success('密码修改成功！');
            }else{
                $this->success('密码修改失败！');
            }
            
         }
    }

    
    
    //公众账号管理
    public function manage() {
        $this->checkUser();

        $publish_type_id = I('publish_type_id') ? I('publish_type_id',1,int) : 1;
        $map['publish_type_id'] = array('eq',$publish_type_id);
        $this->assign("publish_type_status", $publish_type_id);

        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Weixin');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        //获取发布类别
        $publish_type = C('publish_type');
        $this->assign('publish_type',$publish_type);

        $this->seo('微信公众账号管理', '', '', 'manage');
        $this->display(); 
        
    }
    
    //显示抢位情况 - 只显示
    public function showQwInfo() {
    	 
    	$publish_type_id = I('tid');
    	$qiangwei = $this->get_occupy($publish_type_id);
    	$this->assign('publish_type_id', $publish_type_id);
    	$this->assign('qiangwei', $qiangwei);
    	$this->display();
    	 
    }
    
    //显示抢位情况 - 可抢位
    public function showQw() {
    	
    	$id = I('id');
    	$publish_type_id = I('tid');
    	$qiangwei = $this->get_occupy($publish_type_id);
    	$this->assign('id', $id);
    	$this->assign('publish_type_id', $publish_type_id);
    	$this->assign('qiangwei', $qiangwei);
    	$this->display();
    	
    }
    
    //抢位
    public function qiang() {
    	
    	$id = abs(intval(I('id')));
    	$publish_type_id = abs(intval(I('tid')));
    	$sort = abs(intval(I('qwsort')));
    	$memberid = session('id');
    	// 判断位置是否正确
    	if ($sort < 1 || $sort > 12)
    	{
    		// 返回
	    	$result['type'] = 1;
	    	$result['msg'] = urlencode('抢位的位置范围不正确');
	    	exit(urldecode(json_encode($result)));
    	}
    	// 实例化
    	$Wx = D('weixin');
    	// 查询同栏目可占位数是否超出
    	$count = $Wx->where("publish_type_id = '%d' AND qiangwei_sort > 0 AND (unix_timestamp(now()) - qiangwei_time < 24*3600)", array($publish_type_id))->count('id');
    	if ($count > 0)
    	{
    		// 返回
	    	$result['type'] = 2;
	    	$result['msg'] = urlencode('同栏目可占位数超出限额');
	    	exit(urldecode(json_encode($result)));
    	}
    	else 
    	{
    		$SortCount = $Wx->where("publish_type_id = '%d' AND qiangwei_sort = '%d' AND (unix_timestamp(now()) - qiangwei_time < 24*3600)", array($publish_type_id, $sort))->count('id');
    		if ($SortCount > 0)
    		{
    			// 返回
	    		$result['type'] = 1;
	    		$result['msg'] = urlencode('该位置已被抢占了');
	    		exit(urldecode(json_encode($result)));
    		}
    	}
    	
    	// 占位操作
    	$Save['qiangwei_time'] = time();
    	$Save['qiangwei_sort'] = $sort;
    	$Save['publish_type_id'] = $publish_type_id;
    	
    	$Wx->where("id = '%d' AND memberid = '%d'", array($id, $memberid))->data($Save)->save();
    	
    	// 返回
    	$result['type'] = 1;
    	$result['msg'] = urlencode('抢位成功');
    	echo (urldecode(json_encode($result)));
    	
    }
    
    //微信群添加 php@zz
    public function add() {
        $this->checkUser();
        if(IS_POST){
            $model = D('Weixin');
            
            $_POST['pubaccount']=I('post.pubaccount');
            $_POST['wxaccount']=I('post.wxaccount');
            $_POST['ghweixin']=I('post.ghweixin');
            $_POST['website']=I('post.website');
            $_POST['sinaweibo']=I('post.sinaweibo');
            $_POST['tencentweibo']=I('post.tencentweibo');
            $_POST['title']=I('post.title');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            $_POST['weblogo']=I('post.weblogo');
            $_POST['webqrcode']=I('post.webqrcode');
            $_POST['tbshopurl']=I('post.tbshopurl');
            $_POST['ppshopurl']=I('post.ppshopurl');
            $_POST['tag']=I('post.tag');
            $_POST['content']=I('post.content');
            $_POST['realname']=I('post.realname');
            $_POST['phone']=I('post.phone');
            $_POST['qq']=I('post.qq','','int');
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');

            //新加的字段
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');
            $_POST['qunshangxian']=I('post.qunshangxian',0,'int');
            $_POST['renshu']=I('post.renshu',0,'int');
            $_POST['renzheng']=I('post.renzheng',1,'int');
            $_POST['maidian']=I('post.maidian');
            $_POST['img1']=I('post.img1');
            $_POST['img2']=I('post.img2');
            $_POST['img3']=I('post.img3');
            $_POST['img4']=I('post.img4');
            $_POST['img5']=I('post.img5');
			$_POST['img12']=I('post.img12');
            $_POST['qiangwei_time']=I('post.qiangwei_time');
            $_POST['qiangwei_sort']=I('post.qiangwei_sort',0,int);
			if($_POST['province']<0){$this->error("省份必填");}
			if($_POST['city']<0){$this->error("城市必填");}
			if(!$_POST['renshu']){$this->error("人数必填");}
			if(!$_POST['wxaccount']){$this->error("群主微信号必填");}
			
            //鱼福标示
            $yufumark=I('post.yufumark');
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['title'])){
                $_POST['title']=  I('post.pubaccount');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i',$value['weblogo'], $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i',$value['webqrcode'], $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i',$value['tbshopurl'], $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i',$value['ppshopurl'], $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_sort']);
                }else{
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i','', $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i','', $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i','', $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i','', $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i','', $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i','', $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i','', $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i','', $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i','', $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i','', $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i','', $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i','', $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i','', $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i','', $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i','', $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i','', $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i','', $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i','', $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i','', $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_sort']);
                }
                
            }
            
            // 生成二维码缩略图
            $qrcode = $_POST['qrcode'];
            $qrcode = str_replace('/../../../', '/', $qrcode);
            $qrcode = str_replace('//', '/', $qrcode);
            $qrcode_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$qrcode);
            $newname = time().rand(1111, 9999).'.jpg';
            $newpath = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname);
            $savepath = '/../../..//Uploads/'.$newname;
            $_POST['qrcode2'] = $savepath;
            // 生成缩略图
            import('ORG.Util.Image');
            $Image = new Image();
            $result = $Image::thumb($qrcode_path, $newpath, '', 130, 130);
            
           	// 生成封面图像缩略图
            $logo = $_POST['logo'];
            $logo = str_replace('/../../../', '/', $logo);
            $logo = str_replace('//', '/', $logo);
            $logo_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$logo);
            $newname2 = time().rand(1111, 9999).'.jpg';
            $newpath2 = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname2);
            $savepath2 = '/../../..//Uploads/'.$newname2;
            $_POST['logo2'] = $savepath2;
            // 生成缩略图
            $result2 = $Image::thumb($logo_path, $newpath2, '', 130, 130);
			
			
            
            //上传附件
            $this->_upload();
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            if(C('ISAUTOVERIFY')=="1"){
                if(trim(C('YUFUMARK'))==trim($yufumark)){
                    $model->status=1; 
                }else{
                    $model->status=2; 
                }
                
            }else{
               $model->status=2; 
            }
			
			$Gold=M("category");
			$cate_id = I('catid');
			$select['id'] = $cate_id;
			
			$will_gold_old = $Gold->where($select)->select();
			$will_gold = $will_gold_old[0]['gold'];
			
			$this->assign('will_gold',$will_gold);
			
            $model->typeid=1;
            $model->memberid=  session('id');
            $model->membername=  session('account');
            if($_POST['qiangwei_sort'] > 0)
                $model->qiangwei_time= time();
            //保存当前数据对象
            
			
			$qiangwei = $this->get_occupy($_POST['publish_type_id']);
			for($i=1;$i<=12;$i++){
			if($qiangwei[$i]['qiangwei_sort']==$_POST['qiangwei_sort'])
			 $this->error('已被抢占!');
			}
            $list = $model->add();
            if ($list !== false) { //保存成功
			
				//获取当前用户所剩金币总数
				$User=M("Member");
				
				
				$udata=$User->getById(session('id'));
				list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
				$old_gold = $udata['intergral'];
				$new_old = $old_gold-$will_gold;//每发一条消耗100金币
				if($new_old>=0){
					$update['id'] = array('eq',$udata['id']);
					$shuju['intergral'] = $new_old;
					$res = $User->where($update)->save($shuju);
					if($res){
						$this->success1('提交成功!');//跳转
					}else{
						//发布失败提示
						$this->error('发布失败,请重试!');
					}
				}else{
					//金币余额不足
					$this->error('金币余额不足!');
				}
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {
            
            //获取省级地区
            $province=D('areas')->where(array('parent_id'=>1))->select();
            $this->assign('province',$province);
    
            $this->seo('提交微信公众账号', '', '', 'add');

            //获取发布类别
            $publish_type = C('publish_type');
            $this->assign('publish_type',$publish_type);

            //获取群上限
            $qun_shang_xian = C('qun_shang_xian');
            $this->assign('qun_shang_xian',$qun_shang_xian);
            //发布类别id
            $publish_type_id = I('get.publish_type_id') ? I('get.publish_type_id') : 44;
            $this->assign('publish_type_id',$publish_type_id);

            //获取下级子分类
            $childCatMap['pid']=array('eq',44); //pid号 php@zz
            $childCatMap['status']=array('eq',1);
            $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
            $this->categorylist=$childCatList;  



            /*
            //获得抢位位序
            $qiangwei_sort = I('get.qiangwei_sort') ? I('get.qiangwei_sort') : 1;
            $this->assign('qiangwei_sort',$qiangwei_sort);
            */
            
            //获得抢位信息
            $qiangwei = $this->get_occupy($publish_type_id);
            $this->assign('qiangwei',$qiangwei);
			
			
			/* 合页 php@zz*/
			$publish_type_id = 44;
        $map['publish_type_id'] = array('eq',$publish_type_id);
        $this->assign("publish_type_status", $publish_type_id);

        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Weixin');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        //获取发布类别
        $publish_type = C('publish_type');
        $this->assign('publish_type',$publish_type);

        $this->seo('微信公众账号管理', '', '', 'manage');
           
            $this->display();
        }
       } 
		
		
		//个人微信号添加 php@zz
   
		 public function add_gr() {
        $this->checkUser();
				
        if(IS_POST){

        
            $model = D('Weixin');
						
            $_POST['pubaccount']=I('post.pubaccount');
            $_POST['wxaccount']=I('post.wxaccount');
            $_POST['ghweixin']=I('post.ghweixin');
            $_POST['website']=I('post.website');
            $_POST['sinaweibo']=I('post.sinaweibo');
            $_POST['tencentweibo']=I('post.tencentweibo');
            $_POST['title']=I('post.title');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            $_POST['weblogo']=I('post.weblogo');
            $_POST['webqrcode']=I('post.webqrcode');
            $_POST['tbshopurl']=I('post.tbshopurl');
            $_POST['ppshopurl']=I('post.ppshopurl');
            $_POST['tag']=I('post.tag');
            $_POST['content']=I('post.content');
            $_POST['realname']=I('post.realname');
            $_POST['phone']=I('post.phone');
            $_POST['qq']=I('post.qq','','int');
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');

            //新加的字段
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');
            $_POST['qunshangxian']=I('post.qunshangxian',0,'int');
            $_POST['renshu']=I('post.renshu',0,'int');
            $_POST['renzheng']=I('post.renzheng',1,'int');
            $_POST['maidian']=I('post.maidian');
            $_POST['img1']=I('post.img1');
            $_POST['img2']=I('post.img2');
            $_POST['img3']=I('post.img3');
            $_POST['img4']=I('post.img4');
            $_POST['img5']=I('post.img5');
			$_POST['img12']=I('post.img12');
            $_POST['qiangwei_time']=I('post.qiangwei_time');
            $_POST['qiangwei_sort']=I('post.qiangwei_sort',0,int);
			if(!$_POST['phone']){$this->error("手机号必填");}
			if($_POST['province']<0){$this->error("省份必填");}
			if($_POST['city']<0){$this->error("城市必填");}
            //鱼福标示
            $yufumark=I('post.yufumark');
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['title'])){
                $_POST['title']=  I('post.pubaccount');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i',$value['weblogo'], $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i',$value['webqrcode'], $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i',$value['tbshopurl'], $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i',$value['ppshopurl'], $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_sort']);
                }else{
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i','', $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i','', $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i','', $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i','', $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i','', $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i','', $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i','', $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i','', $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i','', $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i','', $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i','', $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i','', $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i','', $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i','', $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i','', $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i','', $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i','', $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i','', $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i','', $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_sort']);
                }
                
            }
            
            // 生成二维码缩略图
            $qrcode = $_POST['qrcode'];
            $qrcode = str_replace('/../../../', '/', $qrcode);
            $qrcode = str_replace('//', '/', $qrcode);
            $qrcode_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$qrcode);
            $newname = time().rand(1111, 9999).'.jpg';
            $newpath = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname);
            $savepath = '/../../..//Uploads/'.$newname;
            $_POST['qrcode2'] = $savepath;
            // 生成缩略图
            import('ORG.Util.Image');
            $Image = new Image();
            $result = $Image::thumb($qrcode_path, $newpath, '', 130, 130);
            
           	// 生成封面图像缩略图
            $logo = $_POST['logo'];
            $logo = str_replace('/../../../', '/', $logo);
            $logo = str_replace('//', '/', $logo);
            $logo_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$logo);
            $newname2 = time().rand(1111, 9999).'.jpg';
            $newpath2 = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname2);
            $savepath2 = '/../../..//Uploads/'.$newname2;
            $_POST['logo2'] = $savepath2;
            // 生成缩略图
            $result2 = $Image::thumb($logo_path, $newpath2, '', 130, 130);
			
			
            
			/*echo "<pre>";
			print_r($model->create());
            echo "</pre>";
			exit;*/
            //上传附件
            $this->_upload();
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            if(C('ISAUTOVERIFY')=="1"){
                if(trim(C('YUFUMARK'))==trim($yufumark)){
                    $model->status=1; 
                }else{
                    $model->status=2; 
                }
                
            }else{
               $model->status=2; 
            }
			
			$Gold=M("category");
			$cate_id = I('catid');
			$select['id'] = $cate_id;
			
			$will_gold_old = $Gold->where($select)->select();
			$will_gold = $will_gold_old[0]['gold'];
			

            $model->typeid=1;
            $model->memberid=  session('id');
            $model->membername=  session('account');
			
            if($_POST['qiangwei_sort'] > 0)
                $model->qiangwei_time= time();
            //保存当前数据对象
            
			
			$qiangwei = $this->get_occupy($_POST['publish_type_id']);
			for($i=1;$i<=12;$i++){
			if($qiangwei[$i]['qiangwei_sort']==$_POST['qiangwei_sort'])
			 $this->error('已被抢占!');
			}
            $list = $model->add();
            if ($list !== false) { //保存成功
			
				//获取当前用户所剩金币总数
				$User=M("Member");
				
				$udata=$User->getById(session('id'));
				list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
				$old_gold = $udata['intergral'];
				$new_old = $old_gold-$will_gold;//每发一条消耗100金币
				if($new_old>=0){
					$update['id'] = array('eq',$udata['id']);
					$shuju['intergral'] = $new_old;
					$res = $User->where($update)->save($shuju);
					if($res){
						$this->success1('提交成功!');//跳转
					}else{
						//发布失败提示
						$this->error('发布失败,请重试!');
					}
				}else{
					//金币余额不足
					$this->error('金币余额不足!');
				}
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {
            
            //获取省级地区
            $province=D('areas')->where(array('parent_id'=>1))->select();
            $this->assign('province',$province);
    
            $this->seo('提交微信公众账号', '', '', 'add');

            //获取发布类别
            $publish_type = C('publish_type');
            $this->assign('publish_type',$publish_type);

            //获取群上限
            $qun_shang_xian = C('qun_shang_xian');
            $this->assign('qun_shang_xian',$qun_shang_xian);
            //发布类别id
            $publish_type_id = I('get.publish_type_id') ? I('get.publish_type_id') : 44;
            $this->assign('publish_type_id',$publish_type_id);

            //获取下级子分类
            $childCatMap['pid']=array('eq',48);
            $childCatMap['status']=array('eq',1);
            $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
            $this->categorylist=$childCatList;  



            /*
            //获得抢位位序
            $qiangwei_sort = I('get.qiangwei_sort') ? I('get.qiangwei_sort') : 1;
            $this->assign('qiangwei_sort',$qiangwei_sort);
            */
            
            //获得抢位信息
            $qiangwei = $this->get_occupy($publish_type_id);
            $this->assign('qiangwei',$qiangwei);
			
			
			$publish_type_id = 48;
        $map['publish_type_id'] = array('eq',$publish_type_id);
        $this->assign("publish_type_status", $publish_type_id);

        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Weixin');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        //获取发布类别
        $publish_type = C('publish_type');
        $this->assign('publish_type',$publish_type);

        $this->seo('微信公众账号管理', '', '', 'manage');
			
			
			
			
			
			
			
			
			
			
			
			
			
           
            $this->display();
        }
	}
		
		
	//选择栏目所花费金币 php@zz	
	public function gold(){
		$lm_id = $_GET['lm_id'];
		$Gold=M("category");
		$select['id'] = $lm_id;
			
		$will_gold_old = $Gold->where($select)->select();
		$will_gold = $will_gold_old[0]['gold'];
		echo $will_gold;
	}
	
	//做任务领金币 php@zz
	public function gold_add(){
		
		$start_time = strtotime(date("Y-m-d 0:0:0"));
		$end_time = strtotime(date("Y-m-d 23:59:59"));
		$id = $_GET['id'];
		$Gold=M("member");
		$select['id'] = $id;
		$row = $Gold->where($select)->select();
		$gold_old = $row[0]['intergral'];
		$update_time = $row[0]['update_time'];
		if($update_time<$start_time || $update_time>$end_time){
			$gold_new = $gold_old+15;
			$time = time();
			$update['intergral'] = $gold_new;
			$update['update_time'] = $time;
			$res = $Gold->where($select)->save($update);
			if($res){
				echo "您已成功签到，并增加了15个金币!";
			}else{
				echo "对不起,请稍候再试";
			}
		}else{
			echo "你今天已经签到过了,明天再来吧";
		}
	}
	
	// 拉人领金币 php@zz
	public function gold_jingli(){
		$id = $_GET['id'];
		$ren = $_GET['ren'];
		$jin = $_GET['jin'];
		$User = M("member");
		$select['tuijian_id'] = $id;
		$row = $User->where($select)->select();
		$num = count($row);
		if($num>=$ren){
			$sel['id'] = $id;
			$row2 = $User->where($sel)->select();
			$gold_old = $row2[0]['intergral'];
			$gold_new = $gold_old+$jin;
			$update['intergral'] = $gold_new;
			if($ren==2){
				$update['cs1'] = 1;
			}else if($ren==5){
				$update['cs2'] = 1;
			}else if($ren==10){
				$update['cs3'] = 1;
			}else if($ren==20){
				$update['cs4'] = 1;
			}else if($ren==50){
				$update['cs5'] = 1;
			}
			$res = $User->where($sel)->save($update);
			if($res){
				echo "您已成功获取了".$jin."个金币!";
			}else{
				echo "对不起,请稍候再试";
			}
		}else{
			echo "对不起,你还没有推荐".$ren."个人";
		}
	}
	
	
		
	//微信公众号添加 php@zz
   
		 public function add_gz() {
        $this->checkUser();
        if(IS_POST){
            $model = D('Weixin');
            
            $_POST['pubaccount']=I('post.pubaccount');
            $_POST['wxaccount']=I('post.wxaccount');
            $_POST['ghweixin']=I('post.ghweixin');
            $_POST['website']=I('post.website');
            $_POST['sinaweibo']=I('post.sinaweibo');
            $_POST['tencentweibo']=I('post.tencentweibo');
            $_POST['title']=I('post.title');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            $_POST['weblogo']=I('post.weblogo');
            $_POST['webqrcode']=I('post.webqrcode');
            $_POST['tbshopurl']=I('post.tbshopurl');
            $_POST['ppshopurl']=I('post.ppshopurl');
            $_POST['tag']=I('post.tag');
            $_POST['content']=I('post.content');
            $_POST['realname']=I('post.realname');
            $_POST['phone']=I('post.phone');
            $_POST['qq']=I('post.qq','','int');
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');

            //新加的字段
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');
            $_POST['qunshangxian']=I('post.qunshangxian',0,'int');
            $_POST['renshu']=I('post.renshu',0,'int');
            $_POST['renzheng']=I('post.renzheng',1,'int');
            $_POST['maidian']=I('post.maidian');
            $_POST['img1']=I('post.img1');
            $_POST['img2']=I('post.img2');
            $_POST['img3']=I('post.img3');
            $_POST['img4']=I('post.img4');
            $_POST['img5']=I('post.img5');
			$_POST['img12']=I('post.img12');
            $_POST['qiangwei_time']=I('post.qiangwei_time');
            $_POST['qiangwei_sort']=I('post.qiangwei_sort',0,int);
			if($_POST['province']<0){$this->error("省份必填");}
			if($_POST['city']<0){$this->error("城市必填");}
			if(!$_POST['renshu']){$this->error("粉丝必填");}
            //鱼福标示
            $yufumark=I('post.yufumark');
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['title'])){
                $_POST['title']=  I('post.pubaccount');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i',$value['weblogo'], $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i',$value['webqrcode'], $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i',$value['tbshopurl'], $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i',$value['ppshopurl'], $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_sort']);
                }else{
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i','', $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i','', $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i','', $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i','', $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i','', $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i','', $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i','', $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i','', $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i','', $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i','', $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i','', $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i','', $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i','', $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i','', $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i','', $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i','', $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i','', $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i','', $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i','', $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_sort']);
                }
                
            }
            
            // 生成二维码缩略图
            $qrcode = $_POST['qrcode'];
            $qrcode = str_replace('/../../../', '/', $qrcode);
            $qrcode = str_replace('//', '/', $qrcode);
            $qrcode_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$qrcode);
            $newname = time().rand(1111, 9999).'.jpg';
            $newpath = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname);
            $savepath = '/../../..//Uploads/'.$newname;
            $_POST['qrcode2'] = $savepath;
            // 生成缩略图
            import('ORG.Util.Image');
            $Image = new Image();
            $result = $Image::thumb($qrcode_path, $newpath, '', 130, 130);
            
           	// 生成封面图像缩略图
            $logo = $_POST['logo'];
            $logo = str_replace('/../../../', '/', $logo);
            $logo = str_replace('//', '/', $logo);
            $logo_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$logo);
            $newname2 = time().rand(1111, 9999).'.jpg';
            $newpath2 = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname2);
            $savepath2 = '/../../..//Uploads/'.$newname2;
            $_POST['logo2'] = $savepath2;
            // 生成缩略图
            $result2 = $Image::thumb($logo_path, $newpath2, '', 130, 130);
			
			/*echo "<pre>";
			print_r($model->create());
            echo "</pre>";
			exit;*/
            //上传附件
            $this->_upload();
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            if(C('ISAUTOVERIFY')=="1"){
                if(trim(C('YUFUMARK'))==trim($yufumark)){
                    $model->status=1; 
                }else{
                    $model->status=2; 
                }
                
            }else{
               $model->status=2; 
            }
			
			$Gold=M("category");
			$cate_id = I('catid');
			$select['id'] = $cate_id;
			
			$will_gold_old = $Gold->where($select)->select();
			$will_gold = $will_gold_old[0]['gold'];
			

            $model->typeid=1;
            $model->memberid=  session('id');
            $model->membername=  session('account');
            if($_POST['qiangwei_sort'] > 0)
                $model->qiangwei_time= time();
            //保存当前数据对象
            
			
			$qiangwei = $this->get_occupy($_POST['publish_type_id']);
			for($i=1;$i<=12;$i++){
			if($qiangwei[$i]['qiangwei_sort']==$_POST['qiangwei_sort'])
			 $this->error('已被抢占!');
			}
            $list = $model->add();
            if ($list !== false) { //保存成功
			
				//获取当前用户所剩金币总数
				$User=M("Member");
				
				$udata=$User->getById(session('id'));
				list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
				$old_gold = $udata['intergral'];
				$new_old = $old_gold-$will_gold;//每发一条消耗100金币
				if($new_old>=0){
					$update['id'] = array('eq',$udata['id']);
					$shuju['intergral'] = $new_old;
					$res = $User->where($update)->save($shuju);
					if($res){
						$this->success1('提交成功!');//跳转
					}else{
						//发布失败提示
						$this->error('发布失败,请重试!');
					}
				}else{
					//金币余额不足
					$this->error('金币余额不足!');
				}
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {
            
            //获取省级地区
            $province=D('areas')->where(array('parent_id'=>1))->select();
            $this->assign('province',$province);
    
            $this->seo('提交微信公众账号', '', '', 'add');

            //获取发布类别
            $publish_type = C('publish_type');

            $this->assign('publish_type',$publish_type);

            //获取群上限
            $qun_shang_xian = C('qun_shang_xian');
            $this->assign('qun_shang_xian',$qun_shang_xian);
            //发布类别id
            $publish_type_id = I('get.publish_type_id') ? I('get.publish_type_id') : 44;
            $this->assign('publish_type_id',$publish_type_id);

            //获取下级子分类
            $childCatMap['pid']=array('eq',47);
            $childCatMap['status']=array('eq',1);
            $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
            $this->categorylist=$childCatList;  



            /*
            //获得抢位位序
            $qiangwei_sort = I('get.qiangwei_sort') ? I('get.qiangwei_sort') : 1;
            $this->assign('qiangwei_sort',$qiangwei_sort);
            */
            
            //获得抢位信息
            $qiangwei = $this->get_occupy($publish_type_id);
            $this->assign('qiangwei',$qiangwei);
			$publish_type_id = 47;
        $map['publish_type_id'] = array('eq',$publish_type_id);
        $this->assign("publish_type_status", $publish_type_id);

        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Weixin');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        //获取发布类别
        $publish_type = C('publish_type');
        $this->assign('publish_type',$publish_type);

        $this->seo('微信公众账号管理', '', '', 'manage');
           
            $this->display();
        }
	}
			
		
		
	//微信货源添加 php@zz
   
		 public function add_hy() {
        $this->checkUser();
        if(IS_POST){
            $model = D('Weixin');
            
            $_POST['pubaccount']=I('post.pubaccount');
            $_POST['wxaccount']=I('post.wxaccount');
            $_POST['ghweixin']=I('post.ghweixin');
            $_POST['website']=I('post.website');
            $_POST['sinaweibo']=I('post.sinaweibo');
            $_POST['tencentweibo']=I('post.tencentweibo');
            $_POST['title']=I('post.title');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            $_POST['weblogo']=I('post.weblogo');
            $_POST['webqrcode']=I('post.webqrcode');
            $_POST['tbshopurl']=I('post.tbshopurl');
            $_POST['ppshopurl']=I('post.ppshopurl');
            $_POST['tag']=I('post.tag');
            $_POST['content']=I('post.content');
            $_POST['realname']=I('post.realname');
            $_POST['phone']=I('post.phone');
            $_POST['qq']=I('post.qq','','int');
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');

            //新加的字段
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');
            $_POST['qunshangxian']=I('post.qunshangxian',0,'int');
            $_POST['renshu']=I('post.renshu',0,'int');
            $_POST['renzheng']=I('post.renzheng',1,'int');
            $_POST['maidian']=I('post.maidian');
            $_POST['img1']=I('post.img1');
            $_POST['img2']=I('post.img2');
            $_POST['img3']=I('post.img3');
            $_POST['img4']=I('post.img4');
            $_POST['img5']=I('post.img5');
			$_POST['img12']=I('post.img12');
            $_POST['qiangwei_time']=I('post.qiangwei_time');
            $_POST['qiangwei_sort']=I('post.qiangwei_sort',0,int);
			if($_POST['province']<0){$this->error("省份必填");}
			if($_POST['city']<0){$this->error("城市必填");}
			if(!$_POST['title']){$this->error("货源名称必填");}
			if(!$_POST['maidian']){$this->error("货源卖点必填");}
			if(!$_POST['content']){$this->error("货源描述必填");}
            //鱼福标示
            $yufumark=I('post.yufumark');
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['title'])){
                $_POST['title']=  I('post.pubaccount');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i',$value['weblogo'], $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i',$value['webqrcode'], $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i',$value['tbshopurl'], $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i',$value['ppshopurl'], $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_sort']);
                }else{
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i','', $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i','', $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i','', $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i','', $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i','', $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i','', $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i','', $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i','', $_POST['qq']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i','', $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i','', $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i','', $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i','', $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i','', $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i','', $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i','', $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i','', $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i','', $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i','', $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i','', $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_sort']);
                }
                
            }
            
            // 生成二维码缩略图
            $qrcode = $_POST['qrcode'];
            $qrcode = str_replace('/../../../', '/', $qrcode);
            $qrcode = str_replace('//', '/', $qrcode);
            $qrcode_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$qrcode);
            $newname = time().rand(1111, 9999).'.jpg';
            $newpath = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname);
            $savepath = '/../../..//Uploads/'.$newname;
            $_POST['qrcode2'] = $savepath;
            // 生成缩略图
            import('ORG.Util.Image');
            $Image = new Image();
            $result = $Image::thumb($qrcode_path, $newpath, '', 130, 130);
            
           	// 生成封面图像缩略图
            $logo = $_POST['logo'];
            $logo = str_replace('/../../../', '/', $logo);
            $logo = str_replace('//', '/', $logo);
            $logo_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$logo);
            $newname2 = time().rand(1111, 9999).'.jpg';
            $newpath2 = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname2);
            $savepath2 = '/../../..//Uploads/'.$newname2;
            $_POST['logo2'] = $savepath2;
            // 生成缩略图
            $result2 = $Image::thumb($logo_path, $newpath2, '', 130, 130);
			
			
            
            //上传附件
            $this->_upload();
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            if(C('ISAUTOVERIFY')=="1"){
                if(trim(C('YUFUMARK'))==trim($yufumark)){
                    $model->status=1; 
                }else{
                    $model->status=2; 
                }
                
            }else{
               $model->status=2; 
            }
			
			$Gold=M("category");
			$cate_id = I('catid');
			$select['id'] = $cate_id;
			
			$will_gold_old = $Gold->where($select)->select();
			$will_gold = $will_gold_old[0]['gold'];
			
			$this->assign('will_gold',$will_gold);

            $model->typeid=1;
            $model->memberid=  session('id');
            $model->membername=  session('account');
            if($_POST['qiangwei_sort'] > 0)
                $model->qiangwei_time= time();
            //保存当前数据对象
            
			
			$qiangwei = $this->get_occupy($_POST['publish_type_id']);
			for($i=1;$i<=12;$i++){
			if($qiangwei[$i]['qiangwei_sort']==$_POST['qiangwei_sort'])
			 $this->error('已被抢占!');
			}
            $list = $model->add();
            if ($list !== false) { //保存成功
                
				//获取当前用户所剩金币总数
				$User=M("Member");
				
				
				$udata=$User->getById(session('id'));
				list($udata['year'],$udata['month'],$udata['day']) = explode('-',$udata['birthday']);
				$old_gold = $udata['intergral'];
				$new_old = $old_gold-$will_gold;//每发一条消耗100金币
				if($new_old>=0){
					$update['id'] = array('eq',$udata['id']);
					$shuju['intergral'] = $new_old;
					$res = $User->where($update)->save($shuju);
					if($res){
						$this->success1('提交成功!');//跳转
					}else{
						//发布失败提示
						$this->error('发布失败,请重试!');
					}
				}else{
					//金币余额不足
					$this->error('金币余额不足!');
				}
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {
            
            //获取省级地区
            $province=D('areas')->where(array('parent_id'=>1))->select();
            $this->assign('province',$province);
    
            $this->seo('提交微信公众账号', '', '', 'add');

            //获取发布类别
            $publish_type = C('publish_type');

            $this->assign('publish_type',$publish_type);

            //获取群上限
            $qun_shang_xian = C('qun_shang_xian');
            $this->assign('qun_shang_xian',$qun_shang_xian);
            //发布类别id
            $publish_type_id = I('get.publish_type_id') ? I('get.publish_type_id') : 44;
            $this->assign('publish_type_id',$publish_type_id);

            //获取下级子分类
            $childCatMap['pid']=array('eq',1);
            $childCatMap['status']=array('eq',1);
            $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
            $this->categorylist=$childCatList;  



            /*
            //获得抢位位序
            $qiangwei_sort = I('get.qiangwei_sort') ? I('get.qiangwei_sort') : 1;
            $this->assign('qiangwei_sort',$qiangwei_sort);
            */
            
            //获得抢位信息
            $qiangwei = $this->get_occupy($publish_type_id);
            $this->assign('qiangwei',$qiangwei);
			
			$publish_type_id = 1;
        $map['publish_type_id'] = array('eq',$publish_type_id);
        $this->assign("publish_type_status", $publish_type_id);

        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Weixin');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        //获取发布类别
        $publish_type = C('publish_type');
        $this->assign('publish_type',$publish_type);

        $this->seo('微信公众账号管理', '', '', 'manage');
           
            $this->display();
        }
	}
			
		
		
		
		
		
		
    
    //公众账号修改
    public function edit() {
        $this->checkUser();
        if(IS_POST){
            
            $model = D('Weixin');

            $_POST['pubaccount']=I('post.pubaccount');
            $_POST['wxaccount']=I('post.wxaccount');
            $_POST['ghweixin']=I('post.ghweixin');
            $_POST['website']=I('post.website');
            $_POST['sinaweibo']=I('post.sinaweibo');
            $_POST['tencentweibo']=I('post.tencentweibo');
            $_POST['title']=I('post.title');
            $_POST['realname']=I('post.realname');
            $_POST['phone']=I('post.phone');
            $_POST['qq']=I('post.qq','','int');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            $_POST['weblogo']=I('post.weblogo');
            $_POST['webqrcode']=I('post.webqrcode');
            $_POST['tbshopurl']=I('post.tbshopurl');
            $_POST['ppshopurl']=I('post.ppshopurl');
            $_POST['tag']=I('post.tag');
            $_POST['content']=I('post.content');

            //新加的字段
            $_POST['publish_type_id']=I('post.publish_type_id',1,'int');
            $_POST['qunshangxian']=I('post.qunshangxian',0,'int');
            $_POST['renshu']=I('post.renshu',0,'int');
            $_POST['renzheng']=I('post.renzheng',1,'int');
            $_POST['maidian']=I('post.maidian');
            $_POST['img1']=I('post.img1');
            $_POST['img2']=I('post.img2');
            $_POST['img3']=I('post.img3');
            $_POST['img4']=I('post.img4');
            $_POST['img5']=I('post.img5');
			$_POST['img12']=I('post.img12');
            $_POST['qiangwei_time']=I('post.qiangwei_time');
            $_POST['qiangwei_sort']=I('post.qiangwei_sort',0,int);
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['title'])){
                $_POST['title']=  I('post.pubaccount');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qq']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i',$value['weblogo'], $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i',$value['webqrcode'], $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i',$value['tbshopurl'], $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i',$value['ppshopurl'], $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qiangwei_sort']);
                }else{
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i','', $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i','', $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['tencentweibo']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i','', $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i','', $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i','', $_POST['qq']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i','', $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i','', $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['ppshopurl']);
                    $_POST['tag']=preg_replace('/'.$value['badword'].'/i','', $_POST['tag']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    //新加
                    $_POST['publish_type_id']=preg_replace('/'.$value['badword'].'/i','', $_POST['publish_type_id']);
                    $_POST['qunshangxian']=preg_replace('/'.$value['badword'].'/i','', $_POST['qunshangxian']);
                    $_POST['renshu']=preg_replace('/'.$value['badword'].'/i','', $_POST['renshu']);
                    $_POST['renzheng']=preg_replace('/'.$value['badword'].'/i','', $_POST['renzheng']);
                    $_POST['maidian']=preg_replace('/'.$value['badword'].'/i','', $_POST['maidian']);
                    $_POST['img1']=preg_replace('/'.$value['badword'].'/i','', $_POST['img1']);
                    $_POST['img2']=preg_replace('/'.$value['badword'].'/i','', $_POST['img2']);
                    $_POST['img3']=preg_replace('/'.$value['badword'].'/i','', $_POST['img3']);
                    $_POST['img4']=preg_replace('/'.$value['badword'].'/i','', $_POST['img4']);
                    $_POST['img5']=preg_replace('/'.$value['badword'].'/i','', $_POST['img5']);
					$_POST['img12']=preg_replace('/'.$value['badword'].'/i','', $_POST['img12']);
                    $_POST['qiangwei_time']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_time']);
                    $_POST['qiangwei_sort']=preg_replace('/'.$value['badword'].'/i','', $_POST['qiangwei_sort']);
                }
                
            }
            
            // 生成二维码缩略图
            $qrcode = $_POST['qrcode'];
            $qrcode = str_replace('/../../../', '/', $qrcode);
            $qrcode = str_replace('//', '/', $qrcode);
            $qrcode_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$qrcode);
            $newname = time().rand(1111, 9999).'.jpg';
            $newpath = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname);
            $savepath = '/../../..//Uploads/'.$newname;
            $_POST['qrcode2'] = $savepath;
            // 生成缩略图
            import('ORG.Util.Image');
            $Image = new Image();
            $result = $Image::thumb($qrcode_path, $newpath, '', 130, 130);
            
           	// 生成封面图像缩略图
            $logo = $_POST['logo'];
            $logo = str_replace('/../../../', '/', $logo);
            $logo = str_replace('//', '/', $logo);
            $logo_path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$logo);
            $newname2 = time().rand(1111, 9999).'.jpg';
            $newpath2 = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/Uploads/'.$newname2);
            $savepath2 = '/../../..//Uploads/'.$newname2;
            $_POST['logo2'] = $savepath2;
            // 生成缩略图
            $result2 = $Image::thumb($logo_path, $newpath2, '', 130, 130);

            //上传附件
            $this->_upload();

            if($_POST['qiangwei_sort'] == 0 AND I('check_qiangwei_sort') > 0)
            {
                $_POST['qiangwei_sort'] = I('check_qiangwei_sort');
            }

            $record = D("weixin")->field("qiangwei_sort")->where("id=".I('id'))->find();

            if($record['qiangwei_sort'] != $_POST['qiangwei_sort'] && $_POST['qiangwei_sort'] > 0)
            {
                $_POST['qiangwei_time']= time();
            }

            if (false === $model->create()) {
                $this->error($model->getError());
            }
            
            $model->typeid=1;
            $model->status=  2;
            $model->memberid=  session('id');
            $model->membername=  session('account');
            if($_POST['qiangwei_sort'] > 0)
                $model->qiangwei_time= time();      
            //保存当前数据对象
			
			$qiangwei = $this->get_occupy($_POST['publish_type_id']);
			for($i=1;$i<=12;$i++){
			if($qiangwei[$i]['qiangwei_sort']==$_POST['qiangwei_sort'])
			 $this->error('已被抢占!');
			}
			
            $list = $model->save();
            
            if ($list !== false) {
                $this->success('保存成功!',U('Member/manage'));
            } else {
                $this->error('保存失败!');
            }
            
            
        }else{
            //获取最大等级
            $Category=M('Category');
            $maxLevel = $Category->max('level');
            $this->assign('maxLevel',$maxLevel);
            
            //获取省级地区
            $province=D('areas')->where(array('parent_id'=>1))->select();
            $this->assign('province',$province);

            $model = M('Weixin');
            $id = $_REQUEST [$model->getPk()];
            $vo = $model->getById($id);
            $this->assign('vo', $vo);
            $this->assign('publish_type_id', $vo['publish_type_id']);
            $this->assign('publish_type_status', $vo['publish_type_id']);

            //获取发布类别
            $publish_type = C('publish_type');
            $this->assign('publish_type',$publish_type);

            //获取下级子分类
            $childCatMap['pid']=array('eq',$vo['publish_type_id']);
            $childCatMap['status']=array('eq',1);
            $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
            $this->categorylist=$childCatList;  

            //获取群上限
            $qun_shang_xian = C('qun_shang_xian');
            $this->assign('qun_shang_xian',$qun_shang_xian);

            //获得抢位信息
            $qiangwei = $this->get_occupy($vo['publish_type_id']);
            $this->assign('qiangwei',$qiangwei);
            $this->seo('修改微信公众账号', '', '', 'edit');
            $this->display();
        }
    }
    //公众账号删除
    public function delete() {
        $this->checkUser();
        //删除指定记录
        $model = M('Weixin');
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
                $condition['memberid']=  session('id');
                $condition['membername']=  session('account');
                $list = $model->where($condition)->setField('status', - 1);
                if ($list !== false) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
    }
    //公众账号删除
    public function delfile() {
        $this->checkUser();
        
        if(isset($_GET['id'])&&isset($_GET['file'])){
            $id = $_GET['id'];	
            $file=$_GET['file'];

            
            $model = D('Weixin');
            $map['id']=$id;
            $map['memberid']=  session('id');
            $map['membername']=  session('account');
            $src = '__ROOT__Uploads/'.$model->where($map)->getField($file);

            $model->where('id='.$id)->setField($file,'');
            if(is_file($src))unlink($src);
            $this->success('操作成功');
        }
        
        
    }
    //删除图片
    public function delthumb(){
        $this->checkUser();
        
        if(isset($_GET['id'])&&isset($_GET['file'])){
            $id = I('get.id');
            $file=I('get.file');
            
            $name = $this->getActionName();
            $model = D($name);
            
            $src = './Uploads'.$model->where('id='.$id)->getField($file);
            $model->where('id='.$id)->setField($file,'');

            if(is_file($src)) unlink($src);


            $this->success('操作成功');
        }
    }
    //推荐管理
    public function tuijian() {
        $this->checkUser();
        
        
        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Weixin');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        $this->seo('微信公众账号管理', '', '', 'tuijian');
        $this->display(); 
        
    }
    //公众账号推荐
    public function addtj() {
        $this->checkUser();

        if(IS_POST){
            
            $model = D('Tuijian');
            
            $wxid=I('post.wxid');
            if(isset($wxid)){
                $map['id']=array('eq',$wxid);
                $status=D('Weixin')->where($map)->getField('status');
                if($status!=1){
                    $this->error('操作错误');
                }
            }else{
                $this->error('操作错误');
            }
            
           
            
            $_POST['wxid']=$wxid;
            $_POST['pubaccount']=  getWeixinName($wxid);
            $recommendid=I('post.recommendid');
            $_POST['recommendid']=$recommendid;
            $starttime=strtotime(I('post.starttime'));
            $_POST['starttime']=$starttime;
            $_POST['endtime']=strtotime(I('post.endtime'));
            $intergralnum=  getIntergral($_POST['recommendid']);
            
             //推荐天数
            $time1 = $_POST['starttime'];
            $time2 = $_POST['endtime'];
            if($time1>$time2){
                $this->error('开始日期大于结束日期');
            }
            
            $time = ($time2-$time1)/(24*3600)+1;
            
            //扣除积分
            $intergral=$time*$intergralnum;
            
            //查询用户当前积分
            $mapmember['id']=array('eq',  session('id'));
            $mapmember['account']=array('eq',  session('account'));
            $curintergral=D('Member')->where($mapmember)->getField('intergral');
            if($intergral>$curintergral){
                $this->error('当前用户积分不够！');
            }
            
            //判断是否已经推荐
            $maptj['wxid']=array('eq',$wxid);
            $maptj['endtime']=array('gt',$starttime);
            $maptj['recommendid']=array('eq',$recommendid);
            
            $istj=D('tuijian')->where($maptj)->find();
            if(!empty($istj)){
                $this->error('已推荐过了');
            }
            
            
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            $model->timelimit=1;
            $model->intergral=$intergral;
            $model->intergralnum=$intergralnum;
            $model->memberid=  session('id');
            $model->membername=  session('account');
           
            //保存当前数据对象
            $list = $model->add();
            if ($list !== false) { //保存成功
            
                if($intergral>0){
                    //添加消费记录
                    $Payspend=M('Payspend');
                    $data['memberid']=session('id');
                    $data['membername']=session('account');
                    $data['type']=2;
                    $data['value']=$intergral;
                    $data['msg']="自助推荐";
                    $data['create_time']=time();
                    $Payspend->add($data);

                    //扣除用户积分
                    D("Member")->where($mapmember)->setDec('intergral',$intergral); // 用户的积分减5
                }
                
                $this->success('提交成功!',U('Member/tjlist'));
                
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {


            $Model=M('Weixin');
            $map['memberid']=  session('id');
            $map['membername']=  session('account');
            $map['status']=array('eq',1);
            $pubaccountlist=$Model->where($map)->select();
            
             //推荐方式
            $maprecommend['status']=array('eq',1);
            $recommendlist=D('Recommend')->where($maprecommend)->order('listorder')->select();
            
            
            $this->assign('pubaccountlist',$pubaccountlist);
            $this->assign('recommendlist',$recommendlist);
            $this->seo('自助推荐', '', '', 'tjlist');
            $this->display();
        }
        
    }
    
    //公众账号管理
    public function tjlist() {
        $this->checkUser();
        
        if(isset($_POST['name'])){
            $map['pubaccount'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Weixin');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Tuijian');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('create_time desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        $this->seo('自助推荐', '', '', 'tjlist');
        $this->display(); 
        
    }
    //促销活动管理
    public function promotionlist() {
        $this->checkUser();
        
        if(isset($_POST['name'])){
            $map['title'] = array('like',"%".I('post.name')."%");
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',I('zt'));
            $this->zt=I('zt');
        }
        $map['memberid']=array('eq',  session('id'));
        $map['membername']=array('eq',  session('account'));
        
        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq','Promotion');
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M('Promotion');
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('create_time desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);

        $this->seo('促销活动', '', '', 'promotionlist');
        $this->display(); 
        
    }
    
    //公众账号添加
    public function promotionadd() {
        $this->checkUser();
        if(IS_POST){
            $intergral=C('XFINTERGRAL');
            
            $model = D('Promotion');
            $_POST['title']=I('post.title');
            $_POST['content']=I('post.content');
            $_POST['sitetitle']=I('post.sitetitle');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['sitetitle'])){
                $_POST['sitetitle']=  I('post.title');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['sitetitle']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sitetitle']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                   
                }else{
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['sitetitle']=preg_replace('/'.$value['badword'].'/i','', $_POST['sitetitle']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                  
                }
                
            }
            
            //上传图片
            $this->_upload();
            
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            $model->intergral=$intergral;
            $model->memberid=  session('id');
            $model->membername=  session('account');
            //保存当前数据对象
            $list = $model->add();
            if ($list !== false) { //保存成功
                if($intergral>0){
                    //添加消费记录
                    $Payspend=M('Payspend');
                    $data['memberid']=session('id');
                    $data['membername']=session('account');
                    $data['type']=2;
                    $data['value']=$intergral;
                    $data['msg']="促销活动";
                    $data['create_time']=time();
                    $Payspend->add($data);
                
                    //扣除用户积分
                    $mapmember['id']=session('id');
                    $mapmember['account']=  session('account');
                    D("Member")->where($mapmember)->setDec('intergral',$intergral); // 用户的积分减5
                }
                
                $this->success('提交成功!',U('Member/promotionlist'));
                
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {
            $Model=M('Weixin');
            $map['memberid']=  session('id');
            $map['membername']=  session('account');
            $map['status']=array('eq',1);
            $pubaccountlist=$Model->where($map)->select();
            
            $catid=I('get.catid',0,int);
            if($catid==0){
                $this->error('操作错误');  
            }
            $this->assign('catid',$catid);
            $this->assign('pubaccountlist',$pubaccountlist);
            $this->seo('添加促销活动', '', '', 'promotionlist');
            $this->display();
        }
        
    }
    //公众账号修改
    public function promotionedit() {
        $this->checkUser();
        if(IS_POST){
            
            $model = D('Promotion');
            
            $_POST['title']=I('post.title');
            $_POST['content']=I('post.content');
            $_POST['sitetitle']=I('post.sitetitle');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['sitetitle'])){
                $_POST['sitetitle']=  I('post.title');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['sitetitle']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sitetitle']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                   
                }else{
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['sitetitle']=preg_replace('/'.$value['badword'].'/i','', $_POST['sitetitle']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                  
                }
                
            }
            //上传图片
            $this->_upload();
            
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            
            $model->memberid=  session('id');
            $model->membername=  session('account');
            //保存当前数据对象
            $list = $model->save();
            
            if ($list !== false) {
                $this->success('保存成功!',U('Member/promotionlist'));
            } else {
                $this->error('保存失败!');
            }
            
        }else{
            $Model=M('Weixin');
            $map['memberid']=  session('id');
            $map['membername']=  session('account');
            $map['status']=array('eq',1);
            $pubaccountlist=$Model->where($map)->select();
            
            $model = M('Promotion');
            $id = $_REQUEST [$model->getPk()];
            $vo = $model->getById($id);
            
            $this->assign('pubaccountlist', $pubaccountlist);
            $this->assign('vo', $vo);

            $this->seo('修改促销活动', '', '', 'promotionlist');
            $this->display();
        }
    }
    
    /* 图片上传 */
    public function wxupload(){
        if(!empty($_FILES))
        {
        var_dump($_FILES);exit;
            import("@.ORG.Util.Image");
            import("@.ORG.UploadFile");
            //导入上传类
            $upload = new UploadFile();
            //设置上传文件大小
            $upload->maxSize = 524288;
            //设置上传文件类型
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
            //设置附件上传目录
            $y = date('Y',time());
            $m = date('m',time());
            $d = date('d',time());
            
            $dir='./Uploads';
            
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/'.$y;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/'.$m;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/'.$d;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/';
            //上传目录
            $upload->savePath =$dir;//'../Uploads/';

            // 设置引用图片类库包路径
            //$upload->imageClassPath = '@.ORG.Util.Image';
            //设置需要生成缩略图，仅对图像文件有效
            //$upload->thumb = true;
            //设置需要生成缩略图的文件后缀
            //$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
            //$height = I('post.height');
            //$width = I('post.width');
            //设置缩略图最大宽度
            //$upload->thumbMaxWidth = $width;
            //设置缩略图最大高度
            //$upload->thumbMaxHeight = $height;
            //设置上传文件规则
            $upload->saveRule = uniqid;
            //删除原图
            $upload->thumbRemoveOrigin = false;


           
            $type = I('get.type');
            $result = array();
            $result['success'] = false;
            $successNum = 0;
            //定义一个变量用以储存当前头像的序号
            $avatarNumber = 1;
            $i = 0;
            $msg = '';

            if (!$upload->upload()) {
                //捕获上传异常
                $msg=$upload->getErrorMsg();
            } else {
                //取得成功上传的文件信息
                $uploadList = $upload->getUploadFileInfo();
                foreach ($uploadList as $key => $value) {
                    foreach ($_FILES as $key1 => $value1) {
                        if($value['name']===$value1['name']){

                            $_POST[$key1] = '/'.$y.'/'.$m.'/'.$d.'/'.$value['savename'];
                            $successNum++;
                        }
                    }
                    
                }
                $msg = '上传成功！';
                
            }      


          
            $result['msg'] = $msg;
            if ($successNum > 0)
            {
                $result['success'] = true;
            }
            //返回图片的保存结果（返回内容为json字符串）
            print json_encode($result);

        }
        else
        {
            /*获取传递过来的长，宽以及类型*/
            $width = I('get.w');
            $height = I('get.h');
            $type = I('get.type');
            $this->assign('w',$width);
            $this->assign('h',$height);
            $this->assign('type',$type);
            $this->display();
        }
    }
    
    /**************************************************************
    *  生成指定长度的随机码。
    *  @param int $length 随机码的长度。
    *  @access public
    **************************************************************/
    function createRandomCode($length)
    {
        $randomCode = "";
        $randomChars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $length; $i++)
        {
            $randomCode .= $randomChars { mt_rand(0, 35) };
        }
        return $randomCode;
    }

    /*生成占位数组信息*/
    function get_occupy($publish_type_id){

        $now = time();
        //获取抢位位序
        $field = 'id,publish_type_id,qiangwei_sort,qiangwei_time';
        $qiangwei = D('weixin')->field($field)->where('publish_type_id='.$publish_type_id.' AND qiangwei_sort > 0 AND (unix_timestamp(now())-qiangwei_time < 24*3600)')->select();
        
        $temp = array();
        foreach ($qiangwei as $k => $v) {
            $temp[$v['qiangwei_sort']] = $v;
        }

        $return = array();
        for($i=1;$i<=12;$i++)
        {
            if(array_key_exists($i, $temp))
                $return[$i] = $temp[$i];
            else
                $return[$i] = null;
        }

        return $return;
    }
/*
用户名:$username,
密码:$password,
邮箱:$email
*/
//Ucenter Thinkphp 会员整合同步 php@zz
public function addmember(){
	if($this->isPost()){
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = trim($_POST['password']);
	import("@.ORG.UcService");//导入UcService.class.php类
	$ucService = new UcService;//实例化UcService类
	$uid = $ucService->register($username, $password, $email);//注册到UCenter
	if($uid){//如果上面注册成功将返回一个int类型的数字
	$M = D('Member');
	if($vo = $M->create()) {
	if($M->add()) {
	$this->success('注册成功!');
	}else{
	$this->error('注册失败!');
	}
	}else{
	$this->error();
	}
	}else{
	exit($uid);
	}
	}else{
	$this->error('非法数据!');
	}
	}
	
}
?>