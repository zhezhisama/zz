<?php
class TuijianModel extends CommonModel {
	// 自动验证设置
	protected $_validate = array(
            array('checkcat','require','公众帐号必填'),
            array('recommendid,require','推荐位必选'),
            array('starttime','require','开始日期必填'),
            array('endtime','require','结束日期必填'),
            
        );
	// 自动填充设置
	protected $_auto = array(
            array('status','1',self::MODEL_INSERT),
            array('create_time','time',self::MODEL_INSERT,'function'),
            
        );
        
     

}
?>