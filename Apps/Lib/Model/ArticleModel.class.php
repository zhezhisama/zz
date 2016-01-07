<?php
class ArticleModel extends CommonModel {
	// 自动验证设置
	protected $_validate = array(
            array('pubaccountid','require','公众帐号必选'),
            array('title','require','标题必填'),
            array('content','require','活动介绍必填'),
            array('title','','标题已经存在',0,'unique',self::MODEL_INSERT),
            
        );
	// 自动填充设置
	protected $_auto = array(
            array('listorder','0',self::MODEL_INSERT),
            array('hits','0',self::MODEL_INSERT),
            array('ip','get_client_ip',self::MODEL_INSERT,'function'),
            array('status','2',self::MODEL_BOTH),
            array('create_time','time',self::MODEL_INSERT,'function'),
            array('update_time','time',self::MODEL_BOTH,'function'),
        );
        

}
?>