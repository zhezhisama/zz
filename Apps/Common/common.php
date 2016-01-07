<?php
/**
 * 生成流水号
 */
function create_sn(){
	mt_srand((double )microtime() * 1000000 );
	return date("YmdHis" ).str_pad( mt_rand( 1, 99999 ), 5, "0", STR_PAD_LEFT );
}
//tag标签转换
function changeA($tag,$id) {
    if(!empty($tag)){
        $tags= preg_split('/\s+/', $tag);
        if(!empty($id)){
            foreach ($tags as $value) {
                $a.="<a href=".U('Weixin/index',array('id'=>$id,'tag'=>urlencode($value)))." target=\"_blank\">".$value."</a>&nbsp;";
            }
        }else{
            foreach ($tags as $value) {
                $a.="<a href=".U('Weixin/index',array('tag'=>urlencode($value)))." target=\"_blank\">".$value."</a>&nbsp;";
            }
        }
        return $a;
    }
}
//删除目录
function delete_dir($dir)
{
    if ($dh = opendir($dir))
    {
        while (($file = readdir($dh)) != false)
        {
            // 当遇到当前目录.与父目录..的时候不执行删除操作,继续循环。
            if (($file == '.') || ($file == '..'))
            {
                continue;
            }
            // 当前文件或目录的绝对路径。如果不是路径的话，会把当前目录的对应的文件或目录删掉。
            $dir_temp = $dir . '/' . $file;
            if (is_dir($dir_temp))
            {
                // 如果是目录，继续递归调用，进入此目录里面把目录里面的文件或目录删掉。
                delete_dir($dir_temp);
            }
            else
            {
                // 如果是文件则把文件删掉。
                unlink($dir_temp);
            }
        }
        // 关闭目录。
        closedir($dh);
        // 此时传入的目录的文件和子目录都已经被清空，这里可以把目录删掉。
        rmdir($dir);
    }
}
//创建订单
function createOrderId(){
    $time=date('YmdHis',time());
    $radnum=mt_rand(100,999);
    return $time.$radnum;
}
function IP($ip = '', $file = 'UTFWry.dat') {
	$_ip = array ();
	if (isset ( $_ip [$ip] )) {
		return $_ip [$ip];
	} else {
		import ( "ORG.Net.IpLocation" );
		$iplocation = new IpLocation ( $file );
		$location = $iplocation->getlocation ( $ip );
		$_ip [$ip] = $location ['country'] . $location ['area'];
	}
	return $_ip [$ip];
}
//将数组转化为查询字符串   
 function getChildId($data,$pid){  
        foreach($data as $k => $v){  
            if($v['pid'] == $pid){  
                $id.=getChildId($data,$v['id']);  
                $id.=$v['id'].',';                
            }  
        }  
        return $id;
 }

 //将数组转化为树形数组   
 function getChildIdArray($data,$pid){ 
        foreach($data as $k => $v){  
            if($v['pid'] == $pid){  
                $id[$v['id']]['catname']=$v['catname'];                 
            }  
        }  

        return $id;
 }  
 
 //将数组转化为树形数组   
 function arrToTree($data,$pid){  
    $tree = array();  
    foreach($data as $k => $v){  
        if($v['pid'] == $pid){  
            $v['pid'] = arrToTree($data,$v['id']);  
            $tree[] = $v;  
        }  
    }     

    return $tree;  
 } 
//使用递归将多维数组转化为一维数组
 function arrToMenu($data,$pid){  
    static $menu = array();  
    foreach($data as $k => $v){  
        if($v['pid'] == $pid){  
            $menu[] = $v; 
            $v['pid'] = arrToMenu($data,$v['id']);  
            
        }  
    }
    return $menu;  
 }
 //获取会员账户余额
 function getMemberAmount($memberid,$membername) {
    if(!isset($memberid)||!isset($membername)){
        return 'error';
    }
    $Member=D('Member');
    $map['id']=array('eq',$memberid);
    $map['account']=array('eq',$membername);
    $amount=$Member->where($map)->getField('amount');
    return $amount;
}
function getPidMembername($pid){
    if($pid==0){
        $name = '';
    }else{
        $Mall = D ( "Comment" );
        $map['id']=array('eq',$pid);
        $list = $Mall->where($map)->find();
        $name = $list['membername'];
    }
    return $name;
}
//根据会员ID获取会员头像
function getMemberLogo($id) {
    if($id==0){
        $name = '';
    }else{
        $Mall = D ( "Member" );
        $map['id']=array('eq',$id);
        $list = $Mall->where($map)->find();
        $name = $list['thumb'];
    }
    return $name;
}
//根据会员ID获取会员头像
function getMemberULogo($u) {
    
    if(empty($u)){
        $name = '';
    }else{
        $Mall = D ( "Member" );
        $map['account']=array('eq',$u);
        $list = $Mall->where($map)->find();
        
        $name = $list['thumb'];
    }
    return $name;
}
 //获取推荐方式名
 function getRecommendName($id){
    if(!isset($id)){
        return 'error';
    }
    $Recommend = D ( "Recommend" );
    $list = $Recommend->getField ( 'id,recommendname' );
    $name = $list [$id];
    return $name;
 }
 
 //获取消费积分基数
function getIntergral($id){
    
    if(!isset($id)){
        return 'error';
    }
    $Recommend = D ( "Recommend" );
    $list = $Recommend->getField ( 'id,intergral' );
    $name = $list [$id];
    return $name;
}
 //根据ID获得城市名
function getAreasName($id){
    
    if(!isset( $id )){
        return '无选择';
    }
    $Areas = D ( "Areas" );
    $list = $Areas->getField ( 'id,area_name' );
    $name = $list [$id];
    return $name;
}
//根据ID获得分类名
function getCategoryName($id){
    if (empty ( $id )) {
            return '无选择';
    }
    $Category = D ( "Category" );
    $list = $Category->getField ( 'id,catname' );
    $name = $list [$id];
    return $name;
}
//根据ID获得模型名
function getModuleById($id){
    $Category = D ( "Category" );
    $list = $Category->getField ( 'id,modelname' );
    $module = $list [$id];
    return $module;
}
//获取会员名称
function getMemberName($id){
    if($id==0){
        $name = '游客';
    }else{
        $Member = D ( "Member" );
        $list = $Member->getField ( 'id,name' );
        $name = $list [$id];
    }
    return $name;
}
//会员组名
function getGroupName($id) {
    
    if(!isset( $id )){
        return '无选择';
    }
    $Membergroup = D("Membergroup");
    $list = $Membergroup->getField('id,name');
    $name = $list [$id];
    return $name;
    
}

 //根据ID获取微信公号
function getWeixinName($id){
    
    if(!isset( $id )){
        return '';
    }
    $Weixin = D ( "Weixin" );
    $list = $Weixin->getField ( 'id,pubaccount' );
    $name = $list [$id];
    return $name;
}
//评论时间
function commentDate($time) {
    if (empty ( $time )) {
        return '';
    }
    $now=  time();
    if(date('Y',$now)!=date('Y',$time)){
        return date ('Y年m月d日', $time );
    }elseif(date('m',$now)!=date('m',$time)){
        return date ('m月d日', $time );
    }elseif(date('d',$now)!=date('d',$time)){
        return date ('m月d日', $time );
    }elseif(date('H',$now)!=date('H',$time)){
        return ceil(($now-$time)/(3600)).'小时前';//date ('H小时', $time );
    }elseif(date('i',$now)!=date('i',$time)){
        return ceil(($now-$time)/(60)).'分钟前';//date ('i分种', $time);
    }else{
        return '刚刚发布';//intval($now-$time).'秒钟前刚刚发布';//date ('s秒', $time);
    }
    
}
//公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}
//MD5
function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}

//字符串截取，支持中文和其他编码
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
	if(mb_strlen($str)>$length)
	return $slice.'...';
	else
    return $slice;
}
