<?php
// 角色模块
class RoleAction extends CommonAction {
    function _filter(&$map){
        $map['status'] = array('egt',0);
        $map['name'] = array('like',"%".$_POST['name']."%");
    }
    public function setRole() {
        $id= $_POST['menu_id'];
        $roleId=$_POST['roleId'];
        $group=D('Role');
        $group->delNodeRole($roleId);
        $result = $group->setNodeRoles($roleId,$id);
        if($result===false) {
            $this->error('授权失败！');
        }else {
            $this->success('授权成功！');
        }
    }
    public function role() {
            
        $role=D('Role');
        //获取当前用户组项目权限信息
        $roleId =  isset($_GET['id'])?$_GET['id']:'';
        $nodeRoleList = array();
        if(!empty($roleId)) {
            $this->assign("selectRoleId",$roleId);
            //获取当前角色的操作权限列表
            $list=$role->getNodeRoleList($roleId);
            foreach ($list as $vo){
                $nodeRoleList[$vo['id']]=$vo['id'];
            }
                
        }

        if (isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {  
            //显示菜单项   
            $menu = array ();  
            
            //读取数据库模块列表生成菜单项   
            $node = M ( "Node" );  
            $where ['status']=array('egt',0); 
            $list = $node->where( $where )->field( 'id,pid,name,title,level' )->order( 'level,sort' )->select();  
     
            $accessList = $_SESSION ['_ACCESS_LIST'];  
            foreach ( $list as $key => $module ) {  
                if (isset ( $accessList [strtoupper ( APP_NAME )] [strtoupper ( $module ['name'] )] ) || $_SESSION ['administrator']) {  
                    //设置模块访问权限   
                    $module ['access'] = 1;  
                    $menu [$key] = $module;  
                }  
            }    
            
            $menu = arrToTree($menu,0);  

            $tree=outMenu($menu,$nodeRoleList);
            $this->assign('tree', $tree );  
        }  
        
        C ( 'SHOW_RUN_TIME', false ); // 运行时间显示   
        C ( 'SHOW_PAGE_TRACE', false );  
        $this->display();  
        return;
    }
    
    //权限组用户列表
    public function user()
    {
        $groupId =  isset($_GET['id'])?$_GET['id']:'';
        $this->assign('groupId',$groupId);
        $map['role_id'] = array('eq',$groupId);
        $map['status'] = array('egt',0);
        
        //读取系统的用户列表
        $user=D("User");
        $list2=$user->where($map)->field('id,account,nickname,last_login_time,last_login_ip,email,status')->select();
        $this->assign('list2',$list2);
        
        //读取系统的角色列表
        $group=D("Role");
        $list=$group->where('status>=0')->field('id,name')->select();
        
        foreach ($list as $vo){
            $groupList[$vo['id']]=$vo['name'];
        }

        $this->assign("groupList",$groupList);
        $this->display();
        return;
    }
    public function _before_edit(){
        $Group = D('Role');
        //查找满足条件的列表数据
        $list= $Group->field('id,name')->select();
        $this->assign('list',$list);

    }
    public function add() {
        
        if (IS_POST) {
            $name = $this->getActionName();
            $model = D($name);
            if (false === $model->create()) {
                //IS_AJAX && $this->ajaxReturn(0, $model->getError());
                $this->error($model->getError());
            }
            //保存当前数据对象
            $list = $model->add();
            if ($list !== false) { //保存成功
                //IS_AJAX && $this->ajaxReturn(1,'新增成功!', '', 'add');
                $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
                $this->success('新增成功!');
            } else {
                //IS_AJAX && $this->ajaxReturn(0, '新增失败!');
                //失败提示
                $this->error('新增失败!');
            }

        }else{
            
            if (IS_AJAX) { 
                $response = $this->fetch();
                $data['status'] = 1;
                $data['data'] = $response;
                $this->ajaxReturn($data,'JSON');

            } else {
                $this->display();
            }
        }
                 
    }
    
}
?>