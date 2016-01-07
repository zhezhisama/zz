<?php
class PaymentAction extends CommonAction {
	//过滤查询字段
	function _filter(&$map){
            $map['title'] = array('like',"%".$_POST['name']."%");
	}
        //支付方式
        function paytype() {
            $model = D('Paytype');
            if (!empty($model)) {

                $this->_list($model, $map);
            }
            $this->display();
        }
        //消费记录
        function payspend() {
            $model = D('Payspend');
            if (!empty($model)) {

                $this->_list($model, $map);
            }
            $this->display();
        }
        
         //人工审核
        public function checkPass() {
            $name = $this->getActionName();
            $model = D($name);
            $pk = $model->getPk();
            $id = $_GET [$pk];
            $condition = array($pk => array('in', explode(',', $id)));
            if (false !== $model->checkPass($condition)) {
                //人工审核后，为当前订单会员账户增加金额
               
                $model=M('Payment');
                $map['id']=array('eq',$id);
                $paydata=$model->where($map)->find();
         
                $total_fee=$paydata['paymoney']+$paydata['discount'];

                $member=M('Member');
                $mapmember['id']=array('eq',$paydata['memberid']);
                $mapmember['account']=array('eq',$paydata['membername']);
                $member->where($mapmember)->setInc('amount',$total_fee); 
                
                $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
                $this->success('操作成功！');
            } else {
                $this->error('操作失败！');
            }
        }
        //改价
        function discount() {
            $model = M('Payment');
            $id = $_REQUEST [$model->getPk()];

            $vo = $model->getById($id);

            $this->assign('vo', $vo);
            $this->display();
        }
        function discountupdate() {

            $model = D('Payment');
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            // 更新数据
            $list = $model->save();
            if (false !== $list) {
                //成功提示
                $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
                $this->success('修改成功!');
            } else {
                //错误提示
                $this->error('修改失败!');
            }
        }
        
        //支付方式配置
        function paytypeedit() {
            $model = M('Paytype');
            $id = $_REQUEST [$model->getPk()];

            $vo = $model->getById($id);

            $this->assign('vo', $vo);
            $this->display();
        }
        function paytypeupdate() {

            $model = D('Paytype');
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            // 更新数据
            $list = $model->save();
            if (false !== $list) {
                //成功提示
                $this->assign('jumpUrl', Cookie::get('_currentUrl_'));
                $this->success('配置成功!');
            } else {
                //错误提示
                $this->error('配置失败!');
            }
        }
        
        //后台操作入账
        function payinsert() {

            $membername=$_POST['membername'];
            $membermap['account']=array('eq',$membername);
            $member=D('Member')->where($membermap)->field('id,amount,intergral')->find();
            $memberid=$member['id'];
            if(empty($memberid)){
                $this->error('用户名输入错误！');
            }
            $price=$_POST['value'];
            if(!preg_match('/^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$/i',$price)) {
                $this->error( '充值金额必须为整数或小数(保留两位小数)');
            }
            $type=$_POST['type'];
            $body=$_POST['msg'];
            
            $optype=$_POST['optype'];
            if($optype==1){
                //增加
                $model = D('Payment');
                $data['memberid']=$memberid;
                $data['membername']=$membername;
                $data['payno']=create_sn();
                $data['businesstype']='线下支付';
                $data['paytypeid']=0;
                $data['paytypename']='后台充值';
                $data['paymoney']=$price;
                $data['type']=$type;//1是金钱，2是积分
                $data['payip']=get_client_ip();
                $data['paybody']=$body;
                $data['status']=1;
                $data['create_time']=time();
                if(false!==$model->add($data)){
                    $map['id']=$memberid;
                    $map['account']=$membername;
                    if($type==1){
                        $member=M('Member')->where($map)->setInc('amount', $price);//帐户余额增加
                    }
                    if($type==2){
                        $member=M('Member')->where($map)->setInc('intergral', $price);//积分点数增加
                    }
                    
                    $this->success('操作成功');
                }  else {
                     $this->error('操作失败');
                }
                
            }elseif($optype==2){
               
                
                if($type==1){
                    if($price>$member['amount']){
                        $this->error('用户当前余额少于所减金钱！');
                    }
                }
                if($type==2){
                    if($price>$member['intergral']){
                        $this->error('用户当前积分小于所减积分！');
                    }
                }
                $model=M('Payspend');
                $data['memberid']=$memberid;
                $data['membername']=$membername;
                $data['type']=$type;
                $data['value']=$price;
                $data['msg']="后台操作".$body;
                $data['userid']=  session('id');
                $data['username']=  session('account');
                $data['create_time']=time();
                if(false!==$model->add($data)){
                    $map['id']=$memberid;
                    $map['account']=$membername;
                    if($type==1){
                        $member=M('Member')->where($map)->setDec('amount', $price);//帐户余额减少
                    }
                    if($type==2){
                        $member=M('Member')->where($map)->setDec('intergral', $price);//积分点数减少
                    }
                    $this->success('操作成功');
                }else{
                     $this->error('操作失败');
                }
            }else{
                $this->error('操作错误');
            }
            
            
        }
        
}
?>