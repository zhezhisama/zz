<?php
define('IN_QY',true);
require("include/common.inc.php");
//删除今天的微信群信息
/*七天前的信息*/
$old_date = date('Y-m-d H:i:s', strtotime('-2 days'));

$query = mysql_query("select * FROM weixinqun where cdate < '".$old_date."'") or die(mysql_error());
$ids = array(); 
while($row=mysql_fetch_array($query)){
	$ids[] = $row['id']; 		    
    //图片的链接 删除图片
}
$wherein = implode(',',$ids);
/* $sql="delete from weixinqun where id in(".$wherein.")";
mysql_query($sql); */

echo $wherein;
exit;	
/* $sql="delete from weixinqun where ".$where;
mysql_query($sql);
mysql_close(); */

?>