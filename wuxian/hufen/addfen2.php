<?php
include('top.php');
session_start();

if(!$_SESSION['uid'])	exit("<script>alert('非法用户，禁止访问...');</script>");
if($_POST["act"]=="save"){
   $wxtitle=clearemoj($_POST["wxtitle"]);
	$sql="INSERT INTO weixinqun (`name`,sex,photoimg,codeimg,addr,prov,city,miaoshu,cdate,shuaxin2)" .
			"VALUES(
				'{$wxtitle}',
				'{$_POST["sex"]}',
				'{$_POST["wximg"]}',
				'{$_POST["qrcode"]}',
				'".$_POST["addr"]."',
				'{$_POST["province"]}',
				'{$_POST["city"]}',
				'{$_POST["content"]}',
				'".date('Y-m-d H:i:s')."',
				'3'
				)";
			//echo $sql;exit;
		mysql_query($sql);
		if(mysql_affected_rows() == 1){
			qy_close();
			echo "<script type='text/javascript'>alert('添加成功!');location.href='orderlist2.php';</script>";
			exit;
		}else{
			qy_close();
			qy_alert_back('信息添加失败!');
		}
}

		
	
//表单部分
?>
	<script type="text/javascript" src="js/jquery.cityselect.js?0709"></script>
<script type="text/javascript">
var myprovince='' ; 
var mycity='' ;


$(function(){ 

	$("#set_city").citySelect({ 
		prov:myprovince,  
		city:mycity 
	});
	
}); 
</script>
<script type="text/javascript" src="js/qcadd.js?0711"></script>
<script type="text/javascript" src="js/jquery.form.js?0709"></script>

    <div class="panel admin-panel">
    	<div class="panel-head"><strong>新增群</strong>
	
		</div>
        <div class="padding border-bottom">
<form method="post" action="addfen2.php" class="form-x form-auto" id="forms" name="forms">
  <div class="form-group">
    <div class="label"><label >昵称：</label></div>
    <div class="field">
      <input type="text" class="input"  name="wxtitle" size="30"  />
    </div>
  </div>
  <div class="form-group">
    <div class="label"><label >微信号：</label></div>
    <div class="field">
      <input type="text" class="input"  name="wxid" size="30"  />
    </div>
  </div>
    <div class="form-group">
    <div class="label"><label >头像：</label></div>
    <div class="field">
					<input id="fileupload1" type="file" name="himg">
	<div id="showimg1"></div>
  </div>
  
</div>
    <div class="form-group">
    <div class="label"><label >微信群二维码：</label></div>
    <div class="field">
					<input id="fileupload" type="file" name="qcimg">
	<div id="showimg"></div>
  </div>
  
</div>
<input type="hidden" name="wximg" value="" />
<input type="hidden" name="qrcode" value="" />
	<input type="hidden" name="wximg_upid" value="" />
<input type="hidden" name="qrcode_upid" value="" />
    <div class="form-group">
    <div class="label"><label >性别：</label></div>
    <div class="field">
       <select class="input" name="sex">
    <option value="1" selected>男</option>
							<option value="2">女</option>
							<option value="0">保密</option>
  </select>
    </div>
  </div>

     <div class="form-group">
    <div class="label"><label >所在城市：</label></div>
    <div class="field" id="set_city">
      <select class="prov" name="province" ></select>
	<select class="city" name="city" ></select>
    </div>
  </div>
      <div class="form-group">
    <div class="label"><label >描述：</label></div>
    <div class="field" >
     <textarea rows="5" cols="50" class="input" name="content"></textarea>
    </div>
  </div>
<input type="hidden" name="act" value="save" />
  <div class="form-button"><button class="button bg-blue" type="submit">保存</button>&nbsp;&nbsp;&nbsp;&nbsp;<a href="orderlist2.php" class="button bg-red"><b>返回</b></a></div>
</form>
        </div>
        
	
    </div>
 </body>
</html>
