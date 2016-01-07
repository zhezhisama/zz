$(function(){
    //选中列表行变色
    $(".list tr").mouseover(function(){
        $(this).addClass("currow");
    }).mouseout(function(){
        $(this).removeClass("currow");
    });
    //全选/取消
    $("#check").click(function(){
        if($(this).attr("checked")=="checked"){
            $('input[name=key]').attr("checked","checked");
        }else{
            $('input[name=key]').removeAttr ("checked");
        }

    });
    
    
});


