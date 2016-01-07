<?php
class SearchModel extends CommonModel {
	// 自动验证设置
	protected $_validate=array(
            array('search','require','关键词不能为空'),
        );
	// 自动填充设置
	protected $_auto=array(
            
            array('create_time','time',self::MODEL_BOTH,'function'),
        );

}
?>