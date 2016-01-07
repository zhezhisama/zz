<?php

class MessageAction extends CommonAction{
    public function index() {
       if(IS_POST){
           
            $ip=get_client_ip();
            $time=time();
            $map['ip']=array('eq',$ip);
            
            //留言间隔
            $Set=D('Set')->find();
            $model = D('Message');
            $Message=$model->where($map)->order('id desc')->find();
            if($time-$Message['create_time']<$Set['messageinterval']){
                $this->error('每条留言需间隔'.($Set['messageinterval']/60).'分钟!');
            }
            
            if ($vo = $model->create()) {
                
                //保存当前数据对象
              //  $list = $model->add();
               // if ($list !== false){ 
				if($model->add()){
               //	 $this->ajaxReturn(0,"留言成功！",1);
                     $this->success('留言成功!');
                } else {
                    //失败提示
                  //  $this->ajaxReturn('添加失败',0);   
                      $this->error('提交失败!');
				}
            }else{
                $this->error($model->getError());
            }
            
       }else{
           $this->display();
       }
    }
}

?>
