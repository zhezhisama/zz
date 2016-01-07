<?php
class PromotionModel extends CommonModel {
    // 自动验证设置
    protected $_validate =array(
        array('title','require','标题必填！',1),
        array('pubaccountid','require','公众帐号必填',1),
        array('title','','标题已经存在',0,'unique',self::MODEL_INSERT),
    );
    // 自动填充设置
    protected $_auto=array(
        array('intergral','0',self::MODEL_INSERT),
        array('hits','0',self::MODEL_INSERT),
        array('listorder','0',self::MODEL_INSERT),
        array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
    );

}
?>