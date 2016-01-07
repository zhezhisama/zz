<?php
class WeixinModel extends CommonModel {
    // 自动验证设置
    protected $_validate =array(
        array('pubaccount','require','公众账号必填',1),
        array('pubaccount','','公众账号已经存在',0,'unique',self::MODEL_INSERT),
        array('title','','标题已经存在',0,'unique',self::MODEL_INSERT),

    );
    // 自动填充设置
    protected $_auto=array(
        array('typeid','1',self::MODEL_BOTH),
        array('status','1',self::MODEL_BOTH),
        array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
    );

}
?>