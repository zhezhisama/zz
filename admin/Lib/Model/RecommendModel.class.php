<?php
class RecommendModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
            array('recommendname','require','推荐方式名必填'),
            array('intergral','number','扣除积分必须为数字'),
            array('listorder','number','排序值必须为数字'),
        );
	// 自动填充设置
	protected $_auto	 =	 array(
            array('status','1',self::MODEL_INSERT),
            array('create_time','time',self::MODEL_INSERT,'function'),
        );

}
?>