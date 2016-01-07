<?php

class AreasAction extends CommonAction{
    //过滤查询字段
    function _filter(&$map){
        $map['area_name'] = array('like',"%".$_POST['name']."%");
    }
    public function index() {

        if(isset($_GET['id'])){
            $map["parent_id"]=$_GET['id'];
        }  else {
            $map["parent_id"]=0;
        }
        $node = M("Areas");  
        $menu = $node->where($map)->order('listorder')->select();  

        $this->list=$menu;
        Cookie::set('_currentUrl_', __SELF__);
        $this->display();  
    }
    public function _before_add() {
        if(isset($_GET['id'])){
            $this->parent_id=$_GET['id'];
        }
    }
    function insert() {
        $this->_upload();
        
        $name = $this->getActionName();
        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        $map['id'] = $_POST['parent_id'];
        $level=$model->where($map)->getField('area_type');
        if(!isset($level)){
            $model->area_type = 0;

        }else{
            $model->area_type = $level+1;

        }
        
        //保存当前数据对象
        $list = $model->add();
        if ($list !== false) { //保存成功
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('新增成功!');
        } else {
            //失败提示
            $this->error('新增失败!');
        }
    }
    function update() {
        $this->_upload();
        
        $name = $this->getActionName();
        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        $map['id'] = $_POST['parent_id'];
        $level=$model->where($map)->getField('area_type');
        if(!isset($level)){
            $model->area_type = 0;
        }else{
            $model->area_type = $level+1;
        }
        // 更新数据
        $list = $model->save();
        if (false !== $list) {
            //成功提示
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('编辑成功!');
        } else {
            //错误提示
            $this->error('编辑失败!');
        }
    }
    //彻底删除记录
    public function foreverdelete() {
        //删除指定记录
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
          
            if (isset($id)) {
                
                $node = M("Areas");  
                $menu = $node->order('listorder')->select();  
                $idlist=  $id.','.getAreaId($menu,$id);
                $idlist= substr($idlist, 0, strlen($idlist)-1);
                
                $condition = array($pk => array('in', explode(',', $idlist)));
                
                if (false !== $model->where($condition)->delete()) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
        //$this->forward();
    }
   
  
}

?>
