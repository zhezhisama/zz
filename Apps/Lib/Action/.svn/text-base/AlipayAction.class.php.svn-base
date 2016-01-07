<?php
// 本类由系统自动生成，仅供测试用途
class AlipayAction extends Action {
    function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        import('@.ORG.Alipay');
    }
    public function index(){
	$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function alipay_config() {

       //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//        $typeid=I('WIDtype');
//        if(!isset($typeid)){
//            $this->error('操作错误，如需帮助请联系管理员。');
//        }

       $alipay_config['pay_account']		= 'ggmmdd2011@qq.com';
       
        //合作身份者id，以2088开头的16位纯数字
       $alipay_config['partner']		= '2088702430575474';

       //安全检验码，以数字和字母组成的32位字符
       $alipay_config['key']			= '6y640m21w6vagxkxkpz80y20bezow7ee';

       
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
        
        $alipay_config=$this->alipay_config();
        /**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = U('Alipay/notify_url','','','',true);
//        $notify_url ='http://'.$_SERVER['HTTP_HOST'].'/wx/index.php?m=Alipay&a=notify_url';//U('Alipay/notify_url');
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = U('Alipay/return_url','','','',true);
//        $return_url = 'http://'.$_SERVER['HTTP_HOST'].'/wx/index.php?m=Alipay&a=return_url';//U('Alipay/return_url');
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
//        $seller_email = $_POST['WIDseller_email'];
        $seller_email =$alipay_config['pay_account'];
        //必填

        //商户订单号
//        $out_trade_no = $_POST['WIDout_trade_no'];
        $out_trade_no =create_sn(); //date('YmdHis').rand(0,9999);
        //商户网站订单系统中唯一订单号，必填

        //订单名称
//        $subject = $_POST['WIDsubject'];
        $subject="在线充值";
        //必填

        //付款金额
        $price = '0.01';
 
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

        dump($parameter);
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认并支付");
        echo $html_text;
        
//        $model=M('Payment');
//        $data['memberid']=session('id');
//        $data['membername']=session('account');
//        $data['payno']=$out_trade_no;
//        $data['businesstype']=$subject;
//        $data['paytypeid']=$alipay_config['payid'];
//        $data['paytypename']=$alipay_config['payname'];
//        $data['paymoney']=$price;
//        $data['payip']=get_client_ip();
//        $data['paybody']=$body;
//        $data['status']='unpay';
//        $data['create_time']=time();
//        if(false===$model->add($data)){
//            $this->error('操作失败');
//        }
//        $this->assign('paymoney',$price);
//        $this->assign('paytypename',$alipay_config['payname']);
//        $this->assign('html_text',$html_text);
//        $this->seo('支付确认', '', '', 'pay');
//        C('TOKEN_ON',false);//关闭表单令牌
//        $this->display();
        
        
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


                if($_POST['trade_status'] == 'WAIT_BUYER_PAY') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录，但没有付款
                        //判断该笔订单是否在商户网站中已经做过处理
                                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                                //如果有做过处理，不执行商户的业务程序
                    
                   
                    
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录1");
            }
                else if($_POST['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
                //该判断表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货
                        //判断该笔订单是否在商户网站中已经做过处理
                                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                                //如果有做过处理，不执行商户的业务程序

                  
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录2");
            }
                else if($_POST['trade_status'] == 'WAIT_BUYER_CONFIRM_GOODS') {
                //该判断表示卖家已经发了货，但买家还没有做确认收货的操作
                        //判断该笔订单是否在商户网站中已经做过处理
                                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                                //如果有做过处理，不执行商户的业务程序
                 
                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录3");
            }
                else if($_POST['trade_status'] == 'TRADE_FINISHED') {
                //该判断表示买家已经确认收货，这笔交易完成
                        //判断该笔订单是否在商户网站中已经做过处理
                                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                                //如果有做过处理，不执行商户的业务程序
             

                echo "success";		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                logResult("这里写入想要调试的代码变量值，或其他运行的结果记录4");
            }
            else {
                //其他状态判断
                echo "success";

                //调试用，写文本函数记录程序运行情况是否正常
                logResult ("这里写入想要调试的代码变量值，或其他运行的结果记录5");
            }

                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            logResult("这里写入想要调试的代码变量值，或其他运行的结果记录6");
        }
        
    }
    /* * 
    * 功能：支付宝页面跳转同步通知页面
    * 版本：3.3
    * 日期：2012-07-23
    */
    public function return_url() {
       
        $alipay_config=$this->alipay_config();
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {//验证成功
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //请在这里加上商户的业务逻辑程序代码

                //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

                //商户订单号

                $out_trade_no = $_GET['out_trade_no'];

                //支付宝交易号

                $trade_no = $_GET['trade_no'];

                //交易状态
                $trade_status = $_GET['trade_status'];


            if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
                        //判断该笔订单是否在商户网站中已经做过处理
                                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                                //如果有做过处理，不执行商户的业务程序
            }
            else if($_GET['trade_status'] == 'TRADE_FINISHED') {
                        //判断该笔订单是否在商户网站中已经做过处理
                                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                                //如果有做过处理，不执行商户的业务程序
            }
            else {
              echo "trade_status=".$_GET['trade_status'];
            }

                echo "验证成功<br />";
                echo "trade_no=".$trade_no;
               

                //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "验证失败";
        }
        
        
    }
}