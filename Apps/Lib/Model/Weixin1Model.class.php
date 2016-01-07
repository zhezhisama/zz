<?php
class Weixin1Model extends CommonModel {
	// 自动验证设置
	protected $_validate = array(
            array('catid','require','所属分类必选'),
            array('pubaccount','require','微信名称必填'),
			array('wxaccount','require','微信号必填'),
			array('province','require','所属地区省份必填'),
			array('city','require','所属地区城市必填'),
            array('logo,weblogo','checklogo','封面必填！',1,'callback'),
            array('qrcode,webqrcode','checkqrcode','二维码必填！',1,'callback'),
            array('content','require','帐号介绍必填'),
            array('pubaccount','','微信名称已经存在',0,'unique',self::MODEL_UPDATE),
            array('title','','名称已经存在',0,'unique',self::MODEL_UPDATE),
            
        );
	// 自动填充设置
	protected $_auto = array(
            array('typeid','1',self::MODEL_UPDATE),
            array('hits','0',self::MODEL_UPDATE),
            array('zhiding','0',self::MODEL_UPDATE),
            array('curtuijian','0',self::MODEL_UPDATE),
            array('tuijian','0',self::MODEL_UPDATE),
            array('xingji','5',self::MODEL_UPDATE),
            array('ip','get_client_ip',self::MODEL_BOTH,'function'),
            array('status','2',self::MODEL_UPDATE),
            array('create_time','time',self::MODEL_UPDATE,'function'),
            array('update_time','time',self::MODEL_BOTH,'function'),
        );
        
        //定义判断头像函数
        public function checklogo($data){
            $logo = $data['logo'];
            $weblogo= $data['weblogo'];
            if(empty($logo)&&empty($weblogo)){
                return false;
            }else{
                return true;
            }
        }
        //定义判断二维码函数
        public function checkqrcode($data){
            $qrcode = $data['qrcode'];
            $webqrcode= $data['webqrcode'];
            if(empty($qrcode)&&empty($webqrcode)){
                return false;
            }else{
                return true;
            }
        }
        public function getMyCategory(){
            $map['status']  = array('egt',0);
            $map['modelname']  = array('eq','Weixin');
            $cat=D('Category');
            $list=$cat->where($map)->field("*,concat(path,'-',id) as bpath")->order('bpath')->select();

            foreach($list as $key=>$value){
                $list[$key]['count']=count(explode('-',$value['bpath']));
            }
            return $list;
        }
        //获取栏目菜单
        public function getMyCategory1() {
            //读取数据库模块列表生成菜单项   
            $node = D ( "Category" );  
            $map ['status'] =array("egt",0); 
            $list = $node->where($map)->order( 'level,listorder' )->select();  
            return $list;
        }
        //获取栏目菜单
        public function getModelCategory($modelname) {
            //读取数据库模块列表生成菜单项   
            $node = D ( "Category" );  
            $map ['status'] =array("egt",0); 
            $map['modelname']=array('eq',$modelname);
            $map['catdir']=array(array('eq',''),array('exp','is null'),'OR');
            $list = $node->where($map)->order( 'level,listorder')->select(); 
            return $list;
        }

}
?>