<?php
// 角色模型
class MembergroupModel extends CommonModel {
    public $_validate = array(
            array('name','require','名称必须'),
            array('name','','名称已经存在',0,'unique',self::MODEL_INSERT),
    );
    public $_auto=array(
            array('create_time','time',self::MODEL_INSERT,'function'),
            array('update_time','time',self::MODEL_BOTH,'function'),
    );
    function getMyRole(){
        $map['status']  = array('egt',0);
        $Group = D('Membergroup');
        //查找满足条件的列表数据
        $list= $Group->where($map)->field('id,name')->select();
        return $list;
    }

}
?>