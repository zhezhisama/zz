$(function(){
// 文本框活的焦点事件
	var $searchText=$(".nSearchText");
	if($(".nSearchText").length){
	if($searchText.val()==$searchText[0].defaultValue){
		$searchText.css({"color":"#ccc"})
		}else{$searchText.css({"color":"#ccc"})
		}}
	$(".inputText").focus(function(){
	var $defaultText=$(this).val();
	if($defaultText==this.defaultValue){
		$(this).css({"color":"#000"})
		.val("");}
	})
	$(".inputText").blur(function(){
		var $defaultText=$(this).val();
		if($defaultText==""){
			$(this).val(this.defaultValue)
			.css({"color":"#ccc"});
			}
		})
//banner切换
var lenBanner=$(".bannerImg li").length;
var indexBanner=0;
var timerBanner;
$(".bannerBtn>span").mouseover(function(){
	indexBanner=$(".bannerBtn span").index(this);
	showBanner(indexBanner);
	//alert(indexBanner)
	}).eq(0).mouseover()
$(".bannerImg").hover(function(){
	clearInterval(timerBanner)
	},function(){
		timerBanner=setInterval(function(){
			showBanner(indexBanner);
			indexBanner++;
			if(indexBanner==lenBanner){indexBanner=0}
			},5000)
		}).trigger("mouseleave")	
//婚庆公司展示各行变色
$(".scrollListColor ul li a:odd").addClass("cGreen");	
//选项卡
$(".searchForm_user .sendcardTop li").click(function(){
	var indexCard=$(".searchForm_user .sendcardTop li").index(this);
	$(this).addClass("optionYes").siblings().removeClass("optionYes")
	$(".cardTextBox>div").hide()
	.eq(indexCard).show();
	})
//关闭层
$("span.closeBtn").click(function(){
	$(this).slideUp().parent(".couldCloseBox").slideUp("500");
	})
//折叠层
$("span.foldBtn").toggle(function(){
	$(this).text("+展开");
	$(this).parent().parent().find("div.foldBox").slideUp("500");
	},function(){
	$(this).text("+收起");
	$(this).parent().parent().find("div.foldBox").slideDown("500");	
		})
//分享
$(".imgLinkBox a").hover(function(){
	$(this).find("span.bigBlack,span.smallBlack").css("opacity","0");
	}
,function(){
	$(this).find("span.bigBlack,span.smallBlack").css("opacity","0.7");
	})
//所有滚动调用	
setInterval('scrollCode(".scrollList")',3000);
setInterval('scrollCode(".scrollListColor")',3000);
//商铺图片
$("img.caseImg_shop").hover(function(){
	$(this).css({"opacity":"0.9"});
	},function(){
	$(this).css("opacity","1.0");
		})
//向上滚动按钮
$(window).scroll(function(){
	if($(this).scrollTop()>=200){
		$("span.topLink_shop").slideDown(200);
		$("span.topLink_shop").css({"_bottom":$(window).scrollTop()})
		}else{
			$("span.topLink_shop").slideUp(200);
			}
	})
$("span.topLink_shop").click(function(){
	$("html,body").animate({scrollTop:0},300)
	})
var win_width= $(window).width();      //窗口宽度
var content_width= $('.content_shop').width();     //容器宽度
var topbtn_width= $('span.topLink_shop').width(); //按钮宽度	
var topbtn_posi = ([win_width - content_width ]/2 - topbtn_width - 10);
$("span.topLink_shop").css({"right":topbtn_posi})

//商铺案例滚动
if($(".caseTcardImgBig img").width()>600){
	$(".caseTcardImgBig img").width(600);
	}
var lenCase=$(".caseScrollBoxH ul li").length;
var caseLiW=$(".caseScrollBoxH ul li").width();
$(".scrollBtnRight").click(function(){var sLeft=$(".caseScrollBoxH ul").css("left");
if($(".caseScrollBoxH ul:animated").length==0){
if(!$(this).is(".scrollBtnRightG")){
	$(".scrollBtnLeft").removeClass("scrollBtnLeftG");
	}
if(sLeft==-(lenCase-5)*(caseLiW+10)+"px")
{ $(this).addClass("scrollBtnRightG");$(".scrollBtnLeft").removeClass("scrollBtnLeftG");
$(".caseScrollBoxH ul").animate({left:"-="+(+caseLiW+10)},500);
}else if(sLeft==-(lenCase-4)*(caseLiW+10)+"px"){}
else {$(this).removeClass("scrollBtnRightG");$(".caseScrollBoxH ul").animate({left:"-="+(+caseLiW+10)},500);}}
})	
$(".scrollBtnLeft").click(function(){var sLeft=$(".caseScrollBoxH ul").css("left");
if($(".caseScrollBoxH ul:animated").length==0){
if(!$(this).is(".scrollBtnLeftG")){
	$(".scrollBtnRight").removeClass("scrollBtnRightG");
	}
if(sLeft==-(caseLiW+10)+"px" ){$(this).addClass("scrollBtnLeftG");$(".scrollBtnRight").removeClass("scrollBtnRightG");$(".caseScrollBoxH ul").animate({left:"+="+(+caseLiW+10)},500);}else if(sLeft=="0px"){}else {$(this).removeClass("scrollBtnLeftG");$(".caseScrollBoxH ul").animate({left:"+="+(+caseLiW+10)},500);}}})

$(".caseScrollBoxH ul li").click(function(){
	var eqImg_shop=$(".caseScrollBoxH ul li").index(this); 
	var $imgSrc=$(this).find("img").attr("data-original");
	var $imgName=$(this).find("h1").text();
	var $imgText=$(this).find("p").text();
	var $bImgWidth=$(this).find("img").attr("data-width");
	$(".caseScrollBoxH ul").css({"position":"static"});
	$(".caseTcardImgBig img").attr("src",$imgSrc);
	$(".caseTcardName").text($imgName);
	$(".caseTcardDetail").text($imgText);
	$(".caseScrollBoxH ul li img").removeClass("showImgCase");
	$(this).find("img").addClass("showImgCase");
	$("#eqImg_shop").html(eqImg_shop+1);
	$(".caseTcardImgBig img").width($bImgWidth);
	if($bImgWidth>600){
		$(".caseTcardImgBig img").width(600);
		}
	$(".caseScrollBoxH ul").css({"position":"absolute"});	
})
//商铺相册编辑	
	var nsu=$('.pimgxces8');
	var chdsu=$('.pimgxces5');
	nsu.each(function(){
		var indexCount=$('.pimgxces8').index(this);
		$(chdsu).eq(indexCount).attr("id", indexCount);
		$(nsu).eq(indexCount).mouseover(function(){
			
		 $('#'+indexCount).css("display","inline");
		})
		$(nsu).eq(indexCount).mouseout(function(){
		 $('#'+indexCount).css("display","none");
		})	
		})		

})

//函数
//banner切换动画
function showBanner(indexBanner){
	var HBanner=$(".bannerList").height();
	$(".bannerImg").stop(true,false).animate({top:-indexBanner*HBanner},100)
	$(".bannerBtn>span").removeClass("sBanBtn")
	.eq(indexBanner).addClass("sBanBtn")
	}
//新闻滚动动画（中标项目和婚庆公司展示）
function scrollCode(obj){
	var $scrollContent=$(obj).find("ul");
	var $scrollElement=$scrollContent.find("li");
	var scrollHeight=$scrollElement.height();
        $(obj).find("ul:first").animate({"marginTop":"-"+scrollHeight },500,function(){
        	$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
	})
	}
//表单默认文字
function clearDefaultText (el,message)
{var obj = el;if(typeof(el) == "string")obj = document.getElementByIdx_x(id);if(obj.value == message){obj.value = "";}obj.onblur = function()
{if(obj.value == "")
{   obj.value = message;}}}


