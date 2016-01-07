<?include('top.php');?>
<?
//判断排序字段是否存在，不存在新增
$rs = mysql_query("DESCRIBE weixin `listorder`");
if(!mysql_num_rows($rs)) {
	$sql="alter table weixin add listorder int(10)  default 0";
$result=mysql_query($sql);
}

if($_GET["act"]=="del")
{
	 $sql="delete from weixin where id=".$_GET['id'];
	 mysql_query($sql);
	 mysql_close();
	 echo "<script type='text/javascript'>alert('成功删除!');location.href='orderlist.php?page=".$_GET["page"]."';</script>";
	 exit;
}
if($_GET["act"]=="delall")
{	
	for($i=0;$i<count($_POST['ids']);$i++){
		$sql="delete from weixin where id=".$_POST['ids'][$i];
		mysql_query($sql);
	}
	echo "<script type='text/javascript'>alert('成功删除!');location.href='orderlist.php?page=".$_GET["page"]."';</script>";
	exit;
}
if($_GET["act"]=="listorder")
{	

	foreach ($_POST['listorder'] as $i => $v) {
		$sql="update weixin set listorder=$v where id=$i";
		mysql_query($sql);
	}
	
	echo "<script type='text/javascript'>alert('成功排序!');location.href='orderlist.php?page=".$_GET["page"]."';</script>";
	exit;
}
if($_GET["act"]=="search")
{	
	if($_POST['keywords']){
		$where = $where ." and (name like '%".$_POST['search']."%' or miaoshu like '%".$_POST['search']."%' or addr like '%".$_POST['search']."%' )";
	}
}else{
	$where ='';
}
if($_GET['act'] == "refresh"){
    $id = $_GET['id'];
    $top = $_GET['top'];
    $sql = "select * FROM `weixin` WHERE `id`='".$id."'";
    $query = mysql_query($sql);
    $weixin = mysql_fetch_array($query);
    
    $now = time();
    $count_sql = mysql_query("SELECT count(id) as num FROM `weixin` where `top` = 3 and `top_time` >= $now ");
    $count = mysql_fetch_array($count_sql);
    
    //查看是否有足够的积分 置顶一周 top 2 3
   if($top == 2){
        $top_time = $now + 3600*24*7;
    }elseif($top == 3){
        $top_time = $now + 3600*24;
    }
    
    if($now < $weixin['top_time']){
        exit("<script>alert('现在该账户还存在置顶状态，请等待置顶时间结束。');location.href='orderlist.php?page=".$_GET["page"]."'</script>");
    }
 
    if($count['num'] >= 3 && $top == 3){
        exit("<script>alert('对不起，现在没有土豪置顶名额');location.href='orderlist.php?page=".$_GET["page"]."'</script>");
    }
    
    $sql="UPDATE weixin SET top='".$top."',top_time='".$top_time."'WHERE id='".$id."'";
    mysql_query($sql);
    if(mysql_affected_rows() == 1){
        echo "<script type='text/javascript'>alert('置顶成功!');location.href='orderlist.php?page=".$_GET["page"]."';</script>";
        exit;
    }else{
        qy_close();
        qy_alert_back('置顶失败!');
    }
   
}
?>
<link type="text/css" href="css/phone.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
	function isWeiXin(){ 
		var ua = window.navigator.userAgent.toLowerCase(); 
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){ 
			return true; 
		}else{ 
			return false; 
		} 
	}
	function go(){
		$("#form1").submit();
	}
	function preview(num){
		$("#pageNo").val(num);
		$("#form1").submit();
	}
	function next(num){
		$("#pageNo").val(num);
		$("#form1").submit();
	}

	if(!isWeiXin()){
		//	window.location = "http://121.40.150.127";
	}
	$(function () {
		$(".login_btn").click(function () {
			$("#desc").html($(this).attr("data-content"));
			$("#img").attr("src",$(this).attr("data-img"));
			$(".modal").show();
		});
		$(".modal").on("click",function(){
			$(this).hide();
		});
	});
</script>
<div > 
	<form method="post" action="orderlist.php" id="forms" name="forms">
    <div class="panel admin-panel">
    	<div class="panel-head"><strong>微信列表</strong>
		<span class="float-right">
			<a class="button button-little bg-main" href="#" onclick="repass()">修改密码</a>
			<a class="button button-little bg-yellow" href="check.php?action=loginout">注销登录</a>
		</span>
		</div>
        <div class="padding border-bottom">
            <input type="button" class="button button-small checkall" name="checkall" checkfor="ids[]" value="全选" />
			<input type="submit" class="button button-small border-red" value="批量删除" onclick="{if(confirm('确认删除?')){document.forms.action='orderlist.php?act=delall';document.getElementById('forms').submit();}return false;}"  />
			<input type="submit" class="button button-small border-red" value="更改排序" onclick="{if(confirm('确认排序?')){document.forms.action='orderlist.php?act=listorder';document.getElementById('forms').submit();}return false;}"  />
			<a class="button button-small bg-red" href="addfen.php">添交友丝</a>	<a class="button button-small bg-red" href="orderlist2.php">群管理</a>
			
			<div class="form-button" style="float:right;margin-right:30px"><button class="button bg-main" type="button" onclick="document.forms.action='orderlist.php?act=search';document.getElementById('forms').submit();">查询</button></div>
			<input type="text" class="input" id="keywords" name="keywords" size="50" placeholder="请输入要查询的联系人电话或运单号" value="" data-validate="required:请输入要检索的关键字" style="width:285px;float:right"/>
        </div>
         <table class="table table-hover">
        	<tr>
			<th width="55">选择</th>
			<th >排序</th>
			<th width="100">来源</th>
			<th >头像</th>
			<th >名称</th>
			<th >自我介绍</th>
			<th >操作</th>
			</tr>
			<?php
				$page_sql = "select * from weixin where 1=1 ".$where." ";		
				qy_page($page_sql,20);
				$sql="SELECT *  FROM  weixin where 1=1 ".$where." ORDER by listorder desc ,id DESC LIMIT $_pagenum,$_pagesize";
				$_id ='&';
				$query=mysql_query($sql);
				while($row=mysql_fetch_array($query)){
				?>
					<tr>
					<td style="height:50px;line-height:50px"><input type="checkbox" name="ids[]" value="<?=$row['id']?>" /></td>
					<td style="height:50px;line-height:50px"><input type="text" value="<?=$row['listorder']?>" name="listorder[<?=$row['id']?>]" size="5"></td>
					<td style="height:50px;line-height:50px" ><?php if(empty($row['uid'])){ ?>后台上传<?php }else{ ?>用户上传<?php }?></td>
					<td><img src="<?=$row['photoimg']?>" width="50"  height="50"/></td>
					<td style="height:50px;line-height:50px"><?=$row['name']?></td>
					<td style="height:50px;line-height:50px"><?=$row['miaoshu']?></td>
					<td style="height:50px;line-height:50px;width:500px;"><a class="button bg-red" href="?id=<?php echo $row['id']?>&act=refresh&top=3&page=<?=$_GET["page"]?>" >土豪置顶</a><a class="button bg-red" href="?id=<?php echo $row['id']?>&act=refresh&top=2&page=<?=$_GET["page"]?>" >普通置顶</a><input type="button" data-img="<?=$row['codeimg']?>" data-content="" value="查看二维码" class="login_btn" style="width:80px;line-height:24px; height:24px;border-radius:4px">   <a class="button button-little bg-red" href="?id=<?php echo $row['id']?>&act=del&page=<?=$_GET["page"]?>" onclick="{if(confirm('确认删除?')){return true;}return false;}">删除</a></td></tr>
				<?
				}
				?>
        </table>
		<?php qy_paging(); ?>
    </div>
    </form>
    <br />
</div>
<div class="modal">
	<div style="margin:20px auto;">
		<div id="desc" style="margin:0 auto;color:#fff;text-align:center;"></div>
		<div style="margin:10px auto;">
		<img id="img" src="" style="display: block;margin:0px auto;width:75%;height:75%;">
		</div>
	</div>
</div>
</body>
</html>