<?php

class ContentAction extends CommonAction{
    public function index() {
        
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }
        
        $catdata = D('Category')->where('status=1')->find($id);
        
        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);

        $this->assign("data", $catdata);
        $this->display(); 
    }
 
}

?>
