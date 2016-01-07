<?php

class ArticleAction extends CommonAction{
    
    function _filter(&$map) {
        $map['status']=array('eq',1);
    }
}

?>
