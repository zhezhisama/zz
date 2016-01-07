// JavaScript Document
$(document).ready(function(e) {
	$("#rightButton").css("right", "0px");
	
    var button_toggle = true;
	$(".right_ico").live("mouseover", function(){
		var tip_top;
		var show= $(this).attr('show');
		var hide= $(this).attr('hide');
		tip_top = show == 'tel' ?  135 :  65;
		button_toggle = false;
		$("#right_tip").css("top" , tip_top).show().find(".flag_"+show).show();
		$(".flag_"+hide).hide();
		
	}).live("mouseout", function(){
		button_toggle = true;
		hideRightTip();
	});
	
	
	$("#right_tip").live("mouseover", function(){
		button_toggle = false;
		$(this).show();
	}).live("mouseout", function(){
		button_toggle = true;
		hideRightTip();
	});
	
	function hideRightTip(){
		setTimeout(function(){		
			if( button_toggle ) $("#right_tip").hide();
		},0);
	}
	
	$("#backToTop").live("click", function(){
		var _this = $(this);
		$('html,body').animate({ scrollTop: 0 }, 500 ,function(){
			_this.hide();
		});
	});

	$(window).scroll(function(){
		var htmlTop = $(document).scrollTop();
		if( htmlTop > 0){
			$("#backToTop").show();	
		}else{
			$("#backToTop").hide();
		}
	});
});


$(document).ready(function(){
	var topMain=$(".header").height()//是头部的高度加头部与nav导航之间的距离
	var nav=$(".navbox");
	$(window).scroll(function(){
		if ($(window).scrollTop()>topMain){//如果滚动条顶部的距离大于topMain则就nav导航就添加类.nav_scroll，否则就移除
			nav.addClass("nav_scroll");
		}else{
			nav.removeClass("nav_scroll");
		}
	});
})
