<?
$url =$_SERVER["SCRIPT_NAME"];
$filename= substr($url,strrpos($url,'/')+1,strlen($url)); 
$script_php = substr($filename,0,strrpos($filename,'.')); 
define('IN_QY',true);
define('SCRIPT',$script_php);
session_start();
require('include/common.inc.php');
require("check.php");


?>
<link rel="stylesheet" href="css/pintuer.css">

<script src="js/jquery.js"></script>
<script src="js/pintuer.js"></script>
<script src="js/respond.js"></script>
<script src="js/admin.js"></script>
<script src="js/common.js"></script>
<script src="js/jquery.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/form/form.js"></script>
<script type="text/javascript" charset="UTF-8" src="js/artdialog/artDialog.js?skin=brief"></script>
<script type="text/javascript" charset="UTF-8" src="js/artdialog/plugins/iframeTools.js"></script>
<script src="js/My97DatePicker/WdatePicker.js"></script>
<link type="image/x-icon" href="/favicon.ico" rel="shortcut icon" />
<link href="/favicon.ico" rel="bookmark icon" />

<?
if($_GET['act']=="mod"){
	if($_POST['passwd']!=$_POST['repasswd']){
		qy_alert_back('两次密码不一致，请重新填写!');
	}
	$bl='lyk';
	$password=md5(md5($_POST['passwd'].$bl));
	$sql="update admin set password='".$password."' where id=".$_SESSION['admin_id']."";
	mysql_query($sql);
	if(mysql_affected_rows() == 1){
		qy_close();
		echo "<script type='text/javascript'>alert('修改成功!');art.dialog.close();</script>";
		exit;
	}else{
		qy_close();
		qy_alert_back('信息修改失败!');
	}
}
?>
<div class="admin">
	<form method="post" action="?act=mod">
    <div class="panel admin-panel">
    	<div class="panel-head"><strong></strong></div>
        <div class="padding border-bottom" align="center">
           输入密码：<input type="password" name="passwd" ><br><br>
		   确认密码：<input type="password" name="repasswd" ><br><br>
			<input type="submit" class="button button-small border-yellow" value="确定" onclick="add_goods()"/>
        </div>
         
    </div>
    </form>
    <br />
</div>
</body>
</html>