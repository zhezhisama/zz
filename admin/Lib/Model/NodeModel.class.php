<?php
// 节点模型
class NodeModel extends CommonModel {
    protected $_validate=array(
        array('name','checkNode','节点已经存在',0,'callback'),
    );
    public function checkNode() {
        $map['name'] =$_POST['name'];
        $map['pid']=isset($_POST['pid'])?$_POST['pid']:0;
        $map['status'] = 1;
        if(!empty($_POST['id'])) {
            $map['id']	=array('neq',$_POST['id']);
        }
        $result=$this->where($map)->field('id')->find();
        if($result) {
            return false;
        }else{
            return true;
        }
    }
    public function getMenu() {
        //显示菜单项   
        $menu = array ();  

        //读取数据库模块列表生成菜单项   
        $node = D ( "Node" );  
        $map ['status'] =array("egt",0); 
        $list = $node->where($map)->field( 'id,pid,name,title,level,sort,status' )->order( 'level,sort' )->select();  

        foreach ( $list as $key => $module ) {  
                //设置模块访问权限   
                $module ['access'] = 1;  
                $menu [$key] = $module;  
  
        }    

        $menu = arrToTree($menu,0);  
        return $menu;
    }
}
?>