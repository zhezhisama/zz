<?php
define('IN_QY',true);
define('SCRIPT','list');
require("include/common.inc.php");
$show_num = 10;
$first_page_num = 15;
$utime = empty($_GET['utime']) ? time() : $_GET['utime'];
$page = empty($_GET['page']) ? 2 : $_GET['page'];
$limit = ($page -2) * $show_num  + $first_page_num;
$where = '';
	//$where = $where ." and (name like '%".$_POST['search']."%' or miaoshu like '%".$_POST['search']."%' or addr like '%".$_POST['search']."%' )";
$where_arr = array();
if(!empty($_GET['search']))
	$where_arr[] = "(name like '%".$_GET['search']."%' or miaoshu like '%".$_GET['search']."%')";
if(!empty($_GET['province']))
	$where_arr[] = "prov='{$_GET['province']}'";
if(!empty($_GET['city']))
	$where_arr[] = "city='{$_GET['city']}'";
if(!empty($_GET[sex])){
	$where_arr[] = "sex='{$_GET[sex]}'";
}
$where = empty($where_arr) ? "" : "and "  .  implode(" and " , $where_arr);

$sj1 = substr($utime ,-4);
$sj2 = substr($utime ,-3);
$sql="SELECT *,mod(id*{$sj1} , {$sj2}) as sjpx  FROM weixin where shuaxin=0 $where ORDER by sjpx DESC LIMIT {$limit},{$show_num}";
$query=mysql_query($sql);
$flist = array();
while($row=mysql_fetch_array($query)){
	$flist[] = array(
		"headimg" => $row['photoimg'],
		"id" => $row['id'],
		"city" => $row['prov'] . $row['city'],
		"username" => $row['name'] ,
		"qrcode" => $row['codeimg'],
		"remark" => $row['miaoshu']);
}
if(empty($flist)){
	$r['sta'] = "null";
}else{
	$r['sta'] = "ok";
	$r[flist] = $flist;
}
echo json_encode($r);
?>
