<?php

class WxapiAction extends CommonAction{
   public function mapi(){
        
        define("TOKEN", C(WX_TOKEN));
        $this->valid();
        
    }
    public function valid(){
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            $this->responseMsg();
            exit;
        }
    }
    //回复消息
    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        
      	//extract post data
        if (!empty($postStr)){
            
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();

            if(!empty($keyword)){
                $resultStr =$this->voperate($fromUsername,$toUsername,$keyword);
                echo $resultStr;
            }else{
                $set=D("Set")->find();
                $textTpl="<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName> 
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>1</FuncFlag>
                </xml>";
                $msgType = "text";
                $contentStr = $set['welcomeinfo'];
                echo $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }

        }else {
        	echo "";
        	exit;
        }
    }
    //验证
    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
                return true;
        }else{
                return false;
        }
    }
    public function voperate($fromUsername,$toUsername,$keyword){

        if(empty($keyword)){
            return '请输入关键词';
        }
        $map['status']=array('eq',1);
        $map['pubaccount']=array('like','%'.$keyword.'%');
        $weixin=M('Weixin');
        $count=$weixin->where($map)->count();

        $textTpl="<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName> 
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>1</FuncFlag>
        </xml>";
        $msgType = "text";
        if($count>0){
            $url=U('Wxapi/msearch',array('keyword'=>$keyword),'','',true);
            $contentStr = "搜索到关于【".$keyword."】的账号（".$count."）个，【<a href=\"".$url."\">查看详细</a>】";
        }else{
            $url=U('Wxapi/msearch','','','',true);
            $contentStr = "抱歉，未搜索到相关微信！【<a href=\"".$url."\">查看精品推荐</a>】";
        }
        
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

        return $resultStr;
    }
    //手机搜索
    public function msearch() {
        
        $keyword =I('get.keyword');
        if(!empty($keyword)){
            $map['pubaccount']=array('like','%'.$keyword.'%');
            $this->keyword=$keyword;
        }else{
            $map['tuijian']=array('eq',1);
        }
        $map['status']=array('eq',1);
        
        $weixin=M('Weixin');
        $list=$weixin->where($map)->order('hits desc')->select();
        $this->list=$list;
        $this->display();
    }
    //手机显示
    public function mshow() {
        $id =I('get.id','','int');
        if(!empty($id)){
            $data = M('Weixin')->where('status=1')->find($id);
        }
        
        $this->data=$data;
        $this->display();
    }
    
}

?>
