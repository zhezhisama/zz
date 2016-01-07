function cleara(str){ //删除左右两端的空格
    return str.replace(/&a=.*/g, "");
}
function add(id){  
    if (id){
        location.href  = cleara(SELF)+"&a=add&id="+id;
    }else{
        location.href  = cleara(SELF)+"&a=add";
    }
}

//批量删除
function tjdelete(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择删除项！');
        return false;
    }

    if (window.confirm('确实要永久删除选择项吗？')){
        location.href =  cleara(SELF)+"&a=tjdelete&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}
//批量删除
function foreverdel(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择删除项！');
        return false;
    }

    if (window.confirm('确实要永久删除选择项吗？')){
        location.href =  cleara(SELF)+"&a=foreverdelete&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}

//批量设置热门推荐
function addtj(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择推荐项！');
        return false;
    }

    if (window.confirm('确实要推荐选中项吗？')){

        location.href =  cleara(SELF)+"&a=addtj&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}
//批量取消热门推荐
function canceltj(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }
    if (!keyValue){
        alert('请选择取消推荐项！');
        return false;
    }
    if (window.confirm('确实要取消推荐选中项吗？')){
        location.href =  cleara(SELF)+"&a=canceltj&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}
//批量设置今日推荐
function addcurtj(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择推荐项！');
        return false;
    }

    if (window.confirm('确实要推荐选中项吗？')){

        location.href =  cleara(SELF)+"&a=addcurtj&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}
//批量取消热门推荐
function cancelcurtj(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }
    if (!keyValue){
        alert('请选择取消推荐项！');
        return false;
    }
    if (window.confirm('确实要取消推荐选中项吗？')){
        location.href =  cleara(SELF)+"&a=cancelcurtj&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}
//批量设置置顶
function addzd(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择推荐项！');
        return false;
    }

    if (window.confirm('确实要推荐选中项吗？')){

        location.href =  cleara(SELF)+"&a=addzd&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}
//批量取消置顶
function cancelzd(id){
    var keyValue;
    if (id){
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }
    if (!keyValue){
        alert('请选择取消推荐项！');
        return false;
    }
    if (window.confirm('确实要取消推荐选中项吗？')){
        location.href =  cleara(SELF)+"&a=cancelzd&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);

    }
}

//批量通过审核
function checkPass(id){
    var keyValue;
    if (id)
    {
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择审核项！');
        return false;
    }

    if (window.confirm('确实要通过选择项吗？')){
        location.href =  cleara(SELF)+"&a=checkPass&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
    }
}
//批量取消审核
function forbid(id){
    var keyValue;
    if (id)
    {
        keyValue = id;
    }else {
        keyValue = getSelectCheckboxValues();
    }

    if (!keyValue){
        alert('请选择审核项！');
        return false;
    }

    if (window.confirm('确实要取消选择项吗？')){
        location.href =  cleara(SELF)+"&a=forbid&id="+keyValue;
        //ThinkAjax.send(URL+"/foreverdelete/","id="+keyValue+'&ajax=1',doDelete);
    }
}
function getSelectCheckboxValues(){
	var obj = document.getElementsByName('key');
	var result ='';
	var j= 0;
	for (var i=0;i<obj.length;i++){
            if (obj[i].checked===true){
//                selectRowIndex[j] = i+1;
                result += obj[i].value+",";
                j++;
            }
	}
	return result.substring(0, result.length-1);
}
//删除左右两端的空格
function trim(str){ 
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
