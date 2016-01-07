function loadArea(areaId,areaType) {
    $.post(ajaxurl,{'areaId':areaId},function(data){
        if(areaType=='city'){
           $('#'+areaType).html('<option value="-1">城市</option>');
           
           $('#district').html('<option value="-1">镇/区</option>');
        }else if(areaType=='district'){
           $('#'+areaType).html('<option value="-1">镇/区</option>');
        }
        if(areaType!='null'){
            $.each(data,function(no,items){
                if(items.id===city){
                    $('#'+areaType).append('<option value="'+items.id+'" selected="selected">'+items.area_name+'</option>');
                }else{
                    $('#'+areaType).append('<option value="'+items.id+'">'+items.area_name+'</option>');
                }
                
            });
        }
    });
}