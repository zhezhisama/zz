<?php
class LinkModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('name','require','网站名称必填！',1),
		array('url','require','网站地址必填'),
		array('url','','网站地址已存在',0,'unique',self::MODEL_INSERT),
		);
	// 自动填充设置
	protected $_auto	 =	 array(
		array('create_time','time',self::MODEL_BOTH,'function'),
		);

}
?>