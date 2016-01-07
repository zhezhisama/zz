<?php
// 后台用户模块
class UserAction extends CommonAction {
    function _filter(&$map){

        $map['status'] = array('egt',0);
        $map['account'] = array('like',"%".$_POST['name']."%");
    }
    // 检查帐号
    public function checkAccount() {
        if(!preg_match('/^[a-z]\w{4,}$/i',$_POST['account'])) {
            $this->error( '用户名必须是字母，且5位以上！');
        }
        $User = M("User");
        //检测用户名是否冲突
        $name  =  $_REQUEST['account'];
        $result  =  $User->getByAccount($name);
        if($result) {
            $this->error('很抱歉，用户名已经存在！');
                
        }else {
            $this->success('恭喜您，用户名可以使用！');
        }
    }


    //重置密码
    public function resetPwd()
    {
    	$id  =  $_POST['id'];
        $password = $_POST['password'];
        if(''== trim($password)) {
            $this->error('密码不能为空！');
        }
        $User = M('User');
        $User->password	=md5($password);
        $User->id=$id;
        $result	= $User->save();
        if(false !== $result) {
            $this->success("密码已成功修改为：$password");
        }else {
            $this->error('重置密码失败！');
        }
    }
     public function _before_edit(){
        $Role=new RoleModel();
        $this->assign('list',$Role->getMyRole());
    }
    public function _before_add(){
        $Role=new RoleModel();
        $this->assign('list',$Role->getMyRole());
    }
    
}
?>