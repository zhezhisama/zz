<?php
class ChainModel extends CommonModel {
	// 自动验证设置
	protected $_validate=array(
            array('keyword','require','关键词不能为空'),
            array('url','require','链接地址不能为空'),
            array('number','number','频率必须为数字'),
            
        );
	// 自动填充设置
	protected $_auto=array(
            array('create_time','time',self::MODEL_BOTH,'function'),
        );

}
?>