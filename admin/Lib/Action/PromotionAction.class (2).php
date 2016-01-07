<?php

class PromotionAction extends CommonAction{
    
    //过滤查询字段
    function _filter(&$map){
        if(isset($_GET['catid'])){
            $map['catid']=  array('eq',$_GET['catid']);
        }
        if(isset($_POST['catid'])){
            $map['catid']=  array('eq',$_POST['catid']);
        }
        if(!empty($_POST['name'])){
            $map['title'] = array('like',"%".$_POST['name']."%");
        }
        
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',$_POST['zt']);
            $this->zt=$_POST['zt'];
        }
    }
    public function _before_index() {
        if(isset($_GET['catid'])){
            $this->catid=$_GET['catid'];
        }
        if(isset($_POST['catid'])){
            $this->catid=$_POST['catid'];
        }
    }
    public function _before_add() {
        if(isset($_GET['id'])){
            $this->catid=$_GET['id'];
        }
    }
    public function _before_edit() {

        $cate=new CategoryModel();
        $menu =$cate->getModelCategory('Promotion'); //加载栏目
        $menu1 = arrToMenu($menu,0);  
        $this->categorylist=$menu1;

    }
    public function _before_insert() {
        if(!IS_POST) $this->error('页面不存在');
        $map['pubaccount']=array('eq',I('post.pubaccount'));
        $pubaccountid=D('Weixin')->where($map)->getField('id');
        if($pubaccountid>0){
            $_POST['pubaccountid']=$pubaccountid;
        }else{
            $this->error('公众帐号不存在');
        }
        
    }
    public function _before_update() {
        if(!IS_POST) $this->error('页面不存在');
        $map['pubaccount']=array('eq',I('post.pubaccount'));
        $pubaccountid=D('Weixin')->where($map)->getField('id');
        if($pubaccountid>0){
            $_POST['pubaccountid']=$pubaccountid;
        }else{
            $this->error('公众帐号不存在');
        }
    }

}

?>
