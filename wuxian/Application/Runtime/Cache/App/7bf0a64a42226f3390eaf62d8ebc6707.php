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
<link href="../Public/Static/css/shopnav.css" rel="stylesheet" type="text/css">
<!--<link rel="stylesheet" type="text/css" href="../Public/Static/css/style2.css">-->
<style>
	.content {
		background-color: #ffffff;
	} 
    #gongao{width:auto;height:30px;overflow:hidden;line-height:30px;font-size:13px;font-family:'Microsoft Yahei';background:#DDE5ED;color:#0C77CF;font-weight:bold;}
    #gongao #scroll_begin, #gongao #scroll_end{display:inline}
      
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
        	<img src="../Public/Static/images/guanggao.jpg" width="100%">
    </div>
    
    <div id="gongao">
            <div style="margin-left: auto;margin-right: auto;overflow:hidden;height:24px;width:300px; white-space:nowrap;" id="scroll_div" class="scroll_div">
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

	<div id="cart-container" style="display: block !important;" >
	
<style>
.title1{font-size:18px;color:#fff;position:relative;line-height:34px;background:#c00;text-align:center;}
.title1 a{background:#fff;color:#c00;position:absolute;left:10px;top:5px;padding:0 15px;line-height:22px;border:1px solid #fff;border-radius: 5px;font-size:14px;}
.info-title{height:45px;line-height:45px;margin-top:15px;}
/*.info-title span{height:40px;line-height:40px; }*/
.info-title p{float:left;}
</style>
		<!--<img style="width:100%;" src='<?php echo ($config_good_pic); ?>'>-->
    
		<section class="order">
			  <!---商品---->
		<div class="container " style="min-height: 150px;">
			<div class="content "  style="border-bottom:1px solid #eee">
				<ul class=" sc-goods-list clearfix " >

					<!-- 商品区域 -->
					<!-- 展现类型判断 -->
			<?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goodsvo): $mod = ($i % 2 );++$i;?><li class="js-goods-card goods-card small-pic card ">
						<a href="./index.php?g=App&m=Index&a=goods_detail&goods_id=<?php echo ($goodsvo["id"]); ?>" class="js-goods link clearfix"  data-goods-id="<?php echo ($goodsvo["id"]); ?>" title="<?php echo ($goodsvo["name"]); ?>">
							
                            <div class="photo-block clearfix " style="background-color: rgb(255, 255, 255);">
								<img class="goods-photo js-goods-lazy" src="__PUBLIC__/Uploads/<?php echo ($goodsvo["image"]); ?>" style="display: block;" onClick="showDetail('<?php echo ($goodsvo["id"]); ?>');">
							</div>
							
                            <div class="clearfix info-title" >
            <!--                	<span style="font-size:18px;font-weight:bold;padding-left:10px;width:50%;" ><?php echo ($goodsvo["name"]); ?></span>
                                <span style="padding:0px 20px 0px 30px;font-size:16px;color:#731818;font-weight:700;">￥<?php echo ($goodsvo["price"]); ?></span>
                                <span style="width:150px;height:45px;background-color:#731818;color:#FFF;" > 立即抢购</span>-->
								<p class=" goods-title " style="font-size:18px;font-weight:bold;padding-left:10px;width:35%;height:30px;" ><?php echo ($goodsvo["name"]); ?></p>
								<p class="goods-sub-title c-black " style="padding:0px 13px 0px 13px;font-size:16px;color:#731818;font-weight:700;height:40px">￥<?php echo ($goodsvo["price"]); ?></p>
								<p class="goods-price" style="width:100px;height:35px;background-color:#731818;color:#FFF;text-align:center;line-height:35px;float:right;margin-right:20px;">立即抢购</p>
							</div>
                            	
						</a>
					</li><?php endforeach; endif; else: echo "" ;endif; ?>

				</ul>
			</div>
		</div>
	</div>
    <!---商品---->
            <div class="orderlist">
          
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

		</section>

    
    
	</div>

	<div class="footermenu">
		<ul>
			<li><a href="/index.php?g=App&m=Index&a=member&etype=1"> <img src="../Public/Static/images/21.png">
					<p>首  页</p>
			</a></li>
			<li><a href="/index.php?g=App&m=Index&a=yunorder" class="active"> <img src="../Public/Static/images/22.png">
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