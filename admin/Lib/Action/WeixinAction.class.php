<?php

class WeixinAction extends CommonAction{

    //过滤查询字段
    function _filter(&$map){
        if(isset($_GET['catid'])){
            $map['catid']=  array('eq',$_GET['catid']);
            $this->catid=$_GET['catid'];
        }
        if(isset($_POST['catid'])){
            $map['catid']=  array('eq',$_POST['catid']);
            $this->catid=$_POST['catid'];
        }
        if(!empty($_POST['name'])){
            //查询条件
            if(isset($_POST['cxtj'])){
                switch ($_POST['cxtj']){
                    case 1:
                        $map['pubaccount'] = array('like',"%".$_POST['name']."%");
                        break;
                    case 2:
                        $map['membername'] = array('like',"%".$_POST['name']."%");
                        break;
                    default :
                        $map['pubaccount'] = array('like',"%".$_POST['name']."%");
                        break;
                }
                $this->cxtj=$_POST['cxtj'];
            }else{
                $map['pubaccount'] = array('like',"%".$_POST['name']."%");
            }
        }
        //状态
        if(isset($_POST['zt'])&&$_POST['zt']!=-2){
            $map['status'] = array('eq',$_POST['zt']);
            $this->zt=$_POST['zt'];
        }
        //热门推荐
        if(isset($_POST['tj'])&&$_POST['tj']!=-2){
            $map['tuijian'] = array('eq',$_POST['tj']);
            $this->tj=$_POST['tj'];
        }
        //推荐位置
        if(isset($_POST['tjposition'])&&$_POST['tjposition']!=-2){
            $map['recommendid'] = array('eq',$_POST['tjposition']);
            $this->tjzt=$_POST['tjposition'];
        }
        
        
    }
    public function _before_index() {
        if(isset($_GET['catid'])){
            $this->catid=$_GET['catid'];
        }
        if(isset($_POST['catid'])){
            $this->catid=$_POST['catid'];
        }
        
    }
    public function _before_add() {
        if(isset($_GET['id'])){
            $this->catid=$_GET['id'];
        }
        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);
        
    }
    public function _before_edit() {
        $id = I("get.id",'',int);

        $wx=D('Weixin');
        $record =$wx->find($id); //加载栏目
      

        //获取下级子分类
        $childCatMap['pid']=array('eq',$record['publish_type_id']);
        $childCatMap['status']=array('eq',1);
        $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
        $this->categorylist=$childCatList;  


        //获取最大等级
//        $Category=M('Category');
//        $map['modelname']=array('eq','Weixin');
//        $maxLevel = $Category->where($map)->max('level');
//        $this->maxLevel=$maxLevel;
    
        //获取省级地区
        $province=D('areas')->where(array('parent_id'=>1))->select();
        $this->assign('province',$province);

        //获取发布类别
        $publish_type = C('publish_type');
        $this->assign('publish_type',$publish_type);

        //获取群上限
        $qun_shang_xian = C('qun_shang_xian');
        $this->assign('qun_shang_xian',$qun_shang_xian);
    }
    public function _before_insert() {
        
        
        if(empty($_POST['title'])){
            $_POST['title']=$_POST['pubaccount'];
        }
        
    }
    public function _before_update() {
        
        if(empty($_POST['title'])){
            $_POST['title']=$_POST['pubaccount'];
        }
    }
    //帐号审核
    public function check() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
           
            $this->_list($model, $map);
        }
        $this->display();
    }
    //热门推荐快速设置
    public function hot() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $map['status']=array('eq',1);
        
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
           
            $this->_list($model, $map);
        }
        $this->ISTASK=C('ISTASK');
        $this->display();
    }
    //推荐记录
    public function tjlist() {
        $tjposition=M('Recommend')->where('status=1')->select();
        
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }

        $model = D('tuijian');
        if (!empty($model)) {
           
            $this->_list($model, $map);
        }
        $this->assign('tjposition', $tjposition);
        $this->display();
    }
    
    //任务推荐
    public function tasktj() {
        if(!IS_POST) $this->error ('操作错误');
        
        //如果开启了任务
        if(!C('ISTASK')) $this->error('未开启任务提交');

        //任务标识
       $taskmark1=I('taskmark');

       if(empty($taskmark1)){
           $this->error('请输入任务代码！');
       }
       $taskmark=base64_decode($taskmark1);
       $mark=  explode('|', $taskmark);

       $taskno=$mark[0];//任务编号

       $pubaccount=$mark[1];//公众帐号 
       $wxid=  getWeixinId($pubaccount);//公众帐号ID
       if(empty($wxid)){

           //验证标识码
           $yufumark=C('YUFUMARK');

           //任务接口地址
           $taskurl=C('TASKURL');
           $result=file_get_contents($taskurl.'&taskno='.$taskno.'&yufumark='.$yufumark);
           $data=json_decode($result,TRUE);

           if($data['info']=='success'){

               $data['data']['create_time']=  time();
               $data['data']['update_time']=  time();
               if($wxid=M('Weixin')->data($data['data'])->add()){

               }  else {
                   $this->error('任务提交失败，请稍后重试!');
               }

           }elseif($data['info']=='fail'){
               //获取帐号失败
               $this->error('任务提交失败，请检查标识码和任务接口地址是否填写正确!');
           }

       }
       $recommendid=$mark[2];//推荐位置
       $timelimit=$mark[3];//是否时间限制
       $starttime=$mark[4];//开始时间
       $endtime=$mark[5];//结束时间 

       $_POST['wxid']=$wxid;
       $_POST['pubaccount']=$pubaccount;
       $_POST['recommendid']=$recommendid;
       $_POST['timelimit']=$timelimit;
       $_POST['starttime']=$starttime;
       $_POST['endtime']=$endtime;

       $model = D('Tuijian');
       if(isset($wxid)){
           $map['id']=array('eq',$wxid);
           $status=D('Weixin')->where($map)->getField('status');
           if($status!=1){
               $this->error('操作错误');
           }
       }else{
           $this->error('操作错误');
       }

       if($timelimit==1){
           $intergralnum= getIntergral($recommendid);
           $intergral=0;
       }else{
           $intergral=0;
       }


       //判断是否已经推荐
       $maptj['wxid']=array('eq',$wxid);
       if($timelimit==1){
           $maptj['endtime']=array('egt',$starttime);
       }else{
           $maptj['timelimit']=array('eq',$timelimit);
       }

       $maptj['recommendid']=array('eq',$recommendid);

       $istj=D('tuijian')->where($maptj)->find();
       if(!empty($istj)){
           $this->error('已推荐过了');
       }

       if (false === $model->create()) {
           $this->error($model->getError());
       }

       $model->intergral=$intergral;
       $model->intergralnum=$intergralnum;
       $model->status=1;
       $model->create_time=  time();

       //保存当前数据对象
       $list = $model->add();
       if ($list !== false) { 
           //保存成功
           $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
           $this->success('提交成功!');

       } else {
           //失败提示
           $this->error('提交失败!');
       }
        
    }




    //添加推荐
    public function addtj() {

        if(IS_POST){

            $wxid=I('post.wxid');
            $pubaccount=I('post.pubaccount');
            $recommendid=I('post.recommendid');
            $timelimit=I('post.timelimit');
            $starttime=strtotime(I('post.starttime'));
            $endtime=strtotime(I('post.endtime'));

            $_POST['wxid']=$wxid;
            $_POST['pubaccount']=$pubaccount;
            $_POST['recommendid']=$recommendid;
            $_POST['timelimit']=$timelimit;
            $_POST['starttime']=$starttime;
            $_POST['endtime']=$endtime;

            $model = D('Tuijian');
            if(isset($wxid)){
                $map['id']=array('eq',$wxid);
                $status=D('Weixin')->where($map)->getField('status');
                if($status!=1){
                    $this->error('操作错误');
                }
            }else{
                $this->error('操作错误');
            }

            if($timelimit==1){
                $intergralnum= getIntergral($recommendid);
                $intergral=0;
            }else{
                $intergral=0;
            }
            
            
            //判断是否已经推荐
            $maptj['wxid']=array('eq',$wxid);
            if($timelimit==1){
                $maptj['endtime']=array('egt',$starttime);
            }else{
                $maptj['timelimit']=array('eq',$timelimit);
            }
            
            $maptj['recommendid']=array('eq',$recommendid);

            $istj=D('tuijian')->where($maptj)->find();
            if(!empty($istj)){
                $this->error('已推荐过了');
            }
            
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            
            $model->intergral=$intergral;
            $model->intergralnum=$intergralnum;
            $model->status=1;
            $model->create_time=  time();
           
            //保存当前数据对象
            $list = $model->add();
            if ($list !== false) { 
                //保存成功
                $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
                $this->success('提交成功!');
                
            } else {
                //失败提示
                $this->error('提交失败!');
            }
        }  else {

            //公号id
            $wxid=I('id');
            
            //公号名称
            $map['id']=array('eq',$wxid);
            $pubaccount=D('Weixin')->where($map)->getField('pubaccount');
            
             //推荐方式
            $maprecommend['status']=array('eq',1);
            $recommendlist=D('Recommend')->where($maprecommend)->order('listorder')->select();
            
            $this->assign('wxid',$wxid);
            $this->assign('pubaccount',$pubaccount);
            $this->assign('recommendlist',$recommendlist);

            $this->display();
        }
        
     
    }
    
    //彻底删除推荐记录
    public function tjdelete() {
        //删除指定记录
        $model = D('Tuijian');
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
          
            if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
                if (false !== $model->where($condition)->delete()) {
                    
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
//        $this->forward();
    }
    //禁用操作 状态0
    public function tjforbid() {
        $model = D('Tuijian');
        $pk = $model->getPk();
        $id = $_REQUEST [$pk];
        $condition = array($pk => array('in', explode(',', $id)));
        if (false !==$model->forbid($condition)) {
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('操作成功');
        } else {
            $this->error('操作失败！');
        }
    }
    //审核操作 状态1
    public function tjcheckPass() {
        $model = D('Tuijian');
        $pk = $model->getPk();
        $id = $_GET [$pk];
        $condition = array($pk => array('in', explode(',', $id)));
        if (false !== $model->checkPass($condition)) {
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }
    
    
}

?>
