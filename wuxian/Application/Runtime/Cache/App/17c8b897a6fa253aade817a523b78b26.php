<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>超级人脉</title>
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="../Public/Static/css/foods.css?t=333" rel="stylesheet"
	type="text/css">
<script type="text/javascript" src="../Public/Static/js/jquery.min.js"></script>
<script type="text/javascript" src="../Public/Static/js/wemall.js?14115"></script>
<script type="text/javascript" src="../Public/Static/js/alert.js"></script>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.call('hideOptionMenu');
});
var appurl = '__APP__';
var rooturl = '__ROOT__';

$(function () {
	$("#all_cnt").click(function(){
		$(".member_cnt").toggle();
		if($(this).find('img').attr("src")=="../Public/Static/images/arrow_unclick.png")
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_click.png");
		}
		else
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_unclick.png");
		}
	});
	
	$("#all_price").click(function(){
		$(".price_cnt").toggle();
		if($(this).find('img').attr("src")=="../Public/Static/images/arrow_unclick.png")
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_click.png");
		}
		else
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_unclick.png");
		}
	});
	
	$("#memeber_url").click(function(){
		$(".memeber_url").toggle();
		if($(this).find('img').attr("src")=="../Public/Static/images/arrow_unclick.png")
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_click.png");
		}
		else
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_unclick.png");
		}
	});
	
	$("#all_buy").click(function(){
		$(".buy_cnt").toggle();
		if($(this).find('img').attr("src")=="../Public/Static/images/arrow_unclick.png")
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_click.png");
		}
		else
		{
			$(this).find('img').attr("src","../Public/Static/images/arrow_unclick.png");
		}
	});
	$(".fans").click(function(){
		location="/hufen/list.php?uid=<?php echo ($users["uid"]); ?>";
	})
	
});

</script>
<!-- tianshi.lomedia.com.cn Baidu tongji analytics -->
<script>
var _hmt = _hmt || [];
(function() {
var hm = document.createElement("script");
hm.src = "//hm.baidu.com/hm.js?d4254b715086304dcf37fab38e56efba";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<body class="sanckbg mode_webapp">
	<div id="member-container" style="display: block;">

		<div class="menu_header">
			<div class="menu_topbar">
				<div id="menu" class="sort ">
					<a href="">分销详情</a>
				</div>
			</div>
		</div>

		<div class="div_header">
        	<div style=" height:8px;"></div>
			<span style='float:left;margin-left:10px;margin-right:10px;'>
				<?php $wx_info = json_decode($users['wx_info'],true); $img = !empty($wx_info['headimgurl'])?$wx_info['headimgurl']:'../Public/Static/images/defult.jpg'; echo "<img src='".$img."' width='70px;' height='70px;' style='border-radius:50%; border:5px solid rgba(255,255,255,.6)'>"; ?>
			</span>
			<span class="header_right">
            <div style="height:8px;"></div>
				<div class="header_l_di">
					<span  style=" color:#fff !important; font-size:14px; margin-top:10px;">昵称：<?php echo $wx_info['nickname']; ?></span>&nbsp;&nbsp;
                    <span style=" display:none;">会员ID号：<?php echo ($users["id"]); ?></span>
					<!--<a style='color:red' href="./index.php?g=App&m=Member&a=index&uid=<?php echo ($users["uid"]); ?>">账号设置</a>-->
				</div>
				<!--span>积分：<?php echo ($users["jifen"]); ?></span-->
				<div style="display:none;"><span>用户等级：
				<?php if($users["member"] == 1): ?>VIP会员 <span style="margin-left:1em;">(<a style='color:blue' href='./index.php?g=App&m=Index&a=index'>VIP会员续费</a>)</span>
				<?php elseif($users["member"] == 2): ?>
					临时会员  <span style="margin-left:1em;">(<a style='color:blue' href='./index.php?g=App&m=Index&a=index'>升级VIP会员</a>)</span>
				<?php else: ?>				
					游客 <span style="margin-left:1em;">(<a style='color:blue' href='/index.php?g=App&m=Index&a=trial&uid=<?php echo ($users["uid"]); ?>'>领取3天试用</a>)</span><?php endif; ?>
				</span></div>
				<div>
                	
                <span style=" color:#fff !important; font-size:14px;">关注时间：<?php echo date('Y-m-d',$wx_info['subscribe_time']); ?>
				
				&nbsp;&nbsp;<!--<a style='color:red' href="./index.php?g=App&m=Member&a=logout">退出登录</a>--></span></div>
				
			</span>
		</div>
        
        <div class="con_ds0" <?php if($etype == 1): ?>style="display: block !important;"<?php endif; ?> >
		<!-- <?php echo ($all_cnt); ?>人脉 -->
		<div class="div_table">
		<table cellpadding="0" cellspacing="0"  style='height:35px;text-align:center;background-color:#fff;border:0px' border=0>
			<tr style="border:0px"  border=0><td style="background-color:#fff; color:#000; font-size:16px; padding-top:6px;border-right:1px solid #929292;" valign="middle">积分总额</td><td style="border-left:1px solid #fff;background-color:#fff;color:#000; font-size:16px; padding-top:6px;" valign="middle">已提积分</td>
            </tr>
            <tr style="border:0px"  border=0><td style="background-color:#fff; color:#fb4444; font-size:14px; text-align:center; padding-bottom:10px;border-right:1px solid #929292;" valign="middle"><?php echo ($users["price"]); ?></td><td style="border-left:1px solid #fff;background-color:#fff;color:#fb4444; font-size:14px;border-right:1px solid #929292; text-align:center; padding-bottom:10px;"  valign="middle"><?php echo ($get_end_price); ?></td>
            </tr>  
		</table>
		</div>
		<div class="cardexplain" style="TEXT-ALIGN: center;color:#000;font-size:14px; display:none;"></div>
		<div class="cardexplain" style="TEXT-ALIGN: center;color:#000;font-size:14px; display:none;">您的邀请人是【<?php echo ($l_name); ?>】</div>
		
        <style type="text/css">
            #gongao{width:auto;height:30px;overflow:hidden;line-height:30px;font-size:13px;font-family:'Microsoft Yahei';background:#DDE5ED;color:#0C77CF;font-weight:bold;}
            #gongao #scroll_begin, #gongao #scroll_end{display:inline}
        </style>
        <script type="text/javascript">
            function ScrollImgLeft(){
                var speed=50;
                var scroll_begin = document.getElementById("scroll_begin");
                var scroll_end = document.getElementById("scroll_end");
                var scroll_div = document.getElementById("scroll_div");
                scroll_end.innerHTML=scroll_begin.innerHTML;
                function Marquee(){
                    if(scroll_end.offsetWidth-scroll_div.scrollLeft<=0)
                        scroll_div.scrollLeft-=scroll_begin.offsetWidth;
                    else
                        scroll_div.scrollLeft++;
                }
                var MyMar=setInterval(Marquee,speed);
                scroll_div.onmouseover=function() {clearInterval(MyMar);}
                scroll_div.onmouseout=function() {MyMar=setInterval(Marquee,speed);}
            }
        </script>
        <div id="gongao">
            <div style="margin-left: auto;margin-right: auto;overflow:hidden;height:24px;width:300px; white-space:nowrap;" id="scroll_div" class="scroll_div">
                <div id="scroll_begin">
                    <?php echo ($info["notification"]); ?></div>
                <div id="scroll_end"></div>
            </div>
            <script type="text/javascript">ScrollImgLeft();</script>
        </div>

		<ul class="function_ul">
        	<li class="cc1_li">
                	<div style="color:#fff;"><a href="<?php echo ($type_a_url); ?>">一级人脉<span style="float:right;color:red;"><?php echo ($users["a_cnt"]); ?></span></a></div>
                    <div style="color:#fff;"><a href="<?php echo ($type_b_url); ?>">二级人脉<span style="float:right;color:red;"><?php echo ($users["b_cnt"]); ?></span></a></div>
                    <div style="color:#fff;"><a href="<?php echo ($type_c_url); ?>">三级人脉<span style="float:right;color:red;"><?php echo ($users["c_cnt"]); ?></span></a></div>
             </li>
        	<li class="cc1">
            	团队成员
            </li>
            <li class="cc2" onClick="$('#tx').click();">
            	<a  href="/index.php?g=App&m=Index&a=tx&etype=2">积分提现</a>
            </li>
            <li class="cc3">
            	微商助手
            </li>
            <li class="cc4 fans">
            	超级人脉
            </li>
            <li class="cc5">
            	<a href="./index.php?g=App&m=Index&a=index">成为CEO</a>
            </li>
            <li class="cc6">
            	<a href="/index.php?g=App&m=Index&a=member&etype=3">推广二维码</a>
            </li>
            <li class="cc7">
            	会员福利
            </li>
            <li class="cc8">
            	工具大全
            </li>
        </ul>
        
		<script>
			$(function(){
				wid1= window.innerWidth;
				$(".cc1 dl").css("width",wid1);
				$(".cc1").click(function(){
					$(".cc1_li").toggle();
				});
			});
		</script>
		
        </div>
        
		<div class="cardexplain" style="display:none;">
			<div class="div_ul" id="all_cnt" ><span><img style='margin-left:5px;' src="../Public/Static/images/arrow_unclick.png">我的人脉</span><span class="bg_total"><?php echo ($all_cnt); ?> 人</span></div>
			<ul class="round">
				<li class="member_cnt" style=""><a href="<?php echo ($type_a_url); ?>"><span><img style="width:20px;height:20px;" src="../Public/Static/images/bullet_blue_expand.png">一级人脉<span style="float:right;color:red;"><?php echo ($users["a_cnt"]); ?></span></span></a></li>
				<li class="member_cnt"><a href="<?php echo ($type_b_url); ?>"><span><img style="width:20px;height:20px;"  src="../Public/Static/images/bullet_blue_expand.png">二级人脉<span style="float:right;color:red;"><?php echo ($users["b_cnt"]); ?></span></span></a></li>
				<li class="member_cnt"><a href="<?php echo ($type_c_url); ?>"><span><img style="width:20px;height:20px;" src="../Public/Static/images/bullet_blue_expand.png">三级人脉<span style="float:right;color:red;"><?php echo ($users["c_cnt"]); ?></span></span></a></li>
			</ul>
		</div>
		
		<div class="cardexplain" style="display:none;">
			
			<div class="div_ul" id="all_buy"><span><img style='margin-left:5px;' src="../Public/Static/images/arrow_unclick.png">人脉订单<span class="bg_total"><?php echo ($all_count_buy); ?> 单</span></span></div>
			<ul class="round">
				<li class="buy_cnt"><span>下单未购买<span style="float:right;color:red;"><?php echo ($all_over_buy); ?></span></span></li>
				<li class="buy_cnt"><span>下单已购买<span style="float:right;color:red;"><?php echo ($all_buy); ?></span></span></li>
			</ul>
		</div>
		
		
		<div class="cardexplain" style="display:none;">
			<div class="div_ul" id="all_price"><span><img style='margin-left:5px;'  src="../Public/Static/images/arrow_unclick.png">我的佣金<span class="bg_total"><?php echo ($users["price"]); ?>元</span></span></div>
			<ul class="round">
	
				<li  class="price_cnt"><span>已提现佣金<span style="float:right;color:red;"><?php echo ($get_end_price); ?></span></span></li>
				<li class="price_cnt"><span>可提现佣金<span style="float:right;color:red;"><?php echo ($users["price"]); ?></span></span></li>
			</ul>
		</div>
		
		<div class="cardexplain" style="display:none;">
			<div class="div_ul" onClick="$('#tx').click();"><span><img style='margin-left:5px;' src="../Public/Static/images/arrow_unclick.png">ATM取款机</span></div>
		</div>
				<div class="cardexplain" style="display:none;">
			<div class="div_ul"><span style="color:#f00;"><img style='margin-left:5px;' src="../Public/Static/images/arrow_unclick.png">★进入粉丝人脉★</span></div>
		</div>
		<!--
		<div class="cardexplain">
			<div class="div_ul" id="memeber_url"><span><img style='margin-left:5px;' src="../Public/Static/images/arrow_unclick.png">分享二维码</span></div>
		
			<span class="memeber_url" style='display:none;'><?php echo ($member_url); ?></span>
		</div>
		-->
		<div class="cardexplain" style="display:none;">
			<a href = './index.php?g=App&m=Index&a=member_top&id=<?php echo ($users["id"]); ?>'><div class="div_ul" id="top_url"><span><img style='margin-left:5px;' src="../Public/Static/images/arrow_unclick.png">销售排行榜</span></div></a>
		</div>
	</div>
	
	<div id="ticket-container" <?php if($etype == 3): ?>style="display: block !important;"<?php endif; ?> >
	
		<h1 align="center" style="margin-top:15px;"><span style="color:#E53333;font-size:20px;">长按以下图片→保存到手机相册</span></h1>
		<h1 align="center" style="margin-bottom:20px;"><span style="color:#E53333;font-size:15px;">分享给别人可以获得高额佣金，满1元即可提现。</span></h1>
		<div align=center><img src='<?php echo ($ticket); ?>' style="width:90%"></div>
	</div>
    
	<div id="tx-container" <?php if($etype == 2): ?>style="display: block !important;  padding-top:5px;"<?php endif; ?> >

		<div class="menu_header" >
			<div class="menu_topbar">
				<div id="menu" class="sort">
					<a href="">充值</a>
				</div>
			</div>
		</div>
		<section class="order">
			
            <form name="txinfoForm" id="txinfoForm" method="post" action="index.php?g=App&m=Index&a=addczorder" >
				<div class="contact-info" style="margin-top:0px; padding-top:0px;">
					<ul>
                        <li class="jifen_show">您当前剩余积分：<span><?php echo ($users["price"]); ?></span></li>
						<li>
							<table style="padding: 0; margin: 0; width: 100%;">
								<tbody>
									<tr>
										<td style="height:70px;" >
											<div class="ui-input-text">
                                            	<span>充值:</span>
												<input id="price" name="price" placeholder="" value="" type="text" class="ui-input-text">
                                              
											</div>
                                        </td>
									</tr>

									<tr>
                                    	<td colspan="2" style="font-size:14px; color:#f00;">注： 单次充值必须大于等于1。</td>
                                    </tr>
								</tbody>
							</table>

							<div class="footReturn">
								<!--<input type="submit" name="" value="提交" class="submit" id="J_weixin" style="width:100%" />-->
                               
                                <a class="submit" href="javascript:;"  onclick="checksubmit()">充&nbsp;&nbsp;&nbsp;&nbsp;值</a>
                                <!--<a id="txshowcard" class="submit" href="javascript:submitCzOrder();">提&nbsp;&nbsp;&nbsp;&nbsp;交</a>-->
							</div>

						</li>
					</ul>
				</div>
			</form>
		</section>

		<!-- 正在提交数据 -->
		<div id="tx-menu-shadow" hidefocus="true"
			style="display: none; z-index: 10;">
			<div class="btn-group"
				style="position: fixed; font-size: 12px; width: 220px; bottom: 80px; left: 50%; margin-left: -110px; z-index: 999;">
				<div class="del" style="font-size: 14px;">
					<img src="../Public/Static/images/ajax-loader.gif" alt="loader">正在提交申请...
				</div>
			</div>
		</div>
		
		<ul class="round">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
					<tr>
						<th>编号</th>
						<th class="cc">金额</th>
						<th class="cc">状态</th>
					</tr>
					<tbody>
					<?php if(is_array($cz_record)): $i = 0; $__LIST__ = $cz_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cz_record): $mod = ($i % 2 );++$i;?><tr>
						<td><?php echo ($cz_record["id"]); ?></td>
						<td class="cc"><?php echo ($cz_record["price"]); ?></td>
						<td class="cc"><?php if($cz_record['status'] == 1): ?><em class="ok">已完成</em><?php else: ?><em class="no">待审核</em><?php endif; ?></td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
			</ul>
	</div>
	
	
	<div id="user-container" style=" display:none;">

		<div class="menu_header">
			<div class="menu_topbar">
				<div id="menu" class="sort ">
					<a href="">查看我的订单</a>
				</div>
			</div>
		</div>

		<div>
			<ul class="round" style="margin:0;padding:0;border-radius:0;border:0px;border-bottom:1px solid #C6C6C6">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
					<tr>
						<td> <span>订单详情</span> <span style='float:right'><a href='javascript:$("#ticket").click();' style='color:red;'>获取推广二维码>>></a></span> </td>
					</tr>
				</table>
			</ul>
		</div>
		
		<div class="cardexplain">
			<div id="page_tag_load" hidefocus="true"
				style="display: none; z-index: 10;">
				<div class="btn-group"
					style="position: fixed; font-size: 12px; width: 220px; bottom: 80px; left: 50%; margin-left: -110px; z-index: 999;">
					<div class="del" style="font-size: 14px;">
						<img src="../Public/Static/images/ajax-loader.gif" alt="loader">正在获取订单...
					</div>
				</div>
			</div>
			<ul class="round"  id="orderlistinsert" style='color:#000;font-size:12px;'>
				<!--插入订单ul-->
			</ul>
		</div>
	</div>

	<div class="footermenu"><!--./index.php?g=App&m=Index&a=index-->
		<ul>
			<li ><a href="/index.php?g=App&m=Index&a=member&etype=1"> <img src="../Public/Static/images/21.png">
					<p>首  页</p>
			</a></li>
			<li><a href="/index.php?g=App&m=Index&a=yunorder"> <img src="../Public/Static/images/22.png">
					<p>微商云订单</p>
			</a></li>
			<li id="member" style=" position:relative;"><a href="javascript:void(0)"><img src="../Public/Static/images/23.png">
					<p>客  服</p>
                    <img class="fefu_img" style="  position: absolute; bottom: 51px; width: 200%; height: auto; display:none; left: -50%;" src="../Public/Static/images/kefu.jpg" />
			</a></li>
			<li><a href="/index.php?g=App&m=Index&a=member&etype=3"> <img src="../Public/Static/images/24.png">
					<p>我的二维码</p>
			</a></li>
		</ul>
	</div>
	<script>
	window.onload=function(){
		if($_GET['page_type']=='order')
		{
			user();
		}
	}
	$(function(){
		$("#member").click(function(){
		$(".imgshow").toggle();
		});
		
		$(".closebtn").click(function(){
			$(".imgshow").toggle();
		});
	})
	function checksubmit(){
		var price = $('#price').val();
		if(!isNaN(price) && price >= 1 ){
			document.getElementById('txinfoForm').submit();
		}else{
			alert("请填写正确数字");
			return false;	
		}
	}
	
	</script>
    
         <div class="imgshow">
				<span class="close"><a href="javascript:;" class="closebtn"><img src="../Public/Static/images/closebtn.png"></a></span>
				<img src="../Public/Static/images/kefu.jpg" id="showimg" style="box-shadow: 0px 0px 3px #fff" ;="">
				<div class="fontcss">
					<font style="color:#ea222e;margin-top: 3px;">1、长按识别二维码<br>2、添加时请注明来自：<b>人脉</b></font>
				</div>
		</div>
    
</body>
</html>