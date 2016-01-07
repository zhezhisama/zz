<?php
class SlideModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','幻灯片标题必填'),
                
            );
	// 自动填充设置
	protected $_auto	 =	 array(
                
            );

}
?>