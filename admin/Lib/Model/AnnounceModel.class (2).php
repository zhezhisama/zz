<?php
class AnnounceModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('content','require','公告内容必填'),
		);
	// 自动填充设置
	protected $_auto	 =	 array(
                array('status','1',self::MODEL_BOTH),
		array('create_time','time',self::MODEL_BOTH,'function'),
		);

}
?>