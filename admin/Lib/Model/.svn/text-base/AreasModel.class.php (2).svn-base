<?php
class AreasModel extends CommonModel {
    // 自动验证设置
    protected $_validate=array(
        array('area_name','require','城市名称必填！',1),
//        array('catname','','栏目已经存在',0,'unique',self::MODEL_BOTH),
    );
    
    // 自动填充设置
    protected $_auto=array(
        
    );
  
    //获取城市菜单
    public function getMyAreas() {
        //读取数据库模块列表生成菜单项   
        $node = D ("Areas");  
        $list = $node->order('area_type,listorder')->select();  
        return $list;
    }

    
}
?>