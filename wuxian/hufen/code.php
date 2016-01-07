<?php

/**
 * @author webfly
 * @copyright 2009
 * 图片验证码函数
 */

//生成4位随机字符
for ($i = 0; $i < 4; $i++)
{
    $num .= dechex(rand(1, 15)); //dechex将10进制转为16进制
}

//记录session供验证使用
session_start();
$_SESSION['vcode'] = $num;

$img = imagecreatetruecolor(60, 20); //创建一个黑色背景的图片
$bgcolor = imagecolorallocate($img, 180, 221, 247); //创建图片背景颜色
imagefill($img,0,0,$bgcolor);

//添加随机线条
for ($i = 0; $i < rand(0, 5); $i++)
{
    $linecolor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
    imageline($img, rand(0, 60), rand(0, 20), rand(0, 60), rand(0, 20), $linecolor);
}

//添加随机噪点
for ($i = 0; $i < rand(0, 200); $i++)
{
    $pixelcolor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
    imagesetpixel($img, rand(0, 60), rand(0, 20), $pixelcolor);
}

$color = imagecolorallocate($img, 125, 12, 56); //创建一个字体前景颜色
imagestring($img, 6, 10, 3, $num, $color); //向图片写入文字
header('content-type:image/png');
imagepng($img);

?>