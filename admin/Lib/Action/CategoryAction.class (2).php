<?php

class CategoryAction extends CommonAction{
    //过滤查询字段
    function _filter(&$map){
        $map['catname'] = array('like',"%".$_POST['name']."%");
    }
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        
        $cate=new CategoryModel();
        $menu =$cate->getAllCategory(); //加载栏目
        $menu = arrToMenu($menu,0);  
        $this->list=$menu;
        Cookie::set('_currentUrl_', __SELF__);
        $this->display();  
    }
    public function _before_add() {
        if(isset($_GET['id'])){
            $current_pid=$_GET['id'];
        }
        if(isset($_GET['model'])){
            $current_modelname=$_GET['model'];
        }
        $cate=new CategoryModel();
//        $this->list=$cate->getMyCategory();//加载栏目
        $menu =$cate->getAllCategory(); //加载栏目
        $menu = arrToMenu($menu,0);  
        $this->list=$menu;
        $this->mdldata=$cate->getMyModel();//加载模型
        //var_dump($this->mdldata)exit;
        $this->current_pid=$current_pid;
        $this->current_modelname=$current_modelname;
    }
    public function _before_edit() {
        $cate=new CategoryModel();
//        $this->list=$cate->getMyCategory();//加载栏目
        $menu =$cate->getAllCategory(); //加载栏目
        $menu = arrToMenu($menu,0);  
        $this->list=$menu;
        $this->mdldata=$cate->getMyModel();//加载模型
        
    }
    function insert() {
        $this->_upload();
        
        $name = $this->getActionName();
        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        $map['id'] = $_POST['pid'];
        $level=$model->where($map)->getField('level');
        $model->level = $level+1;
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
        $map['id'] = $_POST['pid'];
        $level=$model->where($map)->getField('level');
        $model->level = $level+1;
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
                $idarray=explode(',', $id);
                if(is_array($idarray)){
                    foreach ($idarray as $value) {
                        //判断分类下是否还包含子分类
                        $map['pid']=$value;
                        $menucount = $model->where($map)->count();
                        if($menucount>0){
                            $this->error('请先删除栏目下的子级栏目！');
                        }
                        //判断分类下是否有数据
                        $modelname=$model->getField('modelname');//获取当前分类模型
                        $mapmodel['catid']=$value;
                        $datacount=D($modelname)->where($mapmodel)->count();
                        
                        if($datacount>0){
                            $this->error('栏目下已存在数据，不能删除栏目！');
                        }
                        
                        
                    }
                }
                $condition = array($pk => array('in', explode(',', $id)));
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
