<?php
// 角色模块
class MembergroupAction extends CommonAction {
    function _filter(&$map){
        $map['status'] = array('egt',0);
        $map['name'] = array('like',"%".$_POST['name']."%");
    }

    
}
?>