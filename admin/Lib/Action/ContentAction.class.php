<?php

class ContentAction extends CommonAction{

    public function index() {
        if(IS_POST){
            
            $validate=array(
                array('catname','require','栏目名称必填！',2),
            );
            $_POST['content']= str_replace(__ROOT__.'/'.APP_NAME.'/Tpl/Public/ueditor/php/../../../../../Uploads/', __ROOT__.'/Uploads/', $_POST['content']);
            $model = D('Category');
            if (false === $model->validate($validate)->create()) {
                $this->error($model->getError());
            }
            // 更新数据
            $list = $model->save();
            if (false !== $list) {
                //成功提示
                $this->success('编辑成功!');
            } else {
                //错误提示
                $this->error('编辑失败!');
            }
        }else{
            if(isset($_GET['catid'])){
                $map['id']=array('eq',$_GET['catid']);
            }
            $cate=D('Category');
            $this->list=$cate->where($map)->find();   
            $this->display();  
        }
        
    }
    
    
   
}

?>
