<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta property="qc:admins" content="71602102676750206375" />
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta name="360-site-verification" content="ee3bc96118c1aedf029ce7996a4c2d43" />
<title><?php echo ($title); ?> - <?php echo ($sitename); ?></title>
<meta name="keywords" content="<?php echo ($keywords); ?>"><meta name="description" content="<?php echo ($description); ?>">
<link href="__PUBLIC__/css/base.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/css/nav.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/css/columns.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/css/new_header.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/banner0.css" />

<script charset="utf-8" src="__PUBLIC__/js/v.js"></script><script src="__PUBLIC__/js/jquery.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/js/jquery.easing.1.3.js" type="text/javascript"></script>
<script type="text/javascript" src="../Public/js/jquery.form.js"></script>
<script type="text/javascript" src="../Public/js/formValidator-4.0.1.min.js"></script>
<script type="text/javascript" src="../Public/js/formValidatorRegex.js"></script>
<script type="text/javascript" src="../Public/js/qqk.js"></script>
<script src="../Public/js/jquery.plugin.min.js" type="text/javascript"></script>
<link href="__PUBLIC__/css/datouwang.css" rel="stylesheet" type="text/css"/>


<script type="text/javascript">var mobileAgent = new Array("iphone", "ipod", "ipad", "android", "mobile", "blackberry", "webos", "incognito", "webmate", "bada", "nokia", "lg", "ucweb", "skyfire");
var browser = navigator.userAgent.toLowerCase(); 
var isMobile = false; 
for (var i=0; i<mobileAgent.length; i++){ if (browser.indexOf(mobileAgent[i])!=-1){ isMobile = true; 
//alert(mobileAgent[i]); 
location.href = '/m/';
break; } } 
</script>

<style>
.
</style>

</head>
<body>

<div class="header">
	<div class="utility">
        <div class="utility-container w1000"><a class="title1">微商人的第一个网站</a>
        </div>
    </div>
    
    
    <div class="operate">
        <div class="operate-container w1000">
            <div class="logo" style="margin-top: 28px;">
                <a href="/"><img  alt="微信群" class="lazy" src="__ROOT__/Uploads<?php $set=$_result=M('Set')->getField('logo',1); echo $set;?>" style="display: block;"></a>
            </div>
            
            <div class="search">
                <form action="<?php echo U('Weixin/search',array('id'=>126));?>" name="topsearch" method="get" target="_blank">
                    <div class="search_div">
                    <span class="input-icon0"></span>
                    <input type="text" class="search00" name="search" placeholder="请输入微信群关键字、名称">
                    </div>
                    
                    <input type="submit" value="搜索" class="input-btn00">
                 </form>
                
               <!-- <form id="Search_form" class="head_search_box" target="_blank" name="Search_form" method="get" action="http://zhannei.baidu.com/cse/search">
                <div class="input-group"><span class="glyphicon glyphicon-search"></span>
                <input type="text" placeholder="请输入关键字" class="search00" id="bdcsMain" name="q"><span class="input-group-btn">
                <button type="submit" class="btn btn-default">搜索</button></span>-->
            </div>
            
            
            <div id="bdcs"><div class="bdcs-container"><meta http-equiv="x-ua-compatible" content="IE=9">                          </div></div>
            <div><input type="hidden" name="s" value="15665828165835218519"><input type="hidden" name="entry" value="1"></div><div></div></form>
            <div class="search-list" style="display:none;">
            <a class="search-item " href="#" target="_blank">你瘦了梅</a>
            <a class="search-item seagreen" href="#" target="_blank">俏十岁</a>
             
            </div>
            </div>
            <div class="trait">
            	<div class="right">
		            <?php if(session('id') < 1 ){ ?>
		                <a href="<?php echo U('Member/login');?>" class="item seagreen btn_left1" style="border-radius:6px 6px 6px 6px; margin-left:50px;" rel="nofollow">登录</a>
		                
		             <?php }else{ ?>
						<a href="<?php echo U('Member/information_info');?>" class="item seagreen btn_left2"  target="_blank" hidefocus="true">个人中心</a>
						<a href="<?php echo U('Member/logout');?>"  class="item seagreen btn_right2" hidefocus="true">退出</a>
		             <?php } ?>
	            </div>
            </div>
        </div>
    </div>
</div>



<?php $other=$_result=M('Other')->where('status=1 and settag="bdshare"')->find(); echo $other['setvalue'];?>
<!--定义变量-->
<?php $nopicpath = '../Public/theme/nopic.gif'; ?>
<!-- 头部 START -->
<div class="wx-nav">
  <div class="max-width" style="max-width:1000px; position: relative;">
    <ul class="nav-pills" style="">
      <li class="dropdown"><a class="active" id="nav_post" href="/">首页</a></li>
      <li id="navmenu_personal" class="dropdown"> <a href="<?php echo U('weixin/index',array('id'=>44));?>" >微信大全 <span class="fs10">▼</span></a>
        <ul class="dropdown-menu">
          <?php if(is_array($zcategorylist)): $k = 0; $__LIST__ = $zcategorylist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$category): $mod = ($k % 2 );++$k;?><li>
              <a href="<?php echo U('Weixin/index',array('id'=>$category['id']));?>" title="<?php echo ($category['catname']); ?>" hidefocus="true"><?php echo ($category['catname']); ?></a> 
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </li>
      <li class="dropdown"><a id="nav_event" href="<?php echo U('weixin/huoyuan',array('id'=>1));?>">微商货源</a></li>
      <li class="dropdown" style=" display:none;"><a id="nav_event" href="<?php echo U('weixin/order',array('id'=>89));?>">排行榜</a></li>
	  <li class="dropdown"><a id="nav_event" href="<?php echo U('Article/index',array('id'=>142));?>">微信营销</a></li>
      <!--<li class="dropdown"><a id="nav_event" href="<?php echo U('Article/index',array('id'=>158));?>">招商加盟</a></li>-->
	  <!--<li class="dropdown"><a id="nav_event" href="http://weixinqun.xiaoxiaowu.info/" target="_blank">公众号</a></li>-->
      

    </ul>
    
    <!-- 导航右侧 begin -->
    <div class="right" style="width: 144px; height: 40px;">
      <ul id="logout_title">
      	<li style="margin-left: 20px; top:0px; height:40px; line-height:40px;" class="publish_ewm"><a class="item" href="/member-add.html"><b>发布二维码</b></a></li>
      </ul>
    </div>
    <!-- 导航右侧 end --> 
  </div>
</div>
<div class="header-search" style="display:none;">
	<div class="wrap w1000">
    	<div class="area">
        <form action="<?php echo U('Weixin/search',array('id'=>126));?>" name="topsearch" method="get" target="_blank">
        	<span class="input-icon"></span><input type="text" class="input" name="search" placeholder="请输入微信群关键字、名称">
            <input type="submit" value="" class="input-btn"> <input type="button" class="release-btn" onclick="<?php echo U('Member/add');?>">
         </form>
        </div>
    </div>
</div>
<!-- 头部 END -->

<script src="__PUBLIC__/js/jiemi1.js"></script>

<div id="fsD1" class="focus" style="position:relative;">
         <div id="D1pic1" class="fPic"> 
               <div class="fcon" style="display: block;"><a href="<?php echo U('/weixin/show/id/5212');?>" style=" display:block;width:100%; height:365px; background:url(../Public/images/banner1.jpg) no-repeat center center;  background-color:#200403;"><div style="width:100%; height:365px; background:#000"></div></a></div><!--这个是对图片的滤镜-->
               <div class="fcon" style="display: none;"><a href="<?php echo U('/weixin/show/id/5212');?>" style=" display:block;width:100%; height:365px; background:url(../Public/images/banner02.jpg) no-repeat center center; background-color:#fff;"><div style="width:100%; height:365px; background:#000"></div></a></div>
         </div>
         <div class="D1fBt" id="D1fBt">  
                  <a href="javascript:void(0)" hidefocus="true" target="_self" class="current"><i>1</i></a>
                  <a href="javascript:void(0)" hidefocus="true" target="_self"><i>2</i></a>
         </div>
		<span class="prev" style="display:none;"></span>
    	<span class="next" style="display:none;"></span>
</div>

<script type="text/javascript">

	Qfast.add('widgets', { path: "../Public/js/terminator_self3.js", type: "js", requires: ['fx'] });  
	Qfast(false, 'widgets', function (){
		K.tabs({
			id: 'fsD1', 
			conId: "D1pic1", 
			tabId:"D1fBt", 
			tabTn:"a",
			conCn: '.fcon',
			auto: 1,
			effect: 'fade', 
			eType: 'click',
			pageBt:true,
			bns: ['.prev', '.next'],                  
			interval: 3000 
		}) 
	}) 
	
</script>

<div style="clearfix:both;"></div>

<div class="popDoc" style="width:450px;height:350px;margin:-175px 0 0 -225px;">
  <div class="popDocTitle"><a href="javascript:void(0)" class="fRight" onclick="$(&#39;.popDoc&#39;).hide();"><img src="__PUBLIC__/images/dClose.png" width="16" height="16" alt="关闭"></a>登录微信群</div>
    <div class="loginPop">
      <div class="loginTable">
          <form id="loginform" name="login" method="post" action="<?php echo U('Member/checkLogin');?>" onsubmit="return validate_form(this)">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tbody><tr>
                    <th scope="row">用户名/邮箱:</th>
                    <td><input type="text" value="" class="newTxt w250" name="account" id="username"></td>
                    </tr>
                    <tr>
                    <th scope="row">&nbsp;</th>
                    <td><span>&nbsp;</span></td>
                    </tr>
                    <tr>
                    <th scope="row">密　码:</th>
                    <td><input type="password" value="" class="newTxt w250" name="password" id="password"></td>
                  </tr>
                    <tr>
                    <th scope="row">验证码:</th>
                    <td><input type="text" id="verify" name="verify" placeholder="验证码" class="yzm"/>
          
          <img id="verifyImg" src="<?php echo U('Member/verify');?>" onClick="fleshVerify()" border="0" alt="点击刷新验证码" style="cursor:pointer; margin-top:10px; width:95px;" align="absmiddle"></td>
                    </tr>
                    <tr>
                    <th scope="row">&nbsp;</th>
                    <td>
                    <div class="vm pt20"><input name="Tourl" id="Tourl" type="hidden" value=""><input name="action" type="hidden" value="login"><input value="登 录" type="submit" class="yellowBtn">
                    &nbsp;&nbsp;&nbsp;
                    <!-- <a href="http://weixinqun.xiaoxiaowu.info/user-ForgotPassword.html" target="_blank">忘记密码</a>  -->
                    <span class="caaa">|</span> 
                    <a href="<?php echo U('Member/login');?>" target="_blank">注册</a></div></td>
                    </tr>
                </tbody></table>
            </form>
        </div>
    </div>
</div>
<link href="__PUBLIC__/css/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
#kinMaxShow{visibility:hidden;width:700px; height:300px; overflow:hidden;}
.STYLE1 {color: #FF0000}
a:hover,a:active{color:#e6007f;text-decoration:none;}
.boxtwo li a{ position: relative; display: block;float:left; height: 100%; width:auto;}

#qian{
	width:320px;
	height:300px;
	position:absolute;
	margin:auto;
	right:450px;
	bottom:-110px;
	z-index:10000;
	background:#CCC;
}
</style>
<script src="../Public/js/jquery.kinMaxShow-1.1.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
$("#kinMaxShow").kinMaxShow({height:300,width:700});
  
$(".try").mouseover(function(){
	$("#qian").attr("style","display:block");
})

$(".try").mouseout(function(){
	$("#qian").attr("style","display:none");
})

});

</script>
<!-- 广告区域 -->

<div style='clear:both;'></div>
<!-- 品牌推荐 -->
<div class="wrap w1000  mt20 clearfix">


	<div class="gg_box">
        
    	<div class="gg_con border">
       		 <h4><span class="h_bg"><a style="font-size:15px;font-weight:normal;">品牌推荐</a></span><span class="h_btn"><a href="../article-show-id-100.html" target="_blank">我要上推荐</a></span></h4>
             <div class="ul_con">
        	
	            <ul class="boxtwo" style="display: block;">
	            	<?php if(is_array($tjlist)): $i = 0; $__LIST__ = array_slice($tjlist,0,8,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
	                		<div>
	       						<span class="caption">
		                 			<div class="con_img">
		                 					<div style="background-image: url(Uploads<?php echo ($vo['qrcode']?$vo['qrcode']:$vo['logo']); ?>); background-size:130px 130px;width:130px;height:130px;" title="<?php echo ($vo["title"]); ?>">
		                					</div>      
		                	   		</div>        
	                 			 </span>
	
	          				<p align="center"><a href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>" class=""  style="margin:0 auto; float:none;"   title="<?php echo ($vo["pubaccount"]); ?>"  title="<?php echo ($vo["title"]); ?>"><?php echo (msubstr($vo["title"],0,100)); ?></a></p>
	          			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	             </ul>
         
        	</div>
		</div>
      </div>
      <div id="qian" style="display:none;">
      	<center><h3 style="margin-bottom:10px; margin-top:20px;">千哥商城</h3></center>
        <center><img src="/Apps/Tpl/weixinqun/Public/images/b9682e8e83791bdd589f2cc072596932.png" width="200" height="200" /></center>
        <center><h3 style="margin-top:10px;">欢迎扫码进入千哥商城</h3></center>
      </div>
      <div style="width:316px; height:364px; float:left; margin-left: 24px;">
      	<a href="javascript:void(0)" style="display:block;width:316px;height:170px;"><img src="/Apps/Tpl/weixinqun/Public/images/try.jpg" class="try" /></a>
      	<a href="javascript:void(0)" style="display:block;width:316px;height:170px;margin-top: 24px;"><img src="/Apps/Tpl/weixinqun/Public/images/yzly.jpg" class="try" /></a>
      </div> 
            
    </div>
</div>

<!--  VIP END -->

<!-- 抢位 START -->
<div class="brand mt20 clear">
  <div class="wrap w1000">
    <div class="brand-content fLeft border">
      <div class="titleBg" id="tab">
        <span class="title fLeft" style="color: #42d83b;"><a>微信群</a></span>
        <span class="title fLeft" style="border-bottom:1px solid #dbdee1;border-top:2px solid #fafafa;"><a>公众号</a></span>
        <span class="title fLeft" style="border-bottom:1px solid #dbdee1;border-top:2px solid #fafafa;"><a>个人微信</a></span>
        
        <div class="fRight more" style="display: block;">
          <a href="<?php echo U('Weixin/index',array('id'=>44));?>">更多&gt;</a>
        </div>
        <div class="fRight more" style="display: none;">
          <a href="<?php echo U('Weixin/index',array('id'=>47));?>">更多&gt;</a>
        </div>
        <div class="fRight more" style="display: none;">
          <a href="<?php echo U('Weixin/index',array('id'=>48));?>">更多&gt;</a>
        </div>
      </div>
      <div id="box">
        <?php $now = time();$sessionid = session("id"); ?>
        <ul class="boxone" style="display: block;">
          <?php if(is_array($qiangwei_qun)): $k = 0; $__LIST__ = $qiangwei_qun;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
          <div class="place">
            <div class="site">
              <span class="img"><a id="zw4274" href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>">
              <img id="zi4274" src="/Uploads<?php echo ($vo['logo2']?$vo['logo2']:$vo['logo']); ?>" width="130" height="130" alt="<?php echo ($vo["title"]); ?>"></a>
              </span>
            </div>
          </div>
          <p class="settime" endtime="<?php $a_time=$vo['qiangwei_time']+24*3600;echo date('Y-m-d H:i:s',$a_time); ?>">
          	<?php echo (msubstr($vo["title"],0,10)); ?>
          <Div style='display:none;'><span id="zicon4274" class="sofa_<?php if($a_time<$now){echo 'y';}else{echo 'n';} ?> icon">
            <a class="<?php if($sessionid<1){ echo 'loginbox'; }?>" id="zu4274" href="<?php echo U('Member/add',array('publish_type_id'=>1,'qiangwei_sort'=>$k));?>"></a>
            </span>
            <a class="sitename" zid="4274" style="width:120px;overflow:hidden;height:22px" title="<?php echo ($vo["title"]); ?>"  href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>"></a>
            <span class="time"></span></Div>
            
          </p>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <ul class="boxone" style="display: none;">
          <?php if(is_array($qiangwei_hao)): $k = 0; $__LIST__ = $qiangwei_hao;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
          <div class="place">
            <div class="site">
              <span class="img"><a id="zw4274" href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>"><img id="zi4274" src="/Uploads<?php echo ($vo['logo2']?$vo['logo2']:$vo['logo']); ?>" width="130" height="130" alt="<?php echo ($vo["title"]); ?>"></a></span>
            </div>
          </div>
          <p class="settime" endtime="<?php $a_time=$vo['qiangwei_time']+24*3600;echo date('Y-m-d H:i:s',$a_time);?>">
          	<?php echo (msubstr($vo["title"],0,10)); ?>
          <Div style='display:none;'><span id="zicon4274" class="sofa_<?php if($a_time<$now){echo 'y';}else{echo 'n';} ?> icon">
            <a class="<?php if($sessionid<1){ echo 'loginbox'; }?>" id="zu4274" href="<?php echo U('Member/add',array('publish_type_id'=>3,'qiangwei_sort'=>$k));?>"></a>
            </span>
            <a class="sitename" zid="4274" style="width:120px;overflow:hidden;height:22px" title="<?php echo ($vo["title"]); ?>"  href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>"><?php echo (msubstr($vo["title"],0,6)); ?></a>
            <span class="time"></span></div>
          
            
          </p>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <ul class="boxone" style="display: none;">
          <?php if(is_array($qiangwei_person)): $k = 0; $__LIST__ = $qiangwei_person;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
          <div class="place">
            <div class="site">
              <span class="img"><a id="zw4274" href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>"><img id="zi4274" src="/Uploads<?php echo ($vo['logo2']?$vo['logo2']:$vo['logo']); ?>" width="130" height="130" alt="<?php echo ($vo["title"]); ?>"></a></span>
            </div>
          </div>
          <p class="settime" endtime="<?php $a_time=$vo['qiangwei_time']+24*3600;echo date('Y-m-d H:i:s',$a_time); ?>">
          <?php echo (msubstr($vo["title"],0,10)); ?>
          <Div style='display:none;'><span id="zicon4274" class="sofa_<?php if($a_time<$now){echo 'y';}else{echo 'n';} ?> icon">
            <a class="<?php if($sessionid<1){ echo 'loginbox'; }?>" id="zu4274" href="<?php echo U('Member/add',array('publish_type_id'=>2,'qiangwei_sort'=>$k));?>"></a>
            </span>
            <a class="sitename" zid="4274" style="width:120px;overflow:hidden;height:22px" title="<?php echo ($vo["title"]); ?>"  href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>"><?php echo (msubstr($vo["title"],0,6)); ?></a>
            <span class="time"></span></Div>
            
          </p>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- 抢位 END -->
<script language="javascript">
$(function(){
	updateEndTime();
});

//倒计时函数
function updateEndTime()
{
	var date = new Date();
	var time = date.getTime();
	$(".settime").each(function(i){
	var endDate =this.getAttribute("endTime");
	var endDate1 = eval('new Date(' + endDate.replace(/\d+(?=-[^-]+$)/, function (a) { return parseInt(a, 10) - 1; }).match(/\d+/g) + ')');
	var endTime = endDate1.getTime();
	//var endTime = endDate.getTime();
	var lag = (endTime - time) / 1000;
	if(lag > 0)
	{
	var second = Math.floor(lag % 60); 
	var minite = Math.floor((lag / 60) % 60);
	var hour = Math.floor((lag / 3600) % 24);
	var day = Math.floor((lag / 3600) / 24);
	$(this).find('.time').html("到时抢位<br/>还剩"+hour+"小时"+minite+"分"+second+"秒");
	}
	else{
	   var zid=$(this).find('.sitename').attr('zid');
       //var zurl=$("#zu"+zid).attr('href');
       //var zt="空位待抢";
       //var zi="http://weixinqun.xiaoxiaowu.info/404/qw.jpg";
       //$("#zw"+zid).attr('href',zurl);
       //$("#zion"+zid).attr('src',zi);
       //$(this).find('.sitename').attr('href',zurl);
      // $(this).find('.sitename').html(zt);
      $(this).find('span:first').addClass('sofa_y');
      $(this).find('.time').html("<em style='float:left;padding:10px 0 0 20px;font-weight:bold;font-size:16px;'>立即抢位</em>");
	}
	
});
setTimeout("updateEndTime()",1000);
}

$('.settime > .icon').each(function() {
    $(this).mouseover(
		function(){
			$(this).siblings('.time').show();
			}
	)
	$(this).mouseout(
		function(){
			$(this).siblings('.time').hide();
			}
	)
});
</script>


<!-- 微信群-热搜-推广 START -->

<div class="group mt20 clear">
	<div class="wrap w1000">
    	
        <div class="group-content border fLeft">
        <div class="titleBg" id="tab1">
	        <span class="title fLeft" style="color: #42d83b;"><a href="javascript:void(0)">微商货源</a></span>
	        <div class="fRight more" style="display: block;"><a href="/weixin-huoyuan-id-1.html">更多&gt;</a></div>
        </div>
        <div id="box2">          
            <ul class="boxtwo" style="display: block;">
            	<?php if(is_array($weixin_huoyuan)): $i = 0; $__LIST__ = $weixin_huoyuan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <div>
                <a href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>"'>
                  <img src="Uploads<?php echo ($vo['logo2']?$vo['logo2']:$vo['logo']); ?>" width="130" height="130">
                  </a>
              </div>
          <p style="float:left;text-align:left;width:100%;text-align:center;"><a href="<?php echo U('Weixin/show',array('id'=>$vo['id']));?>"  style="overflow:hidden; margin:0 auto;    float: none; text-align:center;height:22px" title="<?php echo ($vo["title"]); ?>"  title="<?php echo ($vo["title"]); ?>"><?php echo (msubstr($vo["title"],0,6)); ?></a></p></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        </div>
       <div class="w280 ml10 fRight">
            <div class="hotword-content border fLeft">
                <div class="titleBg" id="tab2"><span class="title fLeft"><a>热词搜索</a></span>
                </div>
                <div id="box3">
                <ul class="boxtwo remen">
                  <li>
					<?php if(is_array($recisousuo)): $k = 0; $__LIST__ = $recisousuo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><a class="lbc<?php echo (($k-1)%5)+1; ?>" href="<?php echo U('Weixin/search',array('id'=>126,'search'=>$vo['search']));?>" target="_blank"><?php echo ($vo["search"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
				  </li>
                </ul>
             
                </div>
         </div>
            <div class="hotwor-content border fLeft mt20">
                 <div class="titleBg" id="tab2"><span class="title fLeft"><a>快速导航</a></span>
               
                </div>
                <div id="box31">

                <ul class="boxtwo" >
                  <li>
                    <?php if(is_array($kuaisudaohang)): $k = 0; $__LIST__ = $kuaisudaohang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><a class="lbc<?php echo (($k-1)%5)+1; ?>" href="<?php echo U('Article/show',array('id'=>$vo['id']));?>" target="_blank"><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
		          </li>
                </ul>
                </div>
            </div>
            
       </div>     
    </div>
</div>

<!--  微信群-热搜-推广 END -->

<!-- 微信货源 START -->
<div class="goods mt20 clear" style="display:none;">
	<div class="wrap w1000">
    	<div class="goods-content border fLeft">
    		<div class="titleBg"><span class="title fLeft"><a href="<?php echo U('weixin/index',array('id'=>1));?>" target="_blank">微商货源</a></span><span class="fRight more"><a href="<?php echo U('weixin/index',array('id'=>1));?>">更多</a></span></div> 
            <ul>
				<?php if(is_array($weixin_huoyuan)): $i = 0; $__LIST__ = $weixin_huoyuan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
					<p>
						<a href="<?php echo U('weixin/huoyuanshow',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>">
						<img src="Uploads<?php echo ($vo["logo"]); ?>" width="215" height="180" alt="<?php echo ($vo["title"]); ?>"></a>
							
							
					</p>
					<p>
						<a href="<?php echo U('weixin/huoyuanshow',array('id'=>$vo['id']));?>" style="width:205px;overflow:hidden" title="<?php echo ($vo["title"]); ?>"  ><?php echo (msubstr($vo["title"],0,8)); ?></a>
					</p>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>        
    </div>
</div>
<!-- 微信货源 END -->

<!-- 微信营销 sTART -->
<div class="market mt20 clear">
	<div class="wrap w1000">
    	<div class="market-content fLeft">
        	<div class="market-col1 border fLeft mr20">
            	<div class="titleBg"><span class="title fLeft"><a href="<?php echo U('Article/index',array('id'=>142));?>" target="_blank"><?php echo ($weixinyingxiao_cat["catname"]); ?></a></span><span class="fRight more"><a href="<?php echo U('Article/index',array('id'=>142));?>">更多</a></span></div>
                <div class="photo">
                    
                </div>
                <ul>
                    <?php if(is_array($weixinyingxiao)): $i = 0; $__LIST__ = $weixinyingxiao;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><i class="square"></i><a href="<?php echo U('Article/show',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>"><?php echo (msubstr($vo["title"],0,20)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            
            <div class="market-col1 border fLeft mr20">
            	<div class="titleBg"><span class="title fLeft"><a href="<?php echo U('Article/index',array('id'=>55));?>" target="_blank"><?php echo ($weixinzixun_cat["catname"]); ?></a></span><span class="fRight more"><a href="<?php echo U('Article/index',array('id'=>55));?>">更多</a></span></div>
                <div class="photo">
                    
                </div>
                <ul>
					 <?php if(is_array($weixinzixun)): $i = 0; $__LIST__ = $weixinzixun;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><i class="square"></i><a href="<?php echo U('Article/show',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>"><?php echo (msubstr($vo["title"],0,20)); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
					
                </ul>
            </div>
            
	
            <div class="market-col2 border fRight">
            	<div class="titleBg"><span class="title fLeft"><a href="<?php echo U('Article/index',array('id'=>150));?>" target="_blank"><?php echo ($weixinxuetang_cat["catname"]); ?></a></span><span class="fRight more"><a href="<?php echo U('Article/index',array('id'=>150));?>">更多</a></span></div>
                <div class="photo">
                    <?php if(is_array($weixinxuetang)): $i = 0; $__LIST__ = $weixinxuetang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><i class="square"></i><a href="<?php echo U('Article/show',array('id'=>$vo['id']));?>" title="<?php echo ($vo["title"]); ?>"><?php echo (msubstr($vo["title"],0,15)); ?></a></p><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
  
        </div>
    </div>
</div>
<div style = "background:#fff;height:60px;width:1000px;margin:0 auto;margin-top:20px;">
    <ul style = "padding-left:20px;padding-top:20px;">
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style = "float:left;margin-right:20px;">
       <a href="<?php echo ($vo["url"]); ?>" ><?php echo ($vo["name"]); ?></a>      </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<!-- 微信营销 END -->
<!-- 底部 START -->
    <div class="footer mt20">
      <div class="wrap w1000">
        <div class="f-content">
          <div class="f-code fLeft"> <span><img src="__ROOT__/Uploads<?php $set=$_result=M('Set')->getField('qrcode',1); echo $set;?>"width="115" height="115"></span>
 <span><p><strong>扫描二维码</strong></p><p>手机客户端更便捷<br>关注我们，快速了解商家信息，商家活动</p></span>

          </div>
		  <?php $other=$_result=M('Other')->where('status=1 and settag="link"')->find(); echo $other['setvalue'];?>
          <div class="f-info fRight">
            <ul>
              <?php if(is_array($footer_info)): $i = 0; $__LIST__ = $footer_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <p><strong><?php echo ($vo["catname"]); ?></strong>
                </p>
                <?php if(is_array($vo["child"])): $i = 0; $__LIST__ = $vo["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><p><a href="<?php echo U('Article/show',array('id'=>$vo1['id']));?>" title="<?php echo ($vo1["title"]); ?>" target="_blank"><?php echo (strip_tags($vo1["title"])); ?></a></p>
                <!--<?php echo (msubstr(strip_tags($vo1["title"]),0,5)); ?>--><?php endforeach; endif; else: echo "" ;endif; ?>
              </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
	 
    <div style="background:#5b5b5b;height:70px;text-align:center;color:#aaa;">
      <p style="padding-top:25px;"><?php $other=$_result=M('Other')->where('status=1 and settag="footer"')->find(); echo $other['setvalue'];?></p>
	  <?php $other=$_result=M('Other')->where('status=1 and settag="cnzzstatistics"')->find(); echo $other['setvalue'];?>
    </div>
    <div class="help_box" style="display:none;">
      <a href="javascript:;" class="float_qq" data-show="1"></a> 
      <a class="zixun" href="javascript:;" id="help_qq1">客服咨询</a>  
      <a class="join" href="javascript:;" id="help_qq2">广告合作</a>  
      <b class="tels">咨询热线<br>
          <?php $other=$_result=M('Other')->where('status=1 and settag="zxrx"')->find(); echo $other['setvalue'];?>
        </b>

      <div>
        <img src="__ROOT__/Uploads<?php $set=$_result=M('Set')->getField('qrcode',1); echo $set;?>">
      </div>
      <p class="tc">扫一扫立即体验</p>
      <div class="qq_list" id="qq_list1" style="display: none;" data-show="1">
        <a href="javascript:;" class="close_qq" id="close_qq1"></a>
         <h4>客服咨询</h4>

        <div class="area_qq">
		<?php $other=$_result=M('Other')->where('status=1 and settag="kfzx"')->find(); echo $other['setvalue'];?>
        </div>
      </div>
      <div class="qq_list join_bg" id="qq_list2" style="display: none;">
        <a href="javascript:;" class="close_qq" id="close_qq2"></a>
         <h4>广告咨询</h4>

        <div class="area_qq">
		<?php $other=$_result=M('Other')->where('status=1 and settag="ggzx"')->find(); echo $other['setvalue'];?>
        </div>
      </div>
    </div>
<script>
  $(function(){
    $(".float_qq").click(function(){
      if($(this).attr("data-show") == 1)
      {
        $(this).attr("data-show",0);
        $(this).addClass("active");
        $(".help_box").animate({"right":"-140px"});
      }else{
        $(this).attr("data-show",1);
        $(this).removeClass("active");
        $(".help_box").animate({"right":"0"});
      }
      
      });
    $("#help_qq1").click(function(){
      $("#qq_list2").hide();
      if($("#qq_list1").is(":hidden"))
      {
        $("#qq_list1").slideDown();
      }else{
        $("#qq_list1").slideUp();
      }
      });
    $("#close_qq1").click(function(){
      $("#qq_list1").slideUp();     
      }); 
    $("#help_qq2").click(function(){
      $("#qq_list1").hide();
      if($("#qq_list2").is(":hidden"))
      {
        $("#qq_list2").slideDown();
      }else{
        $("#qq_list2").slideUp();
      }
      });
    $("#close_qq2").click(function(){
      $("#qq_list2").slideUp();     
      }); 
    });
</script>



<!-- 弹出登陆 -->

<script>
    $("#loginbox").click(function(){
        $("#Tourl").val('');
        $(".popDoc").show();
        return false;
    });
    $("#loginbox2").click(function(){
        $(".popDoc").show();
        return false;
    });
    $(".loginbox").click(function(){
	<?php if(session('id') < 1 ){ ?>
        var tourl=$(this).attr('href');
        $("#Tourl").val(tourl);
        $(".popDoc").show();
        return false;
	<?php }else{ ?>
	location.href="/member-add.html";
	<?php } ?>    });
    $(".release-btn").click(function(){
	<?php if(session('id') < 1 ){ ?>
        var tourl=$(this).attr('href');
        $("#Tourl").val(tourl);
        $(".popDoc").show();
        return false;
	<?php }else{ ?>
	location.href="/member-add.html";
	<?php } ?>
    });
    function validate_form(thisform)
    {
        var urls="/user-login.html";
        var username=thisform.username.value;
        var userpass=thisform.userpass.value;
        var Tourl=thisform.Tourl.value;
        var tmpmsg='';
        if(username==''){tmpmsg+="请输入用户名!n"}
        if(userpass==''){tmpmsg+="请输入密码!n"}
        if(Tourl!=''){
           $.ajax({
            type: "post",
            dataType: "json",
            url: urls,
            data: "json=1&username="+username+"&userpass="+userpass+"&action=login",
            success: function(result){

                if(result.status){
                    location.href = Tourl;
                    //alert(result.msg);
                }else{
                    alert(result.msg);
                }
                
                }
            });
           
           
           return false;
        }
        if(tmpmsg!=''){
            alert(tmpmsg);
            return false;
        }
    }
</script><!-- 底部 END -->
<script type="text/javascript">
  $('#navmenu_company,#navmenu_personal').mouseover(function(event){
    $('#navmenu_company,#navmenu_personal').removeClass('active');
    $(this).toggleClass('active');
    event.stopPropagation();
    });
  $('#navmenu_company,#navmenu_personal').mouseout(function(event){
    $('#navmenu_company,#navmenu_personal').removeClass('active');
    event.stopPropagation();
    });
</script>
<div style="display:none;"><!--<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe631b3a1fedd3e3a3970479d2e0d0da6' type='text/javascript'%3E%3C/script%3E"));
</script>-->
<script src="__PUBLIC__/js/h.js" type="text/javascript"></script><a href="http://tongji.baidu.com/hm-web/welcome/ico?s=e631b3a1fedd3e3a3970479d2e0d0da6" target="_blank"><img border="0" src="__PUBLIC__/qianduan/21.gif" width="20" height="20"></a></div>
<script src="__PUBLIC__/js/keffect.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#tab span').bind('mouseover',function(){
    var index = $(this).index();
    var mores = $('#tab > div');
    var divs = $('#box > .boxone ');
    $(this).parent().children("span").attr("style", "border-bottom:1px solid #dbdee1;border-top:2px solid #fafafa;");
    $(this).attr("style", "color: #42d83b;");
    divs.hide();
    mores.hide();
    divs.eq(index).show();
    mores.eq(index).show();
  })
})
</script>
<script type="text/javascript">
$(document).ready(function () {
  $('#tab1 span').bind('mouseover',function(){
    var index = $(this).index();
    var mores = $('#tab1 > div');
    var divs = $('#box2 > .boxtwo ');
    $(this).parent().children("span").attr("style", "border-bottom:1px solid #dbdee1;border-top:2px solid #fafafa;");
    $(this).attr("style", "color: #42d83b;");
    divs.hide();
    mores.hide();
    divs.eq(index).show();
    mores.eq(index).show();
  })
})
</script>
<script type="text/javascript">
$(document).ready(function () {
  $('#tab2 span').bind('mouseover',function(){
    var index = $(this).index();
    var divs = $('#box3 > .boxtwo ');
    $(this).parent().children("span").attr("style", "border-bottom:1px solid #dbdee1;border-top:2px solid #fafafa;");
    $(this).attr("style", "color: #42d83b;");
    divs.hide();
    divs.eq(index).show();
  })
})
</script>



</body></html>