<?php
/*前台动作基类*/
class CommonAction extends Action {
    //初始化
    function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        import('@.ORG.Util.Cookie');
        import('@.ORG.Alipay');
        
        //栏目导航
        $nav_list = D('Category')->where('pid=0 AND status=1')->order('listorder')->select();
        
        if(is_array($nav_list)){
            foreach ($nav_list as $key=>$val){
                $nav_list[$key] = $this->changurl($val,$val['catdir']);//地址转化
                $nav_list[$key]['sub_nav'] = D('Category')->where('pid='.$val['id'].' AND status=1')->order('listorder')->select();//查询二级栏目
                foreach ($nav_list[$key]['sub_nav'] as $key2=>$val2){
                    $nav_list[$key]['sub_nav'][$key2] = $this->changurl($val2,$val2['catdir']);
                    $nav_list[$key]['sub_nav'][$key2]['sub_sub_nav'] = D('Category')->where('pid='.$val2['id'].' AND status=1')->order('listorder')->select();//查询三级栏目
                    foreach ($nav_list[$key]['sub_nav'][$key2]['sub_sub_nav'] as $key3=>$val3){
                        $nav_list[$key]['sub_nav'][$key2]['sub_sub_nav'][$key3] = $this->changurl($val3,$val3['catdir']);
                    }
                }
            }
        }
        $this->assign('nav_list',$nav_list);

        //栏目分类
        $node = D ( "Category" );  
        $map ['status'] =array("egt",0); 
        $list = $node->where($map)->order( 'level,listorder' )->select();
        $categorylist=arrToTree($list,0);
        $this->categorylist=$categorylist;

        //底部信息中心
        $xxzx = 141;
        $footer_catdata = D('Category')->where('status=1')->find($xxzx); 
        
        //获取所有子类id
        $footer_catlist = D('Category')->where('status=1')->order('listorder desc')->select();  

        $footer_catlist1 = getChildIdArray($footer_catlist,$xxzx);
        $footer_info = $footer_catlist1; 
        $footer_catlist = getChildId($footer_catlist,$xxzx); 
        $footer_catlist= substr($footer_catlist, 0, strlen($footer_catlist)-1);

        $xxzx_info = D("Article")->where("catid in ($footer_catlist)")->select(); 

        foreach ($xxzx_info as $k => $v) {
            $catid=intval($v['catid']);
            $footer_info[$catid]['child'][] = $v;
        }
        $this->footer_info = $footer_info;
        //获取综合微信下的分类
        $zcategorylist=arrToTree($list,53);
        $this->zcategorylist=$zcategorylist;

        //每日流量统计
        $tjdate=D('Tjdate');
        $map['create_date']=array('eq',date('Ymd',time()));
        $vl=$tjdate->where($map)->find();
        if($vl){
            $tjdate->id=$vl['id'];
            $tjdate->create_num=$vl['create_num']+1;
            $tjdate->save();
        }else{
            $tjdate->create_date=date('Ymd',time());
            $tjdate->create_num=1;
            $tjdate->add();
        }
        
        //页面流量统计
        $tjurl=D('Tjurl');
        $map['create_url']=__SELF__;
        $vla=$tjurl->where($map)->find();
        if($vla){
            $tjurl->id=$vla['id'];
            $tjurl->create_num=$vla['create_num']+1;
            $tjurl->save();
        }else{
            $tjurl->create_url=__SELF__;
            $tjurl->create_num=1;
            $tjurl->add();
        }
        
        //印象码
        define("PRIVATE_KEY","f8e0e8a892f46bfddff0255da50fd46e");
        $this->YXM_PUBLIC_KEY="b20672750e25c094064ddc29ec6b8de9";
        $this->YXM_localsec_url=(is_ssl()?'https://':'http://').$_SERVER['HTTP_HOST'].__ROOT__."/Data/localsec/";
        $this->iscomment=C('ISCOMMENT');//是否开启评论功能
        $this->commenttype=C('COMMENTTYPE');//评论选择
        $this->sitename=C('SITE_NAME');//站点名称
        $this->isaddaccount=C('ISADDACCOUNT');//是否开启游客提交公号功能
        $this->xfintergral=C('XFINTERGRAL');//发布促销活动消费积分
    }
   
    //SEO赋值
    public function seo($title,$keywords,$description,$positioin){
    	$this->assign('title',$title);
    	$this->assign('keywords',$keywords);
    	$this->assign('description',$description);
    	$this->assign('position',$positioin);
        
    }
    //URL转换
    public function changurl($ary,$catdir){
    	if(is_array($ary)){
            if(key_exists('modelname', $ary)){
                if(empty($catdir)){
                    $ary['url']=U($ary['modelname'].'/index/',array('id'=>$ary['id']));
                }else{
                    $ary['url']=U($ary['modelname'].'/'.$catdir.'/',array('id'=>$ary['id']));
                }
                
            }
            return $ary;
        }		
    }
    
    public function index() {
        
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }
        
        $catdata = D('Category')->where('status=1')->find($id);	
        
        //获取所有子类id
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $id.','.  getChildId($catlist,$id);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        $map['catid'] = array('in',$idlist);
        
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        
        $name = $this->getActionName();
        
        //获取分页设置
        $Model=M('Model');
        $map['table']=array('eq',$name);
        $pageinfo=$Model->where($map)->find();

        $Form   =   M($name);
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("data", $catdata);
        $this->assign("page", $page);
        $this->assign("list", $list);
        
        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        
        $this->display(); 
    }
    public function show()
    {
        
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }
        
        $name = $this->getActionName();
        
        $maphits['id']=array('eq',$id);
        D($name)->where($maphits)->setInc('hits',1);//总浏览次数
        
        
        if($name=='Weixin'){
            //按天浏览统计
            $Hits=M('hits');
            $result=$Hits->where("accountid=%d and date(from_unixtime(hittime))=CURDATE()",$id)->find();
            if(empty($result)){
                $data['hittime']=time();
                $data['accountid']=$id;
                $data['hitnum']=1;
                $Hits->data($data)->add();
            }else{
                $Hits->where("accountid=%d and date(from_unixtime(hittime))=CURDATE()",$id)->setInc('hitnum',1);
            }
        }
       
        $model=M($name);
        
        $map['status']=array('eq',1);
        //当前记录
        $map['id']=array('eq',$id);
        $data=$model->where($map)->find();
        if(empty($data)){
            $this->redirect('Public/fail');
        }
        
        //上一条记录
        $map['id']=array('lt',$id);
        $map['catid']=array('eq',$data['catid']);
        $prevdata=$model->where($map)->order('id desc')->limit('1')->find();
        
        
        //下一条记录
        $map['id']=array('gt',$id);
        $map['catid']=array('eq',$data['catid']);
        $nextdata=$model->where($map)->order('id asc')->limit('1')->find();

        //SEO标题、关键字、描述
        if(isset($data['sitetitle'])){
            if(!empty($data['sitetitle'])){
                $title=$data['sitetitle'];
            }  else {
                $title=$data['title'];
            }
        }else{
            $title=$data['title'];
        }
        $this->seo($title, $data['keywords'], $data['description'], D('Common')->getPosition($data['catid']));
        
        
        //内链
        $Chain=D('Chain');
        $ChainMap['status']=array('eq',1);
        $Chainlist=$Chain->where($ChainMap)->select();
        
        foreach ($Chainlist as $key => $value) {
            $data['content']=preg_replace('/'.$value['keyword'].'/i',"<a href=".$value['url']." target=".$value['target'].">".$value['keyword']."</a>", $data['content'],$value['number']);
        }

        //获取分页设置
        $Model=M('Model');
        $modelmap['table']=array('eq','Weixin');
        $pageinfo=$Model->where($modelmap)->find();
        
        //评论
        $Form = M("Comment");
        $Formmap['catid']=array('eq',$data['catid']);
        $Formmap['objectid']=array('eq',$id);
        $Formmap['status']=array('eq',1);
        
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($Formmap)->count();    //计算总数
        $Page = new Page($count, 10);
        
        $list   = $Form->where($Formmap)->limit($Page->firstRow. ',' . $Page->listRows)->order('id desc')->select();

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
       
        //获取促销活动
        $article=M('Promotion');
        $articlemap['pubaccountid']=array('eq',$data['id']);
        $articlemap['status']=array('eq',1);
        $articlelist=$article->where($articlemap)->limit('6')->select();
        
        
        $this->assign('articlelist', $articlelist);
        $this->assign("count", $count);
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->data=$data;
        $this->prevdata=$prevdata;
        $this->nextdata=$nextdata;

        Cookie::set('_curUrl_', __SELF__);
        session('_curUrl_', __SELF__);
        $this->display(); 
    }
    
    //上传图片
    public function _upload(){

        if(!empty($_FILES))
        {
            import("@.ORG.Util.Image");
            import("@.ORG.UploadFile");
            //导入上传类
            $upload = new UploadFile();
            //设置上传文件大小
            $upload->maxSize = 524288;
            //设置上传文件类型
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
            //设置附件上传目录
            $y = date('Y',time());
            $m = date('m',time());
            $d = date('d',time());
            
            $dir='./Uploads';
            
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/'.$y;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/'.$m;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/'.$d;
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            $dir.='/';
            $upload->savePath =$dir;//'../Uploads/';
            
            // 设置引用图片类库包路径
            $upload->imageClassPath = '@.ORG.Util.Image';
            //设置需要生成缩略图，仅对图像文件有效
            //$upload->thumb = true;
            //设置需要生成缩略图的文件后缀
            //$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
            //设置缩略图最大宽度
            //$upload->thumbMaxWidth = '150';
            //设置缩略图最大高度
            //$upload->thumbMaxHeight = '150';
            //设置上传文件规则
            $upload->saveRule = uniqid;
            //删除原图
            $upload->thumbRemoveOrigin = false;
            
            if (!$upload->upload()) {
                //捕获上传异常
                $strerror=$upload->getErrorMsg();
                if($strerror!="没有选择上传文件"){
                    $this->error($strerror);
                }
                
            } else {
                //取得成功上传的文件信息
                $uploadList = $upload->getUploadFileInfo();
                foreach ($uploadList as $key => $value) {
                    foreach ($_FILES as $key1 => $value1) {
                        if($value['name']===$value1['name']){

                            $_POST[$key1] = '/'.$y.'/'.$m.'/'.$d.'/'.$value['savename'];
                            
                        }
                    }
                    
                }

                
            }      
        }
    }
    
    //添加评论
    public function addcomment() {
        if(IS_POST){
            if(C('ISCOMMENT')==0){
                $this->error('操作错误');
            }
            //登录检测
            if(!session('?account')){
//                $this->ajaxReturn(U('Member/login'),'未登录',0);
                $this->error('您还未登录',U('Member/login'));
            }
             //验证码
            if(isset($_POST['verify'])){
               if($_SESSION['verify'] != md5($_POST['verify'])) {
//                    $this->ajaxReturn('验证码错误','验证码错误',0);
                   $this->error('验证码错误');
                } 
            }else{
                //印象码
                $YinXiangMa_response=  $this->YinXiangMa_ValidResult(@$_POST['YinXiangMa_challenge'],@$_POST['YXM_level'][0],@$_POST['YXM_input_result']);
                if($YinXiangMa_response !== "true") {
//                    $this->ajaxReturn('验证码错误','验证码错误',0);
                    $this->error('验证码错误');
                }
            }
            
            //评论内容
            $content=I('content');
            if(empty($content)){
                $this->error('内容不能为空！');
            }else{
                $_POST['content']=$content;
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                }else{
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                }
                
            }
            
            //站点设置
            $Set=D('Set')->find();
            
            //评论
            if($_POST['reply']==='0'){
                $_POST['pid']=0;
            }else{
                $_POST['pid']=I('post.pid',0,'int');
            }
            $name = $this->getActionName();
            $_POST['memberid']=  session('id');
            $_POST['membername']=  session('account');
            $_POST['ip']=get_client_ip();
            $_POST['create_time']=time();
            $_POST['modelname']=$name;
            
            $model = D('Comment');
            $vo=$model->create();
            if (false=== $vo) {
//                $this->ajaxReturn($model->getError(), '非法操作', 0);
                $this->error('非法操作');
            }
            if($Set['comment']){
                $model->status=  2;
                $info=",您发表的评论需要审核才能显示";
            }else{
                $model->status=  1;
                $info='';
            }
            //保存当前数据对象
            $list = $model->add();
           
            if ($list !== false) { 
                $vo['id']=$list;
                $vo['logo'] = getMemberLogo($vo['memberid']);//根据会员id得到会员头像
                $vo['create_time'] = date('Y-m-d H:i:s', $vo['create_time']);
                $vo['comment_time'] = commentDate($vo['create_time']);
                if($vo.reply==1){
                    $vo['replymembername'] = getPidMembername($vo['pid']);
                }
                $vo['content'] = nl2br($vo['content']);
                //成功提示
//                $this->ajaxReturn($vo, $info, 1);
                $this->success('提交成功');
                
            } else {
                //失败提示
//                $this->ajaxReturn('提交失败', '提交失败', 0);
                $this->error('提交失败');
            }
        }
        
    }
    
    /**
     * 印象码
     * @param type $YinXiangMaToken
     * @param type $level
     * @param type $YXM_input_result
     * @return string
     */
    function YinXiangMa_ValidResult($YinXiangMaToken,$level,$YXM_input_result){	
            if($YXM_input_result==md5("true".PRIVATE_KEY.$YinXiangMaToken)) { $result= "true"; }
            else { $result= "false"; }
            return $result;
    }
    //验证码
    public function verify() {
        $type=isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.Util.Image");
        Image::buildImageVerify(4,1,$type);
        
    }
    
    
}