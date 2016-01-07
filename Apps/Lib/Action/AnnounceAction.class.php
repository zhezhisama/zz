<?php

class AnnounceAction extends CommonAction{
    public function index(){
        //站内公告
        $model=D('Announce');
        $map['status']=array('eq',1);
        $list=$model->where($map)->order('create_time desc')->select();
        
        $position[]=array('id'=>'Announce','catname'=>'站内公告');
        $this->seo('站内公告', '', '', $position);
        
        $this->list=$list;
        $this->display();
    }
}

?>
