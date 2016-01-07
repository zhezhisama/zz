<?php

class PagesetAction extends CommonAction {
    public function index() {
        if(IS_POST){
           
            $Model=M('Model');
            $count=$Model->count();
            for($i=0;$i<$count;$i++){
                $data['id']=$_POST['id'][$i];
                $data['listrows']=$_POST['listrows'][$i];
                $data['header']=$_POST['header'][$i];
                $data['prev']=$_POST['prev'][$i];
                $data['next']=$_POST['next'][$i];
                $data['first']=$_POST['first'][$i];
                $data['last']=$_POST['last'][$i];
                $data['theme']=$_POST['theme'][$i];
                $Model->save($data);
            }
            $this->success('设置成功!');
        }else{
            $Model=M('Model');
            $list=$Model->where($map)->select();
            $this->list=$list;
            $this->display();  
        }
        
    }
    
}

?>
