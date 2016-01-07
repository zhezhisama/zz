<?php
class MemberModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
            array('account','require','用户名不能为空'),
            array('password','require','密码不能为空'),
            array('nickname','require','昵称不能为空'),
            array('account','','用户名已存在',0,'unique',self::MODEL_INSERT),
        );
	// 自动填充设置
	protected $_auto	 =	 array(
            array('status','1',self::MODEL_INSERT),
            array('create_time','time',self::MODEL_INSERT,'function'),
            array('update_time','time',self::MODEL_BOTH,'function'),
        );

}
?>