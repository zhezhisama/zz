<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>超级无限人脉</title>
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="../Public/Static/css/foods.css?t=333" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../Public/Static/js/jquery.min.js"></script>
<script type="text/javascript" src="../Public/Static/js/wemall.js?222"></script>
<script type="text/javascript" src="../Public/Static/js/alert.js"></script>
<script type="text/javascript" src="../Public/Static/js/area.js"></script>
<script type="text/javascript">
var appurl = '__APP__';
var rooturl = '__ROOT__';
</script>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.call('hideOptionMenu');
});
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
	<div id="menu-container" style="display: none;">
		<div class="menu_header">
			<div class="menu_topbar">
				<div id="menu" class="sort sort_on">
					<a href=""><?php echo ($info["name"]); ?></a>
					<ul>
						<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menuid): $mod = ($i % 2 );++$i;?><li><a href="javascript:showProducts('<?php echo ($menuid["id"]); ?>')"><?php echo ($menuid["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						<li><a href="javascript:showAll()">所有商品</a></li>
					</ul>
				</div>
				<!--a class="head_btn_right" href="javascript:showMenu();"><i
					class="menu_header_home"></i> </a-->
			</div>
		</div>
		
 <!--
		<div class="gonggao">
			<div class="hot">
				<strong>公告</strong>
			</div>
			<div class="content"><?php echo ($info["notification"]); ?></div>
		</div>-->


		<section class="menu">
			<section class="list listimg">
				<dl>
					<!--dt>菜单</dt-->
					<div class="ccbg">
						<?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goodsvo): $mod = ($i % 2 );++$i;?><dd menu="<?php echo ($goodsvo["menu_id"]); ?>" style="display:none;">
							<div class="tupian">
								<img src="__PUBLIC__/Uploads/<?php echo ($goodsvo["image"]); ?>"
									onclick="showDetail('<?php echo ($goodsvo["id"]); ?>');"> <a
									href="javascript:doProduct('<?php echo ($goodsvo["id"]); ?>','<?php echo ($goodsvo["name"]); ?>','<?php echo ($goodsvo["price"]); ?>');" class="add">
									<p class="dish2"><?php echo ($goodsvo["name"]); ?></p>
									<p class="price2"><?php echo ($goodsvo["price"]); ?>元/份</p>
									<p>
										<del><?php echo ($goodsvo["old_price"]); ?>元/份</del>
									</p></a>
							</div>
							<a href="javascript:doProduct('<?php echo ($goodsvo["id"]); ?>','<?php echo ($goodsvo["name"]); ?>','<?php echo ($goodsvo["price"]); ?>');" id="<?php echo ($goodsvo["id"]); ?>" class="reduce" style="display: block;"><b class="ico_reduce">减一份</b></a>
						</dd>
						
						<script>
						window.onload=function(){
							showDetail('<?php echo ($goodsvo["id"]); ?>');
							doProduct('<?php echo ($goodsvo["id"]); ?>','<?php echo ($goodsvo["name"]); ?>','<?php echo ($goodsvo["price"]); ?>');
							$('#cart').click();
						}
						</script><?php endforeach; endif; else: echo "" ;endif; ?>
						
					</div>
				</dl>
			</section>

			
			<div id="mcover" onClick="document.getElementById('mcover').style.display='';">
				<div id="Popup" style="display: block;">
					<div class="imgPopup">
						<img id="detailpic" src="">
						<!--h3 id="detailtitle"></h3-->
						<p class="jianjie" id="detailinfo"></p>
					</div>
				</div>
				<a class="close" onClick="document.getElementById('mcover').style.display='';" style="display: none;">X</a>
			</div>

		</section>
	</div>
    
    <div style="width:100%; height:150px; background:#f00;">
        	
    </div>
    <div id="gongao">
            <div style="margin-left: auto;margin-right: auto;overflow:hidden;height:24px;width:90%; white-space:nowrap;" id="scroll_div" class="scroll_div">
                <div id="scroll_begin">
                    <?php echo ($info["notification"]); ?></div>
                <div id="scroll_end"></div>
            </div>
            <script type="text/javascript">
				ScrollImgLeft();
				
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
     </div>    

	<div id="cart-container" <?php if($etype == 1): ?>style="display: block !important;"<?php endif; ?>>
		<div class="menu_header">
			<div class="menu_topbar">
				<div id="menu" class="sort">
					<a href="">购买</a>
				</div>
			</div>
		</div>
<style>
.title1{font-size:18px;color:#fff;position:relative;line-height:34px;background:#c00;text-align:center;}
.title1 a{background:#fff;color:#c00;position:absolute;left:10px;top:5px;padding:0 15px;line-height:22px;border:1px solid #fff;border-radius: 5px;font-size:14px;}

</style>
		<!--<img style="width:100%;" src='<?php echo ($config_good_pic); ?>'>-->
		<div class="title1" style="display:none;">
		<a href="<?php echo U('member');?>">返回</a>
		购买会员
		</div>

		<section class="order">
			<div class="orderlist">
				<ul>
					<li>
						<label onClick="setProduct('<?php echo ($goods[0]["id"]); ?>','<?php echo ($goods[0]["name"]); ?>','<?php echo ($goods[0]["price"]); ?>' , 1);">
							<input type="radio" value="0" name="buy_num" class="radioBox1"  checked="checked" ><span style="font-size:20px; color:#000;">专享VIP会员：</span><span style="float:right;">共计：￥<i style="color:#f00; margin:0 5px;"><?php echo ($goods[0]["price"]); ?></i>元</span>
						</label>
					</li>
                    <li>
						<label onClick="setProduct('<?php echo ($goods[1]["id"]); ?>','<?php echo ($goods[1]["name"]); ?>','<?php echo ($goods[1]["price"]); ?>' , 1);">
							<input type="radio" value="0" name="buy_num" class="radioBox1"  checked="checked" ><span style="font-size:20px; color:#000;"><?php echo ($goods[1]["name"]); ?>：</span><span style="float:right;">共计：￥<i style="color:#f00; margin:0 5px;"><?php echo ($goods[1]["price"]); ?></i>元</span>
						</label>
					</li>
                    
					<li style="display:none;">
						<label onClick="setProduct('<?php echo ($goodsvo["id"]); ?>','<?php echo ($goodsvo["name"]); ?>','<?php echo ($goodsvo["price"]); ?>' , 3);">
							<input type="radio" value="1" name="buy_num" class="radioBox3"  >购买时长：3个月 = <?php echo ($goods[0][price]*3); ?>元(马上涨价)<span style='color:#ea222e;'>(送1个月)</span>
						</label>
					</li>
					<li style="display:none;">
						<label onClick="setProduct('<?php echo ($goodsvo["id"]); ?>','<?php echo ($goodsvo["name"]); ?>','<?php echo ($goodsvo["price"]); ?>' , 6);">
							<input type="radio" value="2" name="buy_num" class="radioBox6" >购买时长：6个月 = <?php echo ($goods[0][price]*6); ?>元(马上涨价) <span style='color:#ea222e;'>(送2个月)</span>
						</label>
					</li>
				</ul>
				<ul id="ullist" style="display:none;">
					
				</ul>
				
				<ul>
					<!--li class="ccbg2" >今日限购剩余：<span style='color:red'><?php echo ($buy_cnt); ?></span>份</li-->
					<?php if($buy_cnt == 0): ?><li class="ccbg2" style='color:red'>很抱歉，今天的货已经卖完了，明天请早吧</li><?php endif; ?>
				</ul>
				<?php if($buy_cnt != 0): ?><ul id="cartinfo" style="margin-top:-46px;background:none;margin-right:100px;">
					<!--<dt>购物车总计</dt>-->
					<li class="ccbg2" id="emptyLii" style=" display:none;">已选：<span id="totalNum">0</span>份　共计：<span id="totalPrice">0</span>元</li>
				</ul><?php endif; ?>
				<!--div class="twobtn">
					<div class="footerbtn">
						<a class="del right3" href="javascript:home();">选购</a>
					</div>
					<div class="footerbtn">
						<a class="submit left3" onclick="clearCache()">清空</a>
					</div>
					<div class="clr"></div>
				</div-->
			</div>
			<?php if($buy_cnt != 0): ?><form name="infoForm" id="infoForm" method="post" action="">
				<div class="contact-info">
					<ul>
                    	<li>
							<table style="padding: 0; margin: 0; width: 100%;">
								<tbody>
									<tr>
										<!--td width="90px"><label for="name" class="ui-input-text"><span style="color:red">*</span>联系人：</label></td-->
										<td>
											<div class="ui-input-text">
												<input id="name" name="name" placeholder="请输入您的姓名" style="width:96% !important;" value="<?php echo ($users["username"]); ?>" type="text" class="ui-input-text" >
											</div>
										</td>
									</tr>

									<tr>
										<!--td width="90px"><label for="phone" class="ui-input-text"><span style="color:red">*</span>联系电话：</label></td-->
										<td>
											<div class="ui-input-text">
												<input id="phone" name="phone" placeholder="请输入您的手机号码" style="width:96% !important;"  value="<?php echo ($users["phone"]); ?>" type="tel"
													class="ui-input-text">
											</div>
										</td>
									</tr>
									<!--tr>
										<td width="80px"><label for="pay" class="ui-input-text">支付方式：</label></td>
										<td colspan="2"><select name="pay" class="selectstyle"
											id="select1">
												<option value="0">货到付款</option>
												<?php if($alipay == 1): ?><option value="1">支付宝在线支付</option><?php endif; ?>
												<option value="2">微信支付</option>
										</select></td>
									</tr-->
									<!--tr>
										<td width="90px"><label for="weixin" class="ui-input-text">微信号：</label></td>
										<td>
											<div class="ui-input-text">
												<input id="weixin" name="weixin" placeholder="" value="<?php echo ($users["weixin"]); ?>" type="text"
													class="ui-input-text">
											</div></td>
									</tr-->
									<tr style="display:none;">
										<td width="90px"><label for="address"
											class="ui-input-text"><span style="color:red">*</span>联系地址：</label></td>
										<td>	<input teyp="hidden" name="s_province" value="省">
												<input teyp="hidden" name="s_city" value="市">
												<input teyp="hidden" name="s_county" value="区县">
												<select id="s_province" name="s_province"></select>  
												<select id="s_city" name="s_city" ></select>  
												<select id="s_county" name="s_county"></select>
												<script type="text/javascript">_init_area();</script>
												<div id="show"></div>
												
												<textarea id="address" name="address" placeholder=""
												value="" class="ui-input-text">地址</textarea>
										</td>
									</tr>
									
									<script type="text/javascript">
									var Gid  = document.getElementById ;
									var showArea = function(){
										Gid('show').innerHTML = "<h3>省" + Gid('s_province').value + " - 市" + 	
										Gid('s_city').value + " - 县/区" + 
										Gid('s_county').value + "</h3>"
																}
									Gid('s_county').setAttribute('onchange','showArea()');
									</script>

									<tr style="display:none;">
										<td width="90px"><label for="note" class="ui-input-text">备注：</label></td>
										<td><textarea name="note" placeholder=""
												class="ui-input-text"></textarea></td>
									</tr>
								</tbody>
							</table>
							<div>
								<label style="font-size: 12px;line-height: 45px;"><input type="checkbox" checked="checked" id="agreement" name="agreement"> 已阅读并同意<a href="/about/contract.html" style="color:#ea222e;">《VIP会员协议》</a>和<a href="/about/user_contract.html" style="color:#ea222e;">《平台服务协议》</a></label>								
							</div>
							<div class="footReturn">
								<a id="showcard" class="submit" href="javascript:submitOrder();">微信支付</a>
							</div>

						</li>
					</ul>
				</div>
			</form>
            
            
            <div class="renav" style="display:none;">
				
                <ul>
                	<li>
                    	<dl>
                        	<dd>张*</dd>
                            <dd class="mid">177****5578已成为会员</dd>
                            <dd>5分钟前</dd>
                        </dl>
                    </li>
                    <li>
                    	<dl>
                        	<dd>李*</dd>
                            <dd class="mid">158****5938已成为会员</dd>
                            <dd>5分钟前</dd>
                        </dl>
                    </li>
                    <li>
                    	<dl>
                        	<dd>张*</dd>
                            <dd class="mid">138****9865已成为会员</dd>
                            <dd>5分钟前</dd>
                        </dl>
                    </li>
                    <li>
                    	<dl>
                        	<dd>王*</dd>
                            <dd class="mid">189****1452已成为会员</dd>
                            <dd>5分钟前</dd>
                        </dl>
                    </li>
                    <li>
                    	<dl>
                        	<dd>朱*</dd>
                            <dd class="mid">131****5478已成为会员</dd>
                            <dd>5分钟前</dd>
                        </dl>
                    </li>
                </ul>
                
                            	
            </div>
            <script>
	
$(function(){ 

	var $this = $(".renav"); 
	
	var scrollTimer; 
	
	$this.hover(function(){ 
		clearInterval(scrollTimer); 
	},function(){ 
		scrollTimer = setInterval(function(){ 
		scrollNews( $this ); 
	}, 2000 ); 
	}).trigger("mouseout");
	
});


function scrollNews(obj){ 
	var $self = obj.find("ul:first"); 
	var lineHeight = $self.find("li:first").height();
	$self.animate({ "margin-top" : -lineHeight +"px" },600, function(){ 
		$self.css({"margin-top":"0px"}).find("li:first").appendTo($self); 
	}) 
} 

</script><?php endif; ?>
		</section>

		<!-- 正在提交数据 -->
		<div id="menu-shadow" hidefocus="true"
			style="display: none; z-index: 10;">
			<div class="btn-group"
				style="position: fixed; font-size: 12px; width: 220px; bottom: 80px; left: 50%; margin-left: -110px; z-index: 999;">
				<div class="del" style="font-size: 14px;">
					<img src="../Public/Static/images/ajax-loader.gif" alt="loader">正在提交订单...
				</div>
			</div>
		</div>

	</div>

	<div id="user-container" <?php if($etype == 1): ?>style="display: block !important;"<?php endif; ?> >

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
						<td> <span>订单详情</span> <span style='float:right'><a href='./index.php?g=App&m=Index&a=index_info'>继续购物>>></a></span> </td>
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

	<div class="footermenu" style='display:none;'>
		<ul>
			<li><a class="active" href="./index.php?g=App&m=Index&a=index_info"> <img
					src="../Public/Static/images/home.png">
					<p>首页</p>
			</a></li>

			<li id="cart"><a href="javascript:void(0);"> <span class="num" id="cartN2"  style="display:none;">0</span> <img
					src="../Public/Static/images/cart.png">
					<p>购买</p>
			</a></li>
			<li id="user"><a href="javascript:void(0);"> <img src="../Public/Static/images/user.png">
					<p>我的</p>
			</a></li>
		</ul>
	</div>
	
	<div class="footermenu"><!--./index.php?g=App&m=Index&a=index-->
		<ul>
			<li><a href="/index.php?g=App&m=Index&a=member&etype=1" <?php if($etype == 1): ?>class="active"<?php endif; ?> > <img src="../Public/Static/images/21.png">
					<p>首  页</p>
			</a></li>
			<li><a href="javascript:void(0)"> <img src="../Public/Static/images/22.png">
					<p>微商云订单</p>
			</a></li>
			<li id="member" style=" position:relative;"><a href="javascript:void(0)"><img src="../Public/Static/images/23.png">
					<p>客  服</p>
			</a></li>
			<li><a href="/index.php?g=App&m=Index&a=member&etype=3" <?php if($etype == 3): ?>class="active"<?php endif; ?> > <img src="../Public/Static/images/24.png">
					<p>我的二维码</p>
			</a></li>
		</ul>
	</div>
    
    <div class="imgshow">
				<span class="close"><a href="javascript:;" class="closebtn"><img src="../Public/Static/images/closebtn.png"></a></span>
				<img src="../Public/Static/images/kefu.jpg" id="showimg" style="box-shadow: 0px 0px 3px #fff" ;="">
				<div class="fontcss">
					<font style="color:#ea222e;margin-top: 3px;">1、长按识别二维码<br>2、添加时请注明来自：<b>人脉</b></font>
				</div>
		</div>
    
    <script>
	$("#member").click(function(){
		$(this).find(".fefu_img").toggle();
	});
	
		$(function(){
		$("#member").click(function(){
		$(".imgshow").toggle();
		});
		
		$(".closebtn").click(function(){
			$(".imgshow").toggle();
		});
	})
	
	</script>
</body>
</html>