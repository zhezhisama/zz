<?php
// 角色模型
class MemberModel extends CommonModel {
    public $_validate = array(
            array('account','require','用户名不能为空'),
            array('password','require','密码不能为空'),
            array('intergral','number','积分点数必须为数字'),
            array('account','','用户名已存在',0,'unique',self::MODEL_INSERT),
    );
    public $_auto=array(
            array('create_time','time',self::MODEL_INSERT,'function'),
            array('update_time','time',self::MODEL_BOTH,'function'),
    );
    
}
?>