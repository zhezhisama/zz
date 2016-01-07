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
<style>
.photo-block img{width:100%;}
</style>
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

		<div style="width:100%;">
        	<img src="../Public/Static/images/goods_info.jpg" width="100%">
    	</div>
		<div style="width:100%;height:50px;line-height:50px;">
        	<span style="font-weight:bold;padding-left:30px;display:inline-block;"><?php echo ($goods["name"]); ?></span> <span  style="float:right;margin-right:30px;display:inline-block;">总计：￥<em id="totalPrice"><?php echo ($goods["price"]); ?></em>元</span>
        </div>
		<section class="order">
		
        <div id="cart-container" style="display: block !important;" >
            <!---商品---->
            <div class="container " style="min-height: 150px;">
                <div class="photo-block clearfix " >
                    <?php echo ($goods["detail"]); ?>
                </div>

            </div>
		</div>
            
          <!--  <div class="orderlist">
				<ul>
					<?php echo ($goods["detail"]); ?>
					<?php echo ($goods["price"]); ?>
                    <?php echo ($goods["id"]); ?>
                    <?php echo ($goods["name"]); ?>
                 
				</ul>
			
			</div>-->
            
            <ul id="ullist" style="display:none;">
				<li>
                <span class="goods_id"><?php echo ($goods["id"]); ?></span>
                <span name="title"><?php echo ($goods["name"]); ?></span>
                <span class="price"><?php echo ($goods["price"]); ?></span>	
                <span class="count">1</span>
				</li>
            </ul>
           
			<form name="infoForm" id="infoForm" method="post" action="" >
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
								<!--	tr>
										<td width="90px"><label for="weixin" class="ui-input-text">微信号：</label></td>
										<td>
											<div class="ui-input-text">
												<input id="weixin" name="weixin" placeholder="" value="2" type="text"
													class="ui-input-text">
											</div></td>
									</tr-->
									<tr style="display:none;">
										<!--<td width="90px"><label for="address"
											class="ui-input-text"><span style="color:red">*</span>联系地址：</label></td>-->
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
										<td><textarea name="note" placeholder="" class="ui-input-text"></textarea></td>
									</tr>
								</tbody>
							</table>
							<div>
								<label style="font-size: 12px;line-height: 45px;"><input type="checkbox" checked="checked" id="agreement" name="agreement"> 已阅读并同意<a href="/about/user_contract.html" style="color:#ea222e;" name="pay">《平台服务许可协议》</a></label>								
							</div>
							<div class="footReturn">
								<a id="showcard" class="submit" href="javascript:;" onClick="submitOrder();">立即购买</a>
							</div>

						</li>
					</ul>
				</div>
			</form>
      <div class="renav" >
				
                <ul>
                	<?php if(is_array($user_arr)): $i = 0; $__LIST__ = $user_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user_arr): $mod = ($i % 2 );++$i;?><li>
                    	<dl>
                        	<dd><?php echo ($user_arr["username"]); ?></dd>
                            <dd class="mid"><?php echo ($user_arr["phone"]); ?>已成为代理</dd>
                            <dd><?php echo ($user_arr["time"]); ?></dd>
                        </dl>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                
                            	
            </div>
            <div id="show_pay"  onclick="hide0()" style="    position: fixed;right: 0px;bottom: 223px;width: 50px; height: 50px;background: rgb(86,209,118);font-size: 12px; color: #fff  !important; text-align: center;line-height: 50px;">
            	<a href="#pay" style="color: #fff;">购买</a>
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

</script>
            
</if>
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
	
	<div class="footermenu" id="footer_menu"><!--./index.php?g=App&m=Index&a=index-->
		<ul>
			<li><a href="/index.php?g=App&m=Index&a=member&etype=1"> <img src="../Public/Static/images/21.png">
					<p>首  页</p>
			</a></li>
			<li><a href="/index.php?g=App&m=Index&a=yunorder"> <img src="../Public/Static/images/22.png">
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
			
		//点击让 购买隐藏，滑动到上面又显示
		
		$(window).scroll(function() {
			hide0();
		});
		
		function hide0()
		{
			height = $("#footer_menu").offset().top;
			if(height > 5498){
				$("#show_pay").hide();
			}else{
				$("#show_pay").show();
			}
			
			
		}
		
	
	
	</script>
</body>
</html>