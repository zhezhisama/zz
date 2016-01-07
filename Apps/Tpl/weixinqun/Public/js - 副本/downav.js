// JavaScript Document
	  var prompt_word = "��ʳ";
	  if(prompt_word && prompt_word.length > 0)
	  {
		  $(document).ready(function()
		  {
			 (function(element, str)
		  {
		  if (element.size() == 0)
		  { 
			return; 
		  }
	     var elem = element; 
		 var opts = {styleDefault: 'prompt-default',styleFocus: 'prompt-focus'};
		 if (elem.val() === ""){
		 elem.val(str);
		 if (!elem.hasClass(opts.styleDefault)){
		    elem.addClass(opts.styleDefault);
		 }
		 } 
		 elem.bind("focus", function(e){
		   elem.removeClass(opts.styleDefault);
		 if (!elem.hasClass(opts.styleFocus)){
		    elem.addClass(opts.styleFocus);
		 }
		 if (elem.val() === str){elem.val("");
		 }
		 });
		 elem.bind("blur", function(e){
		 if (elem.val() === ""){
			 elem.val(str);
			 elem.removeClass(opts.styleFocus);
			 if (!elem.hasClass(opts.styleDefault)){
			  elem.addClass(opts.styleDefault);
			 }
		 } else {
		 elem.removeClass(opts.styleDefault);
		 }
		 });
		 })($("#ssinput"), prompt_word);
		 });
		 }
		 function bindEvent(uname, utip){
		 if(uname.size() == 0) return;
		 var userNameEnter,userNameLeave,userTipLeave;
		 uname.bind("mouseenter", function(){
		 userNameEnter = setTimeout(function(){
		 if(userNameEnter) clearTimeout(userNameEnter);utip.show();
		 },200);
		 });
		 uname.bind("mouseleave", function(){
		 userNameLeave = setTimeout(function(){
		 if(userNameLeave) clearTimeout(userNameLeave);utip.hide();
		 },200);
		 });
		 utip.bind("mouseenter", function(){
		 if(userNameLeave) clearTimeout(userNameLeave);
		 if(userTipLeave) clearTimeout(userTipLeave);
		 });
		 utip.bind("mouseleave",function(){
		 userTipLeave = setTimeout(function(){
		 if(userNameLeave) clearTimeout(userNameLeave);
		 if(userTipLeave) clearTimeout(userTipLeave);
		 utip.hide();
		 },10);
		 });
		 }
		 $(document).ready(function(){
		 bindEvent($("#J_mytuanorder"), $("#J_mylist"));});/* ȫ����Ʒ */
		 (function(){
		 var $block = $("#jpros"),
		 $subblock = $(".subpage"),
		 $head=$subblock.find('h2'),
		 $ul = $("#proinfo"),
		 $lis = $ul.find("li"),
		 inter=false;
		 $head.click(function(e){
		 e.stopPropagation();
		 if(!inter){$ul.show();
		 }else{
		 $ul.hide();
		 }
		 inter=!inter;
		 });
		 $ul.click(function(event){
		 event.stopPropagation();
		 });
		 $('body').click(function(){
		 $ul.hide();inter=!inter;
		 });
		 $lis.hover(function(){
		 if(!$(this).hasClass('nochild')){
		 $(this).addClass("prosahover");
		 $(this).find(".prosmore").removeClass('hide');
		 }},
		 function(){
		 if(!$(this).hasClass('nochild')){
		 if($(this).hasClass("prosahover")){
		 $(this).removeClass("prosahover");
		 }
		 $(this).find(".prosmore").addClass('hide');
		 }});
		 })();
