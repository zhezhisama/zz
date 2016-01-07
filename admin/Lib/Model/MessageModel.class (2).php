<?php
class MessageModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
            array('name','require','联系人必填'),
            array('content','require','留言内容必填'),
        );
	// 自动填充设置
	protected $_auto	 =	 array(
            array('ip','get_client_ip',self::MODEL_INSERT,'function'),
            array('status','2',self::MODEL_INSERT),
            array('create_time','time',self::MODEL_INSERT,'function'),
        );

}
?>