<?php

class CommonAction extends Action {

    function _initialize() {

        import('@.ORG.Util.Cookie');
        
        // 用户权限检查
        if (C('USER_AUTH_ON') && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))) {
           
            import('@.ORG.Util.RBAC');
            if (!RBAC::AccessDecision()) {
                //检查认证识别号
                if (!$_SESSION [C('USER_AUTH_KEY')]) {
                    //跳转到认证网关
//                    redirect(PHP_FILE . C('USER_AUTH_GATEWAY'));
                    redirect(U(C('USER_AUTH_GATEWAY')));
                }
                // 没有权限 抛出错误
                if (C('RBAC_ERROR_PAGE')) {
                    // 定义权限错误页面
                    redirect(C('RBAC_ERROR_PAGE'));
                } else {
               
                    if (C('GUEST_AUTH_ON')) {
            
//                        $this->assign('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY'));
                        $this->assign('jumpUrl', U(C('USER_AUTH_GATEWAY')));
                    }
                    // 提示错误信息
                    $this->error(L('_VALID_ACCESS_'));
                }
               
            }
         
        }
    }

    public function index() {
        
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
        return;
    }

    /**
      +----------------------------------------------------------
     * 取得操作成功后要返回的URL地址
     * 默认返回当前模块的默认操作
     * 可以在action控制器中重载
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    function getReturnUrl() {
        return __URL__ . '?' . C('VAR_MODULE') . '=' . MODULE_NAME . '&' . C('VAR_ACTION') . '=' . C('DEFAULT_ACTION');
    }

    /**
      +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param string $name 数据对象名称
      +----------------------------------------------------------
     * @return HashMap
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    protected function _search($name = '') {
        //生成查询条件
        if (empty($name)) {
            $name = $this->getActionName();
        }
        $name = $this->getActionName();
        $model = D($name);
        $map = array();
        foreach ($model->getDbFields() as $key => $val) {
            if (isset($_REQUEST [$val]) && $_REQUEST [$val] != '') {
                $map [$val] = $_REQUEST [$val];
            }
        }
        return $map;
    }

    /**
      +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
      +----------------------------------------------------------
     * @access protected
      +----------------------------------------------------------
     * @param Model $model 数据对象
     * @param HashMap $map 过滤条件
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
      +----------------------------------------------------------
     * @return void
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    protected function _list($model, $map, $sortBy = '', $asc = false) {
        //排序字段 默认为主键名
        if (isset($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = !empty($sortBy) ? $sortBy : $model->getPk();
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if (isset($_REQUEST ['_sort'])) {
            $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        //取得满足条件的记录数
        $count = $model->where($map)->count('id');
        
        if ($count > 0) {
            import("@.ORG.Util.Page");
            //创建分页对象
            if (!empty($_REQUEST ['listRows'])) {
                $listRows = $_REQUEST ['listRows'];
            } else {
                $listRows = '';
            }
            $p = new Page($count, $listRows);
            
            //分页查询数据
            $voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();

            //分页跳转的时候保证查询条件
            foreach ($map as $key => $val) {
                if (!is_array($val)) {
                    $p->parameter .= "$key=" . urlencode($val) . "&";
                }
            }
            //分页显示
            $page = $p->show();
            //列表排序显示
            $sortImg = $sort; //排序图标
            $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
            $sort = $sort == 'desc' ? 1 : 0; //排序方式
            //模板赋值显示
            $this->assign('list', $voList);
            $this->assign('sort', $sort);
            $this->assign('order', $order);
            $this->assign('sortImg', $sortImg);
            $this->assign('sortType', $sortAlt);
            $this->assign("page", $page);
        }
        
        Cookie::set('_currentUrl_', __SELF__);
        return;
    }

    public function add() {
        $this->display();
    }
    function insert() {
        
        $this->_upload();
        $name = $this->getActionName();
        
        $_POST['content']= str_replace(__ROOT__.'/'.APP_NAME.'/Tpl/Public/ueditor/php/../../../../../Uploads/', __ROOT__.'/Uploads/', $_POST['content']);
        
        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        //保存当前数据对象
        $list = $model->add();
        if ($list !== false) { //保存成功
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('新增成功!');
        } else {
            //失败提示
            $this->error('新增失败!');
        }
    }

    function edit() {
        $name = $this->getActionName();
        $model = M($name);
        $id = $_REQUEST [$model->getPk()];

        $vo = $model->getById($id);
        
        $this->assign('vo', $vo);
        $this->display();
    }

    function update() {
       
        $this->_upload();
        $name = $this->getActionName();
        
        $_POST['content']= str_replace(__ROOT__.'/'.APP_NAME.'/Tpl/Public/ueditor/php/../../../../../Uploads/', __ROOT__.'/Uploads/', $_POST['content']);

        $model = D($name);
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        // 更新数据
        $list = $model->save();
        if (false !== $list) {
            //成功提示
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('编辑成功!');
        } else {
            //错误提示
            $this->error('编辑失败!');
        }
    }

    /**
      +----------------------------------------------------------
     * 默认删除操作
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     * @throws ThinkExecption
      +----------------------------------------------------------
     */
    public function delete() {
        //删除指定记录
        $name = $this->getActionName();
        $model = M($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
                $list = $model->where($condition)->setField('status', - 1);
                if ($list !== false) {
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
    }
    //彻底删除记录
    public function foreverdelete() {
        //删除指定记录
        $name = $this->getActionName();
        $model = D($name);
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

    public function clear() {
        //删除指定记录
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
            if (false !== $model->where('status=1')->delete()) {
                $this->assign("jumpUrl", $this->getReturnUrl());
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！');
            }
        }
//        $this->forward();
    }

    //禁用操作 状态0
    public function forbid() {
        $name = $this->getActionName();
        $model = D($name);
        $pk = $model->getPk();
        $id = $_REQUEST [$pk];
        $condition = array($pk => array('in', explode(',', $id)));
        
        if (false !== $model->forbid($condition)) {
            //$this->assign("jumpUrl", $this->getReturnUrl());
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('操作成功');
        } else {
            $this->error('操作失败！');
        }
    }
    //审核操作 状态1
    public function checkPass() {
        $name = $this->getActionName();
        $model = D($name);
        $pk = $model->getPk();
        $id = $_GET [$pk];
        $condition = array($pk => array('in', explode(',', $id)));
        if (false !== $model->checkPass($condition)) {
            //$this->assign("jumpUrl", $this->getReturnUrl());
            $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }
    //还原 状态1
    public function recycle() {
        $name = $this->getActionName();
        $model = D($name);
        $pk = $model->getPk();
        $id = $_GET [$pk];
        $condition = array($pk => array('in', $id));
        if (false !== $model->recycle($condition)) {

            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }
    //回收站
    public function recycleBin() {
        $map = $this->_search();
        $map ['status'] = - 1;
        $name = $this->getActionName();
        $model = D($name);
        if (!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
    }

    //恢复 状态1
    function resume() {
        //恢复指定记录
        $name = $this->getActionName();
        $model = D($name);
        $pk = $model->getPk();
        $id = $_GET [$pk];
        $condition = array($pk => array('in', $id));
        if (false !== $model->resume($condition)) {
            $this->assign("jumpUrl", $this->getReturnUrl());
            $this->success('操作成功！');
        } else {
            $this->error('操作失败！');
        }
    }
    //发货 状态3
    public function over() {
        $name = $this->getActionName();
        $model = M($name);
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
                $list = $model->where($condition)->setField('status', 3);
                if ($list !== false) {
                    $this->success('操作成功！');
                } else {
                    $this->error('操作失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
    }
    function saveSort() {
        $seqNoList = $_POST ['seqNoList'];
        if (!empty($seqNoList)) {
            //更新数据对象
            $name = $this->getActionName();
            $model = D($name);
            $col = explode(',', $seqNoList);
            //启动事务
            $model->startTrans();
            foreach ($col as $val) {
                $val = explode(':', $val);
                $model->id = $val [0];
                $model->sort = $val [1];
                $result = $model->save();
                if (!$result) {
                    break;
                }
            }
            //提交事务
            $model->commit();
            if ($result !== false) {
                //采用普通方式跳转刷新页面
                $this->success('更新成功');
            } else {
                $this->error($model->getError());
            }
        }
    }

     //删除图片
    public function delfile(){
        if(isset($_GET['id'])&&isset($_GET['file'])){
            $id = $_GET['id'];	
            $file=$_GET['file'];

            $name = $this->getActionName();

            $model = D($name);
            $src = '../Uploads'.$model->where('id='.$id)->getField($file);
            
            $model->where('id='.$id)->setField($file,'');
            if(is_file($src))unlink($src);
            $this->success('操作成功');
        }
    }
    public function _upload(){

        if(!empty($_FILES))
        {
            import("@.ORG.Util.Image");
            import("@.ORG.UploadFile");
            //导入上传类
            $upload = new UploadFile();
            //设置上传文件大小
            $upload->maxSize = 2097152;
            //设置上传文件类型
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg,zip,rar');
            //设置附件上传目录
            $y = date('Y',time());
            $m = date('m',time());
            $d = date('d',time());
            $dir='../Uploads/'.$y;
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
            //$upload->thumbMaxWidth = '400,100';
            //设置缩略图最大高度
            //$upload->thumbMaxHeight = '400,100';
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
//                            if($key1=='thumb'){
//                                //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
//                                //Image::water($value['savepath'] . 'm_' . $value['savename'], './Public/Images/logo.jpg');
//                                $_POST['thumb'] = $value['savename'];
//                            }  else {
//                                $_POST[$key1] = $value['savename'];
//                            }
                            $_POST[$key1] = '/'.$y.'/'.$m.'/'.$d.'/'.$value['savename'];
                        }
                    }
                    
                }

                
            }      
        }
    }
    
    public function _uploadimg(){

        if(!empty($_FILES))
        {
            import("@.ORG.Util.Image");
            import("@.ORG.UploadFile");
            //导入上传类
            $upload = new UploadFile();
            //设置上传文件大小
            $upload->maxSize = 2097152;
            //设置上传文件类型
            $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
            //设置附件上传目录
            $y = date('Y',time());
            $m = date('m',time());
            $d = date('d',time());
            $dir='../Uploads/img';
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
            $upload->thumbMaxWidth = '46';
            //设置缩略图最大高度
            $upload->thumbMaxHeight = '46';
            //设置上传文件规则
            $upload->saveRule = uniqid;
            //删除原图
            $upload->thumbRemoveOrigin = true;
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
                            $_POST[$key1] = '/img/'.$y.'/'.$m.'/'.$d.'/'.$value['savename'];
                        }
                    }
                    
                }
                

                
            }      
        }
    }
    
    
    
}

?>