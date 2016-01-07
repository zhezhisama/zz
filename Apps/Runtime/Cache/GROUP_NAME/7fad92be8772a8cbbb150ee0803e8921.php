<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta property="qc:admins" content="71602102676750206375" />
<meta http-equiv="Cache-Control" content="no-siteapp">
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
        <div class="utility-container w1000">
        	<a class="title1">微商人的第一个网站</a>
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
      <li class="dropdown" style="display:none;"><a id="nav_event" href="<?php echo U('weixin/order',array('id'=>89));?>">排行榜</a></li>
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
<!-- 头部 END -->

<div class="hotTags">
  <div class="first">热门标签<span class="arrow arrow_right"></span></div>
  <a <?php if($wcatid == 0){echo 'class="z-sel"';} ?> href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$vo['id'],'or'=>$or,'time'=>$time));?>">全部</a> 
  <?php if(is_array($catidlist)): $k = 0; $__LIST__ = $catidlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k < 16): ?><a <?php if($wcatid == $vo['id']){echo 'class="z-sel"';} ?> href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$vo['id'],'or'=>$or,'time'=>$time));?>"><?php echo ($vo["catname"]); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</div>


<div class="leader mt20">
  <div class="w1000 wrap command">
    <div class="l-sort">
      <div class="d2 sort_btn" style="border-bottom:1px solid #eee;">
      <p <?php if($catid == 44){echo 'class="active"'; } ?> ><a href="<?php echo U('Weixin/order',array('catid'=>44));?>">微信群</a></p>
        <p <?php if($catid == 48){echo 'class="active"'; } ?>><a href="<?php echo U('Weixin/order',array('catid'=>48));?>">个人微信</a></p>
        <p <?php if($catid == 47){echo 'class="active"'; } ?>><a href="<?php echo U('Weixin/order',array('catid'=>47));?>">公众号</a></p>
        <p <?php if($catid == 1){echo 'class="active"'; } ?>><a href="<?php echo U('Weixin/order',array('catid'=>1));?>">微商货源</a></p>
      </div>
      <div class="d2"> <span class="fLeft"><strong>类型:</strong></span>
        <ul class="fLeft">
          

        <li <?php if($wcatid == 0){echo 'class="active"';} ?> ><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$vo['id'],'or'=>$or,'time'=>$time));?>">全部</a></li>
          <?php if(is_array($catidlist)): $i = 0; $__LIST__ = $catidlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($wcatid == $vo['id']){echo 'class="active"';} ?> ><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$vo['id'],'or'=>$or,'time'=>$time));?>"><?php echo ($vo["catname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
      <div class="d2"> <span class="fLeft"><strong>排序:</strong></span>
        <ul class="fLeft">
        <li <?php if($or == 'hits'){echo 'class="active"';} ?>><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$wcatid,'or'=>'hits','time'=>$time));?>">人气最高</a></li>
            <li <?php if($or == 'xh'){echo 'class="active"';} ?> ><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$wcatid,'or'=>'xh','time'=>$time));?>">点赞最高</a></li>
        </ul>
      </div>
      <div class="d2"> <span class="fLeft"><strong>时间:</strong></span>
        <ul class="fLeft">
        <li <?php if($time == 0){echo 'class="active"';} ?>><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$wcatid,'or'=>$or,'time'=>''));?>">不限</a></li>
          <li <?php if($time == 3){echo 'class="active"';} ?>><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$wcatid,'or'=>'hits','time'=>3));?>">三天内</a></li>
          <li <?php if($time == 7){echo 'class="active"';} ?> ><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$wcatid,'or'=>'hits','time'=>7));?>">本周</a></li>
          <li <?php if($time == 30){echo 'class="active"';} ?>><a href="<?php echo U('Weixin/order',array('catid'=>$catid,'wcatid'=>$wcatid,'or'=>'hits','time'=>30));?>">一月内</a></li>
        </ul>
      </div>
    </div>
    <table class="top" cellpadding="0" cellspacing="0" align="center" border="0" width="96%">
      <tbody><tr>
        <th height="40"></th>
        <th height="40">微信头像</th>
        <th width="15%">微信名称</th>
        <th width="17%">微信号</th>
        <th width="25%">简介</th>
        <th width="12%">关注度</th>
        <th width="17%"><div align="center">扫一扫</div></th>
      </tr>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
        <td width="2%" height="100" align="center" class="item1"></td>
        <td width="12%" align="center" class="item1"><span class="fLeft"><a href="<?php echo U('Weixin/turnshow',array('id'=>$vo['id']));?>"><img src="/Uploads<?php echo ($vo["logo"]); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>" height="80" width="80"></a></span></td>
        <td class="item2" valign="middle"><span class="fLeft"><a href="<?php echo U('Weixin/turnshow',array('id'=>$vo['id']));?>"></a></span><span class="name"><?php echo ($vo["title"]); ?></span></td>
        <td class="item3"><?php echo ($vo["wxaccount"]); ?></td>
        <td><a href=" <?php echo U('Weixin/turnshow',array('id'=>$vo['id']));?>"><?php echo ($vo["pubaccount"]); ?></a></td>
        <td><span style="color:red;">关注：<?php echo ($vo["hits"]); ?></span></td>
        <td class="item4"><div align="center"><img src="/Uploads<?php echo ($vo["qrcode"]); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>" height="80" width="80">
              <!--<div class="zancai"><span onclick="praise(this,<?php echo ($vo["id"]); ?>,'xh');"><i class="zan"></i><em class="txt_num" id="<?php echo ($vo["id"]); ?>" znum="94"><?php echo ($vo["xh"]); ?></em></span><span onclick="praise(this,<?php echo ($vo["id"]); ?>,'nxh');"><i class="cai"></i><em class="txt_num" id="cnum<?php echo ($vo["id"]); ?>" cnum="-22"><?php echo ($vo["nxh"]); ?></em></span></div>-->      
        </div></td><?php endforeach; endif; else: echo "" ;endif; ?>
<script>
function praise(cur,id,type){
       $.get("<?php echo U('Weixin/xh');?>",{id:id,type:type},function(data){
            if(data.status==1){
                $(cur).children('em').html(data.info);
            }else{
                
                alert(data.info);
            }
            
       });
    }
</script>
    </tbody></table>
          <div class="pageNo vm center">
                 <?php echo ($page); ?>
                 <!-- <a class="selected">1</a><a href="http://weixinqun.xiaoxiaowu.info/phb-9-wx-0-or-7-da-7-page-2.html" title="页 2">2</a><a href="http://weixinqun.xiaoxiaowu.info/phb-9-wx-0-or-7-da-7-page-3.html" title="页 3">3</a><a href="http://weixinqun.xiaoxiaowu.info/phb-9-wx-0-or-7-da-7-page-4.html" title="页 4">4</a><a href="http://weixinqun.xiaoxiaowu.info/phb-9-wx-0-or-7-da-7-page-5.html" title="页 5">5</a>...<a href="http://weixinqun.xiaoxiaowu.info/phb-9-wx-0-or-7-da-7-page-32.html">32</a><a href="http://weixinqun.xiaoxiaowu.info/phb-9-wx-0-or-7-da-7-page-2.html" class="pageNext"><b>&gt;</b></a> -->
    </div>
           
  </div>
</div>
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