<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link href="__PUBLIC__/css/common.css" rel="stylesheet" type="text/css">
<!-- 头部 END -->
<style type="text/css">
<!--
.STYLE1 {color: #FF0000}
-->
</style>
<div class="hy mt5 clear">
  <div class="w1000 wrap">
    <div class="c-content command">
      <div class="c-top">
        <p>
          我的位置：<a href="index.php">首页</a> &gt; <span class="b_nav"><?php if(is_array($position)): $i = 0; $__LIST__ = $position;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Weixin/index',array('id'=>$vo['id']));?>"><?php echo ($vo["catname"]); ?></a> &gt;<?php endforeach; endif; else: echo "" ;endif; ?>
        </p>
      </div>
      <div class="c-left fLeft">
        <div class="box1">
          
          <div class="fLeft b1">
            <p>
                <img src="<?php if(empty($data["qrcode"])): ?>../Public/images/nopic.gif<?php else: ?>
                <?php if($data["tupian"] == '1'): echo ($data["qrcode"]); else: ?>__ROOT__/Uploads<?php echo ($data["qrcode"]); endif; endif; ?>" alt="<?php echo ($data["pubaccount"]); ?>" width="250" /> 
                           </p>
            <p class="flip">
	    <?php if(!empty($prevdata["id"])): ?><a class="fixed-btn" rel="prev" href="<?php echo U('Weixin/show',array('id'=>$prevdata['id']));?>" title="<?php echo ($prevdata["pubaccount"]); ?>"><i class="pre"></i>上一篇</a><?php endif; ?>
	    <?php if(!empty($nextdata["id"])): ?><a class="fixed-btn" rel="next" href="<?php echo U('Weixin/show',array('id'=>$nextdata['id']));?>" title="<?php echo ($nextdata["pubaccount"]); ?>"><i class="next"></i>下一篇</a><?php endif; ?>
            </p>
          </div>
          <div class="fRight b2">
          	<h4><?php echo ($data["pubaccount"]); ?></h4>
            <p><strong>微信号：</strong><span style="color:#318000;"><?php echo ($data["wxaccount"]); ?></span><span style="margin-left:40px;color:#f00;">(群满员可加群主)</span></p>
            <p><strong>QQ号：</strong><?php echo ($data["qq"]); ?></p>
            <p><strong>关注度：</strong><span class="STYLE1"><?php echo ($data["hits"]); ?>人关注</span></p>
            <p><strong>评价度：</strong><img alt="星级" src="http://weixinqun.xiaoxiaowu.info/Apps/Tpl/weixinqun/Public/images/xx5.jpg" /></p>
            <p>
              <strong>地区：</strong><a href="" title="<?php echo (getareasname($data["province"])); ?>"><b><?php echo (getareasname($data["province"])); ?></b></a>/<a href="" title="<?php echo (getareasname($data["city"])); ?>"><b><?php echo (getareasname($data["city"])); ?></b></a>            </p>
            <p>
              <strong>发布时间：</strong><?php echo (todate($data["create_time"],'Y-m-d')); ?>            </p>
            <p><strong>简介：</strong><?php echo (msubstr(strip_tags($data["content"]),0,40)); ?> </p>
            <br>
            <div>
             <!-- <div class="gb">
                <span class="zan" onclick="xh(this,<?php echo ($data['id']); ?>,'xh')">
                  <em class="txt_num" id="znum9537" znum="<?php echo ($data["xh"]); ?>">
				  <?php echo ($data["xh"]); ?></em>
                </span>
                <span class="cai" onclick="xh(this,<?php echo ($data['id']); ?>,'nxh')">
                  <em class="txt_num" id="cnum9537" cnum="<?php echo ($data["nxh"]); ?>"><?php echo ($data["nxh"]); ?>
                  </em>
                </span>              </div>-->
            </div>
            <br>
            <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare"></div>
          </div>
        </div>
        <div class="box2">
          <h4>你可能对下面的微信群也感兴趣</h4>
          <ul>
            <?php $_result=M('Weixin')->where('status=1 and catid in ('.$data['catid'].')')->order('rand()')->limit(15)->select();foreach($_result as $key=>$weixin):?><li>
              <a href="<?php echo U('Weixin/show',array('id'=>$weixin['id']));?>" title="<?php echo ($weixin["pubaccount"]); ?>" target='_blank'>
                <img src="<?php if(empty($weixin["logo"])): if(empty($weixin["weblogo"])): ?>../Public/images/nopic.gif<?php else: echo ($weixin["weblogo"]); endif; else: ?>
                <?php if($weixin["tupian"] == '1'): echo ($weixin["qrcode"]); else: ?>__ROOT__/Uploads<?php echo ($weixin["qrcode"]); endif; endif; ?>" width="80" height="80">
              </a>
              <p><a href="<?php echo U('Weixin/show',array('id'=>$weixin['id']));?>" title="<?php echo ($weixin["pubaccount"]); ?>" target='_blank' style=" height: 30px; line-height: 30px;"><?php echo (msubstr($weixin["pubaccount"],0,5)); ?></a></p>
            </li><?php endforeach;?>
          </ul>
        </div>
       <div class="comment" style="display:none;">
        <?php if($data['iscomment'] == 1): if(($iscomment) == "1"): if(($commenttype) == "2"): ?><div class="comment">
                <?php $other=$_result=M('Other')->where('status=1 and settag="duoshuo"')->find(); echo $other['setvalue'];?>
              </div><?php endif; ?>
            <?php if(($commenttype) == "1"): $url=U('Weixin/addcomment');?>
              ﻿<script type="text/javascript">
    //验证码刷新
    function fleshVerify(cur){ 
        $(cur).attr('src',"<?php echo U('Member/verify',array('t'=>time()));?>");
    }
    function reply(cur,id){
        $('.replyform').children().remove();
        var html='<div class="box4">';
            html+='<form id="formreply" action="<?php echo ($url); ?>" method="post">';
                html+='<textarea cols="10" name="content" id="content" ></textarea>';

                html+='<p>';
                    html+='<input type="text" class="ipt2" id="verify" name="verify"> ';
                    html+='<img id="verifyImg" src="<?php echo U("Member/verify",array("t"=>time()));?>" onClick="fleshVerify(this)" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle">';
                    html+='<input type="hidden" name="pid" value='+id+'>';
                    html+='<input type="hidden" name="catid" value="<?php echo ($data["catid"]); ?>">';
                    html+='<input type="hidden" name="objectid" value="<?php echo ($data["id"]); ?>">';
                    html+='<input type="hidden" name="reply" value="1">';
                    html+='&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="replybutton" value="提交" class="tj" >';
                html+='</p>';      
            html+='</form>';
            html+='</div>';
        $(cur).parent().next('.replyform').html(html);
       
    }
</script>


<div class="box3">
    <h4>最新评论(共<?php echo ($count); ?>条)</h4>
    <ul>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            <div class="commentinfo">
                <div class="memberimg"><?php $logo=getMemberULogo($vo['membername']);?><img src="<?php if(empty($logo)): ?>../Public/images/nopic.gif<?php else: ?>__ROOT__/Uploads<?php echo ($logo); endif; ?>" width="48" height="48" /></div>
                <div class="memberother">
                    <div class="membername"><?php echo ($vo["membername"]); ?></div>
                    <div class="commentcontent"><?php if(($vo["reply"]) == "1"): ?><span>回复&nbsp;<?php echo (getpidmembername($vo["pid"])); ?>：</span><?php echo (nl2br($vo["content"])); else: echo (nl2br($vo["content"])); endif; ?></div>
                    <div class="commentreply"><span title="<?php echo (todate($vo["create_time"],'Y-m-d H:i:s')); ?>"><?php echo (commentdate($vo["create_time"])); ?></span>&nbsp;&nbsp;<a href="javascript:return false;" onclick="reply(this,<?php echo ($vo["id"]); ?>);">回复</a></div>
                    <div class="replyform"></div>
                </div>
            </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div class="page"><?php echo ($page); ?></div>
<div class="box4">
    <h4>发表评论</h4>
    <form id="formcomment" action="<?php echo ($url); ?>"  method="post">
        <textarea cols="10" name="content" id="content" ></textarea>

        <p>
            <input type="text" class="ipt2" id="verify" name="verify"> 
            <img src="<?php echo U('Member/verify');?>" onClick="fleshVerify(this)" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle">

            <input type='hidden' name='catid' value='<?php echo ($data["catid"]); ?>'>
            <input type='hidden' name='objectid' value='<?php echo ($data["id"]); ?>'>
            <input type='hidden' name='reply' value='0'>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type='submit' id="submit" value='提交' class="tj">
        </p>
    </form>
</div><?php endif; endif; endif; ?>
      </div>
       
      </div>
      <div class="c-right fRight">
	<div class="box" style="overflow:hidden;">
		 <h4>微商联盟</h4>
		 		<ul>
			<?php $_result=M('Article')->where('status=1 and catid in (158)')->field('id,title')->order('create_time desc')->limit(5)->select();foreach($_result as $key=>$article):?><li class="bt"> <span class="fLeft" style="width:75%;"><a href="<?php echo U('Article/show',array('id'=>$article['id']));?>" title="<?php echo ($article['title']); ?>" target='_blank'><?php echo ($article['title']); ?></a></span> 
				</li><?php endforeach;?>
		</ul>
		<p style="height:30px;padding:5px 0;margin-top:10px;border-bottom:1px dashed #ccc;"> <span class="fLeft ml10" style="width:120px;color:red;"><b>微商交流与合作</b></span><span class="fLeft ml10"><a href="<?php $other=$_result=M('Other')->where('status=1 and settag="wsjlhz"')->find(); echo $other['setvalue'];?>" rel="nofollow" target="_blank"><img src="../Public/images/button_11.gif" title="微商交流与合作" alt="微商交流与合作"></a></span>
		</p>
		<p style="height:30px;padding:5px 0;border-bottom:1px dashed #ccc;"> <span class="fLeft ml10" style="width:120px;color:red;"><b>AAA微商交流</b></span><span class="fLeft ml10"><a href="<?php $other=$_result=M('Other')->where('status=1 and settag="aaawsjl"')->find(); echo $other['setvalue'];?>" rel="nofollow" target="_blank"><img src="../Public/images/button_11.gif" title="AAA微商交流" alt="AAA微商交流"></a></span>
		</p>
		<p style="height:30px;padding:5px 0;border-bottom:1px dashed #ccc;"> <span class="fLeft ml10" style="width:120px;color:red;"><b>微商运营咨询</b></span><span class="fLeft ml10"><a href="<?php $other=$_result=M('Other')->where('status=1 and settag="hyq"')->find(); echo $other['setvalue'];?>" rel="nofollow" target="_blank"><img src="../Public/images/button_11.gif" title="微商货源群" alt="微商货源群"></a></span>
		</p>
	</div>
	<div class="box">
		 <h4>本周给力排行

        <span class="fRight mr10">

          <a href="<?php echo U('Weixin/order',array('id'=>'89'));?>">更多&gt;</a>

        </span>

      </h4>
		<ul id="ph-list">
			<?php $_result=M('Weixin')->where('status=1 and catid in (53,162,160,161,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,44,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,48,230,229,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,266,265,264,263,262,267,268,269,270,271,47)')->field('id,logo,pubaccount,hits')->order('hits desc')->limit(8)->select();foreach($_result as $key=>$weixin):?><li>
					<div class="cover-img cover"> <a href="<?php echo U('Weixin/show',array('id'=>$weixin['id']));?>" class="cr" title="<?php echo ($weixin["pubaccount"]); ?>"><img src="<?php if(empty($weixin["logo"])): if(empty($weixin["weblogo"])): ?>../Public/images/nopic.gif<?php else: echo ($weixin["weblogo"]); endif; else: ?>
                    <?php if($weixin["tupian"] == '1'): echo ($weixin["logo"]); else: ?>__ROOT__/Uploads<?php echo ($weixin["logo"]); endif; endif; ?>" alt="<?php echo ($weixin["pubaccount"]); ?>" title="<?php echo ($weixin["pubaccount"]); ?>" height="50" width="50"></a>
					</div>
					<div class="name"> <a href="<?php echo U('Weixin/show',array('id'=>$weixin['id']));?>" title="<?php echo ($weixin["pubaccount"]); ?>"><?php echo ($weixin["pubaccount"]); ?></a>
					</div>
				</li><?php endforeach;?>
		</ul>
	</div>
	<div class="box">
		 <h4>最新收录

        <span class="fRight mr10">

          <a href="<?php echo U('Weixin/news',array('id'=>'90'));?>">更多&gt;</a>

        </span>

      </h4>
		<ul id="ph-list">
			<?php $_result=M('Weixin')->where('status=1 and catid in (53,162,160,161,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,44,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,48,230,229,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,266,265,264,263,262,267,268,269,270,271,47)')->field('id,logo,pubaccount')->order('create_time desc')->limit(8)->select();foreach($_result as $key=>$weixin):?><li>
					<div class="cover-img cover"> <a href="<?php echo U('Weixin/show',array('id'=>$weixin['id']));?>" class="cr" title="<?php echo (msubstr($weixin["pubaccount"],0,6)); ?>"><img src="<?php if(empty($weixin["logo"])): if(empty($weixin["weblogo"])): ?>../Public/images/nopic.gif<?php else: echo ($weixin["weblogo"]); endif; else: ?>
                    <?php if($weixin["tupian"] == '1'): echo ($weixin["logo"]); else: echo ($weixin["logo"]); endif; endif; ?>" alt="<?php echo (msubstr($weixin["pubaccount"],0,6)); ?>" title="<?php echo (msubstr($weixin["pubaccount"],0,6)); ?>" height="50" width="50"></a>
					</div>
					<div class="name"> <a href="<?php echo U('Weixin/show',array('id'=>$weixin['id']));?>" title="<?php echo ($weixin["pubaccount"]); ?>"><?php echo (msubstr($weixin["pubaccount"],0,6)); ?></a>
					</div>
				</li><?php endforeach;?>
		</ul>
	</div>
	<div class="box">
		 <h4>微信资讯

        <span class="fRight mr10">

          <a href="<?php echo U('Weixin/hot',array('id'=>'88'));?>">更多&gt;</a>

        </span>

      </h4>
		<ul>
			<?php $_result=M('Article')->where('status=1 and catid in (55)')->field('id,title')->order('create_time desc')->limit(10)->select();foreach($_result as $key=>$article):?><li class="bt"> <span class="fLeft" style="width:75%;"><a href="<?php echo U('Article/show',array('id'=>$article['id']));?>" title="<?php echo ($article['title']); ?>" target='_blank'><?php echo ($article['title']); ?></a></span> 
				</li><?php endforeach;?>
		</ul>
	</div>
</div>
    </div>
  </div>
</div>
<script>
function praise(id,tid,ztype){
    var urls="/user-praise.html";
    var zid="#znum"+id;
    var cid="#cnum"+id;
    var znum=parseInt($(zid).attr('znum'));
    var cnum=parseInt($(cid).attr('cnum'));
    $.ajax({type: "post",dataType: "json",url: urls,data: "json=1&id="+id+"&cid="+tid+"&ztype="+ztype,success: function(result){if(result.type){if(ztype==1){znum=znum+1;$(zid).attr('znum',znum);$(zid).html(znum);}else{cnum=cnum-1;$(cid).attr('cnum',cnum);$(cid).html(cnum);}alert(result.msg);}else{alert(result.msg);}}});
}
</script>

<script type="text/javascript">    $(function(){
        $('#pubaccount').click(function(){
            alert('a');
            copyToClipBoard("aaaaaaaaaaaaaaa");
        })
        
    })
    function xh(cur,id,type){
       $.get("<?php echo U('Weixin/xh');?>",{id:id,type:type},function(data){
            if(data.status==1){
                $(cur).children('em').html(data.info);
            }else{
                
                alert(data.info);
            }
            
       });
    }
</script>
<script type="text/javascript" language="javascript">        //复制到剪切板js代码
        function copyToClipBoard(s) {
            
            //alert(s);
            if (window.clipboardData) {
                window.clipboardData.setData("Text", s);
                alert("已经复制到剪切板！"+ "\n" + s);
            } else if (navigator.userAgent.indexOf("Opera") != -1) {
                window.location = s;
            } else if (window.netscape) {

                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                } catch (e) {
                    alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");
                }
                var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
                if (!clip)
                    return;
                var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
                if (!trans)
                    return;
                trans.addDataFlavor('text/unicode');
                var str = new Object();
                var len = new Object();
                var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
                var copytext = s;
                str.data = copytext;
                trans.setTransferData("text/unicode", str, copytext.length * 2);
                var clipid = Components.interfaces.nsIClipboard;
                if (!clip)
                    return false;
                clip.setData(trans, null, clipid.kGlobalClipboard);
                alert("已经复制到剪切板！" + "\n" + s)
            }else{

            }
        }
    </script>

<!-- 底部 START -->
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