<?php

class IndexAction extends CommonAction {

    public function index(){

        $wxModel=M('Weixin');
        $wxModelMap['status']=array('eq',1);
        //总共收录公众帐号数
        $allcount=$wxModel->where($wxModelMap)->count();

        //今日收录帐号数
        $daycount=$wxModel->where('status=1 and date(from_unixtime(create_time))=CURDATE() ')->count();
        
        //热门推荐
        $prefix=C('DB_PREFIX');
        //获取热门推荐的公号
        $time=time();
        
        $where['starttime']=array('elt',$time);
        $where['endtime']=array('egt',$time-(24*3600));
        $where['_logic'] = 'and';
        $mapa['_complex'] = $where;
        $mapa['timelimit']=array('eq',0);
        $mapa['_logic'] = 'or';
        $map['_complex'] = $mapa;

        $map[$prefix.'tuijian.status']=array('eq',1);
        $map['recommendid']=array('eq',1);

        //取所有热门推荐数据
        $name = $this->getActionName();

        $map[$prefix.'weixin.status']=array('eq',1);
        //获取分页设置
        $Model=M('Model');
        $modelmap['table']=array('eq','Weixin');
        $pageinfo=$Model->where($modelmap)->find();

        $tuijian=M('Tuijian');
        import("@.ORG.Page");       //导入分页类
        $count  = $tuijian->join($prefix.'weixin ON '.$prefix.'weixin.id='.$prefix.'tuijian.wxid')->where($map)->count();    //计算总数
//        $Page = new Page($count, $pageinfo['listrows']);
        $Page = new Page($count, 14);
//        $list=$tuijian->join($prefix.'weixin ON '.$prefix.'weixin.id='.$prefix.'tuijian.wxid')->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->field($prefix.'weixin.*')->order('timelimit desc,'.$prefix.'tuijian.create_time desc')->select();
        $tjlist=$tuijian->join($prefix.'weixin ON '.$prefix.'weixin.id='.$prefix.'tuijian.wxid')->where($map)->limit('14')->field($prefix.'weixin.*')->order('timelimit desc,'.$prefix.'tuijian.create_time desc')->select();
        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
//        $this->assign("tjpage", $page);
        $this->assign("tjlist", $tjlist);
        $category = D('category');
        $weixin = D('weixin');
        // 抢位 微信群
        $weixin_qun_t = $category -> where("pid = 44") -> select();
        foreach($weixin_qun_t as $k => $v)
        {
            $weixin_qun_a[] = $v['id']; 
        }
        $weixin_qun_catid = "(".implode(',',$weixin_qun_a).")"; 
        $this -> assign('qiangwei_qun',$weixin -> field("id,title,logo,logo2,qrcode,qrcode2,qiangwei_time") -> where("status = 1 and qiangwei_sort > 0 and catid in {$weixin_qun_catid} and qiangwei_time=(select max(qiangwei_time) from ".$prefix.weixin." a where a.publish_type_id=44 and a.qiangwei_sort=".$prefix.weixin.".qiangwei_sort and a.status=1)")-> limit("12") ->order("qiangwei_sort asc")-> select());
        // 抢位 公众号
        $weixin_hao_t = $category -> where("pid = 47") -> select();
        foreach($weixin_hao_t as $k => $v)
        {
            $weixin_hao_a[] = $v['id']; 
        }
        $weixin_hao_catid = "(".implode(',',$weixin_hao_a).")"; 
        $this -> assign('qiangwei_hao',$weixin -> field("id,title,logo,logo2,qrcode,qrcode2,qiangwei_time") -> where("status = 1 and qiangwei_sort > 0 and catid in {$weixin_hao_catid} and qiangwei_time=(select max(qiangwei_time) from ".$prefix.weixin." a where a.publish_type_id=47 and a.qiangwei_sort=".$prefix.weixin.".qiangwei_sort and a.status=1)")-> limit("12") ->order("qiangwei_sort asc")-> select());
        // 抢位 个人威信
        $weixin_person_t = $category -> where("pid = 48") -> select();
        foreach($weixin_person_t as $k => $v)
        {
            $weixin_person_a[] = $v['id']; 
        }
        $weixin_person_catid = "(".implode(',',$weixin_person_a).")"; 
        $this -> assign('qiangwei_person',$weixin  -> field("id,title,logo,logo2,qrcode,qrcode2,qiangwei_time")-> where("status = 1 and qiangwei_sort > 0 and catid in {$weixin_person_catid} and qiangwei_time=(select max(qiangwei_time) from ".$prefix.weixin." a where a.publish_type_id=48 and a.qiangwei_sort=".$prefix.weixin.".qiangwei_sort and a.status=1)")-> limit("12") ->order("qiangwei_sort asc")-> select());
        // 今天 微信群
        $this -> assign('today_qun',$weixin -> field("id,title,logo,logo2,qrcode,qrcode2,qiangwei_time,pubaccount")-> where("status = 1 and catid in {$weixin_qun_catid}") -> limit("12") -> order("update_time desc") -> select());
         // 今天 公众号
        $this -> assign('today_hao',$weixin -> field("id,title,logo,logo2,qrcode,qrcode2,qiangwei_time,pubaccount")-> where("status = 1  and catid in {$weixin_hao_catid}") -> limit("12") -> order("update_time desc") -> select());
         // 今天 个人威信
        $this -> assign('today_person',$weixin -> field("id,title,logo,logo2,qrcode,qrcode2,qiangwei_time,pubaccount")-> where("status = 1  and catid in {$weixin_person_catid}") -> limit("12") -> order("update_time desc") -> select());
	
		//微商货源
		$weixin_huoyuan_t = $category -> where("pid = 1") -> select();
		
		
		
        foreach($weixin_huoyuan_t as $k => $v)
        {
            $weixin_huoyuan_a[] = $v['id']; 
        }
		
        $weixin_huoyuan_catid = "(".implode(',',$weixin_huoyuan_a).")"; 

		
		$this -> assign('weixin_huoyuan',$weixin -> field("id,title,logo,logo2") -> where("status = 1  and catid in {$weixin_huoyuan_catid}") -> order("create_time desc") ->limit('12') -> select());
		
		$article = D('article');
		
		$art_cate = D('category');
		
		$cate_info150 = $art_cate -> field("id,catname") -> where(array('id'=>'150')) -> find();
		$cate_info142 = $art_cate -> field("id,catname") -> where(array('id'=>'142')) -> find();
		$cate_info55  = $art_cate -> field("id,catname") -> where(array('id'=>'55')) -> find();
		
		
		
		
		//热门问答
		$this -> assign('remenwenda',$article -> field("id,title") -> where("catid = 149 ") -> order("id desc") -> limit(7) -> select());
		
		
		//首页文章左
		$this -> assign('weixinyingxiao',$article -> field("id,title") -> where("catid = 142 ") -> order("id desc") -> limit(9) -> select());
		$this -> assign('weixinyingxiao_cat',$cate_info142);
		//首页文章中
		$this -> assign('weixinzixun',$article -> field("id,title") -> where("catid = 55 ") -> order("id desc") -> limit(9) -> select());
		$this -> assign('weixinzixun_cat',$cate_info55);
		//首页文章右
		$this -> assign('weixinxuetang',$article -> field("id,title") -> where("catid = 150 ") -> order("id desc") -> limit(9) -> select());
		$this -> assign('weixinxuetang_cat',$cate_info150);
		
		
        //快速导航
        $this -> assign('kuaisudaohang',$article -> field("id,title") -> where("catid = 148 ") -> order("id desc") -> limit(9) -> select());

		//热词搜索
		$this -> assign('recisousuo',D('search') -> where("status = 1") -> limit(20) -> select());
		
        //友情链接
        $model=D('Link');
        $list=$model->order('listorder asc')->select();
        $this->list=$list;
        
        $this->assign('allcount', $allcount);
        $this->assign('daycount', $daycount);
        $position[]=array('id'=>0,'catname'=>'首页');
        $this->seo(C(SITE_TITLE), C(SITE_KEYWORDS), C(SITE_DESCRIPTION), $position);

        Cookie::set('_curUrl_', __SELF__);
        session('_curUrl_', __SELF__);
        $this->prefix=C('DB_PREFIX');//表前缀
        $this->display();
    }
    public function index2(){

        $wxModel=M('Weixin');
        $wxModelMap['status']=array('eq',1);
        //总共收录公众帐号数
        $allcount=$wxModel->where($wxModelMap)->count();

        //今日收录帐号数
        $daycount=$wxModel->where('status=1 and date(from_unixtime(create_time))=CURDATE() ')->count();
        
        //热门推荐
        $prefix=C('DB_PREFIX');
        //获取热门推荐的公号
        $time=time();
        
        $where['starttime']=array('elt',$time);
        $where['endtime']=array('egt',$time-(24*3600));
        $where['_logic'] = 'and';
        $mapa['_complex'] = $where;
        $mapa['timelimit']=array('eq',0);
        $mapa['_logic'] = 'or';
        $map['_complex'] = $mapa;

        $map[$prefix.'tuijian.status']=array('eq',1);
        $map['recommendid']=array('eq',1);

        //取所有热门推荐数据
        $name = $this->getActionName();

        $map[$prefix.'weixin.status']=array('eq',1);
        //获取分页设置
        $Model=M('Model');
        $modelmap['table']=array('eq','Weixin');
        $pageinfo=$Model->where($modelmap)->find();

        $tuijian=M('Tuijian');
        import("@.ORG.Page");       //导入分页类
        $count  = $tuijian->join($prefix.'weixin ON '.$prefix.'weixin.id='.$prefix.'tuijian.wxid')->where($map)->count();    //计算总数
//        $Page = new Page($count, $pageinfo['listrows']);
        $Page = new Page($count, 7);
//        $list=$tuijian->join($prefix.'weixin ON '.$prefix.'weixin.id='.$prefix.'tuijian.wxid')->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->field($prefix.'weixin.*')->order('timelimit desc,'.$prefix.'tuijian.create_time desc')->select();
        $tjlist=$tuijian->join($prefix.'weixin ON '.$prefix.'weixin.id='.$prefix.'tuijian.wxid')->where($map)->field($prefix.'weixin.*')->order('timelimit desc,'.$prefix.'tuijian.create_time desc')->limit('7')->select();
        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
//        $this->assign("tjpage", $page);
        $this->assign("tjlist", $tjlist);
        
        //友情链接
        $model=D('Link');
        $list=$model->order('listorder asc')->select();
        $this->list=$list;
        
        $this->assign('allcount', $allcount);
        $this->assign('daycount', $daycount);
        $position[]=array('id'=>0,'catname'=>'首页');
        $this->seo(C(SITE_TITLE), C(SITE_KEYWORDS), C(SITE_DESCRIPTION), $position);

        Cookie::set('_curUrl_', __SELF__);
        session('_curUrl_', __SELF__);
        $this->prefix=C('DB_PREFIX');//表前缀
        $this->display();
    }
   

}