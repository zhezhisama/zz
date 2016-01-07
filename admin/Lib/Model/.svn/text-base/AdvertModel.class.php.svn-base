<?php
class AdvertModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('adname','require','广告名称必填'),
                array('adtag','require','广告标签必填'),
            );
	// 自动填充设置
	protected $_auto	 =	 array(
                array('status','1',self::MODEL_INSERT),
		array('create_time','time',self::MODEL_BOTH,'function'),
            );

}
?>