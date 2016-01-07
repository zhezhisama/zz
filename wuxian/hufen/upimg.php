<?php
define('IN_QY',true);
require("include/common.inc.php");
session_start();
header('Content-Type:application/json; charset=utf-8');
if(empty($_GET[a])) die(json_encode(array('err' => "no action")));
if(!$_SESSION['uid']) die(json_encode(array('err' => "非法登录")));
$a = $_GET['a'];

	//$info['err'] = "上传失败请重试！";
	//$info['pic'] = "upload/1432038502b.jpg";
	//$info['key'] = "111";	
	//$info['upid'] = "111";

if(is_uploaded_file($_FILES[$a]['tmp_name'])){ 
	$upfile=$_FILES[$a]; 
	//获取数组里面的值 
	//$img=time().$upfile["name"];//上传文件的文件名
	$string = strrev($_FILES[$a]['name']);
	$array = explode('.',$string);
	$type=$upfile["type"];//上传文件的类型 
	$size=$upfile["size"];//上传文件的大小 
	$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径 
	$img = 'upload/'.time().'a.'.strrev($array[0]);
	//判断是否为图片 

	switch ($type){ 
		case 'image/pjpeg':$okType=true; 
		break; 
		case 'image/jpeg':$okType=true; 
		break; 
		case 'image/gif':$okType=true; 
		break; 
		case 'image/png':$okType=true; 
		break; 
	} 

	if($okType){ 
		$error=$upfile["error"];//上传后系统返回的值 
		//把上传的临时文件移动到up目录下面 
		move_uploaded_file($tmp_name,$img); 
		if($a == "himg"){
			mkThumbnail($img, 80, 80,$img);
		} else{
			mkThumbnail($img, 320, null,$img);
		}
		die(json_encode(array('pic' => $img)));
	}else{ 
		//qy_alert_back('请上传jpg,gif,png等格式的图片！');
		die(json_encode(array('err' => "请上传jpg,gif,png等格式的图片！")));
	} 
}else{
	die(json_encode(array('err' => "上传失败！")));
}

/*
 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp）
 * @author ruxing.li
 * @param  string $src      源图片路径
 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放）
 * @param  string $filename 保存路径（不指定时直接输出到浏览器）
 * @return bool
 */
function mkThumbnail($src, $width = null, $height = null, $filename = null) {
    if (!isset($width) && !isset($height))
        return false;
    if (isset($width) && $width <= 0)
        return false;
    if (isset($height) && $height <= 0)
        return false;

    $size = getimagesize($src);
    if (!$size)
        return false;

    list($src_w, $src_h, $src_type) = $size;
    $src_mime = $size['mime'];
    switch($src_type) {
        case 1 :
            $img_type = 'gif';
            break;
        case 2 :
            $img_type = 'jpeg';
            break;
        case 3 :
            $img_type = 'png';
            break;
        case 15 :
            $img_type = 'wbmp';
            break;
        default :
            return false;
    }

    if (!isset($width))
        $width = $src_w * ($height / $src_h);
    if (!isset($height))
        $height = $src_h * ($width / $src_w);

    $imagecreatefunc = 'imagecreatefrom' . $img_type;
    $src_img = $imagecreatefunc($src);
    $dest_img = imagecreatetruecolor($width, $height);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

    $imagefunc = 'image' . $img_type;
    if ($filename) {
        $imagefunc($dest_img, $filename);
    } else {
        header('Content-Type: ' . $src_mime);
        $imagefunc($dest_img);
    }
    imagedestroy($src_img);
    imagedestroy($dest_img);
    return true;
}
