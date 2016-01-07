<?php
header("Content-type: text/html; charset=utf-8");
class RedCash {
	private $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	
	function  __construct($cash_conf = array()){
		$this->openid = $cash_conf['useropenid'];
		$this->amount = $cash_conf['amount'];
		$this->appid = $cash_conf['appid'];
		$this->mchid = $cash_conf['mchid'];
		$this->key = $cash_conf['key'];
		$this->send_name = $cash_conf['send_name'];
		$this->act_name = $cash_conf['act_name'];
	}
	
	function sendRedCash( ) {
		$package = array();
		$package['nonce_str'] = $this->createNoncestr(32);
		$package['mch_billno'] = $this->mchid.date('YmdHis').rand(1000, 9999);
		$package['mch_id'] = $this->mchid;
		$package['wxappid'] = $this->appid;
		//$package['nick_name'] =$this->send_name;
		$package['send_name'] = "天使微商";
		$package['re_openid'] = $this->openid;
		$package['total_amount'] = $this->amount;
		$package['min_value'] = $this->amount;
		$package['max_value'] = $this->amount;
		$package['total_num'] = 1;
		$package['wishing'] = $this->act_name;
		$package['client_ip'] = $this->getClientIP();
		$package['act_name'] = $this->act_name;
		$package['remark'] = $this->act_name;

		ksort($package, SORT_STRING);
		$strSign = '';
		foreach($package as $key => $v) {
			$strSign .= "{$key}={$v}&";
		}
		$strSign .= "key={$this->key}";
		$package['sign'] = strtoupper(md5($strSign));

		$xml = $this->arrayToXml($package);
		$certs = array(
			'SSLCERT' => getcwd().'\Application\Common\hongbao\apiclient_cert.pem',
			'SSLKEY' => getcwd().'\Application\Common\hongbao\apiclient_key.pem',
			'CAINFO' => getcwd().'\Application\Common\hongbao\rootca.pem',
		);
		
		$response = $this->http_request($this->url, $xml, $certs, 'post');
      
		$responseObj = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
		$aMsg = (array)$responseObj;
		
		if (isset($aMsg['err_code'])) {
			$db_data['err_code'] = $aMsg['err_code'];
			$db_data['err_code_des'] = $aMsg['err_code_des'];
		}else {
			$db_data['err_code'] = 'SUCCESS';
			$db_data['err_code_des'] = '发送成功，领取红包';
		}
		$db_data['return_msg'] = serialize($aMsg);
		
		return $db_data;
	}
	

	
	function createNoncestr( $length = 32 ) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	function getClientIP() {
		$onlineip = '';
		if (getenv ( 'HTTP_CLIENT_IP' ) && strcasecmp ( getenv ( 'HTTP_CLIENT_IP' ), 'unknown' )) {
			$onlineip = getenv ( 'HTTP_CLIENT_IP' );
		} elseif (getenv ( 'HTTP_X_FORWARDED_FOR' ) && strcasecmp ( getenv ( 'HTTP_X_FORWARDED_FOR' ), 'unknown' )) {
			$onlineip = getenv ( 'HTTP_X_FORWARDED_FOR' );
		} elseif (getenv ( 'REMOTE_ADDR' ) && strcasecmp ( getenv ( 'REMOTE_ADDR' ), 'unknown' )) {
			$onlineip = getenv ( 'REMOTE_ADDR' );
		} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown' )) {
			$onlineip = $_SERVER ['REMOTE_ADDR'];
		}
		return $onlineip;
	}
	
	function arrayToXml($arr = null){
		if(!is_array($arr) || empty($arr)){
			die("参数不为数组无法解析");
		}
		$xml = "<xml>";
		foreach ($arr as $key=>$val){
			if (is_numeric($val)){
				$xml.="<".$key.">".$val."</".$key.">"; 
			}else{
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
			}
		}
		$xml.="</xml>";
		return $xml; 
	}
	
	function http_request($url, $fields, $params, $method='get', $second=30){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_TIMEOUT, $second);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);

		if (isset($params)) {
			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			curl_setopt($ch,CURLOPT_SSLCERT, $params['SSLCERT']);
			curl_setopt($ch,CURLOPT_SSLKEYTYPE, 'PEM');
			curl_setopt($ch,CURLOPT_SSLKEY, $params['SSLKEY']);
			curl_setopt($ch, CURLOPT_CAINFO, 'PEM');
			curl_setopt($ch,CURLOPT_CAINFO, $params['CAINFO']);
		}
		if ($method=='post') {
			curl_setopt($ch,CURLOPT_POST, true);
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);
		}
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}