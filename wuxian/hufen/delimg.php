<?php
define('IN_QY',true);
require("include/common.inc.php");
//删除今天的微信群信息
/*七天前的信息*/
$old_date = date('Y-m-d H:i:s', strtotime('-7 days'));

$query = mysql_query("select * FROM weixinqun where cdate < '".$old_date."'") or die(mysql_error());
$ids = $url = array(); 
while($row=mysql_fetch_array($query)){
	$ids[] = $row['id']; 		    
    //图片的链接 删除图片
    $url[]['codeimg'] = $row['codeimg'];
    $url[]['photoimg'] = $row['photoimg'];
}

$wherein = implode(',',$ids);
if($wherein){
    $sql="delete from weixinqun where id in(".$wherein.")";
    //mysql_query($sql);
    //@pq 删除图片
    
    if(mysql_query($sql)){
        foreach ($url as $val){
            @unlink($val['codeimg']);
            @unlink($val['photoimg']);
        }
    }
}
mysql_close();

echo "<script type='text/javascript'>alert('成功删除!');location.href='orderlist2.php?page=".$_GET["page"]."';</script>";
exit;

?>