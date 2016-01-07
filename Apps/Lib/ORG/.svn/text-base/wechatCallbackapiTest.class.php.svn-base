<?php

class wechatCallbackapiTest
{
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
   
            
            if(!empty( $keyword ))
            {
                
                $resultStr =$this->voperate($fromUsername,$toUsername,$keyword);
                
                echo $resultStr;
            }else{
                echo "Input something...";
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

        $textTpl="<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName> 
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[%s]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>1</FuncFlag>
        </xml>";
        $msgType = "text";
        $contentStr = "抱歉，未搜索到相关微信！【<a href=\"http://www.baidu.com\">查看精品推荐</a>】";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

        return $resultStr;
    }
    
    //日志
    public function logger($content) {
        file_put_contents("log.html", date('Y-m-d H:i:s').$content."<br>",FILE_APPEND);
    }
}
?>
