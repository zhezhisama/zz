<?php
class AdvertAction extends CommonAction {
    //过滤查询字段
    function _filter(&$map){
        $map['adname'] = array('like',"%".$_POST['name']."%");
    }
    public function _before_insert() {
        $_POST['starttime']=strtotime($_POST['starttime']);
        $_POST['endtime']=strtotime($_POST['endtime']);
       
    }
    public function _before_update() {
        $_POST['starttime']=strtotime($_POST['starttime']);
        $_POST['endtime']=strtotime($_POST['endtime']);
       
    }
}
?>