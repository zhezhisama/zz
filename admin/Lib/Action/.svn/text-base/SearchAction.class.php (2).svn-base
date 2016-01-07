<?php
class SearchAction extends CommonAction {
	//过滤查询字段
	function _filter(&$map){
		$map['search'] = array('like',"%".$_POST['name']."%");
	}
}
?>