var curIndex = 0;
var time = 800;
var slideTime = 6000;
var adTxt = $("#banner_img>li>div>.ad_txt");
var adImg = $("#banner_img>li>div>.ad_img");
var int = setInterval("autoSlide()", slideTime);
$("#banner_ctr>ul>li[class!='first-item'][class!='last-item']").mouseover(function () {
 	var ct=$(this).index("#banner_ctr>ul>li[class!='first-item'][class!='last-item']");
	 
	if(ct==1 || ct==0){
	  ct=0;
	}
	if(ct==2 || ct==3){
	  ct=1;
	}
	 
	if(ct==5 || ct==4){
	  ct=2;
	}
       if(ct==6 || ct==7){
	  ct=3;
	}
      if(ct==8 || ct==9){
	  ct=4;
	}
	if(ct<0){
	  ct=0;
	}
 
    show(ct);
    window.clearInterval(int);
    int = setInterval("autoSlide(1)", slideTime);
}); 
function autoSlide(ct) { 
 
	curIndex + 1 >= 5? curIndex = -1 : 0;
	 
	 
    show(curIndex + 1);
}
function show(index) {
	 
	 
    $.easing.def = "easeOutQuad";
    $("#drag_ctr").stop(false, true).animate({ left: index * 120 + 0 }, time);
    $("#banner_img>li").eq(curIndex).stop(false, true).fadeOut(time);
    adTxt.eq(curIndex).stop(false, true).animate({ top: "340px" }, time);
    adImg.eq(curIndex).stop(false, true).animate({ right: "700px" }, time);
    setTimeout(function () {
        $("#banner_img>li").eq(index).stop(false, true).fadeIn(time);
        adTxt.eq(index).children("p").css({ paddingTop: "50px", paddingBottom: "50px" }).stop(false, true).animate({ paddingTop: "0", paddingBottom: "0" }, time);
        adTxt.eq(index).css({ top: "0", opacity: "0" }).stop(false, true).animate({ top: "170px", opacity: "1" }, time);
		 
        adImg.eq(index).css({ right: "700px", opacity: "0" }).stop(false, true).animate({ right: "500px", opacity: "1" }, time);
    }, 200)
		
    curIndex = index;

	 
}