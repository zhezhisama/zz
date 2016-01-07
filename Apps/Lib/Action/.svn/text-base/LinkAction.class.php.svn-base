<?php

class LinkAction extends CommonAction {

    public function index(){
        //友情链接
        $model=D('Link');
        $list=$model->order('listorder asc')->select();
        
        $position[]=array('id'=>'Link','catname'=>'友情链接');
        $this->seo('友情链接', '', '', $position);
        
        $this->list=$list;
        $this->display();
    }
 
}