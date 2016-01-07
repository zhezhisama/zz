<?php
class AjaxAction extends Action {
    public function getArea(){
        $where['parent_id']=$_REQUEST['areaId'];
        $area=D('areas')->where($where)->select();
        $this->ajaxReturn($area);
    }
}