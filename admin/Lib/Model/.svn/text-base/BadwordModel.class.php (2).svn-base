<?php
class BadwordModel extends CommonModel {
    // 自动验证设置
    protected $_validate =array(
        array('badword','require','敏感词必填！',1),
    );
    // 自动填充设置
    protected $_auto=array(
        array('create_time','time',self::MODEL_BOTH,'function'),
            
    );

}
?>