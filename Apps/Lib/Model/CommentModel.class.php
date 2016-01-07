<?php
class CommentModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
            array('content','require','内容不能为空'),
        );
	
}
?>