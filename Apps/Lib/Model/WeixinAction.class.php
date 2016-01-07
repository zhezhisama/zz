<?php

class WeixinAction extends CommonAction{
    public function index() {
        //分类id
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }

        $select_option = array();
        $parameter = array();
        //获取topid
        $select_option['id'] = $id;
        $parameter['id'] = $id;

        $catid = I('get.catid',0,'int');
        $select_option['catid'] = $catid;
        $parameter['catid'] = $catid;

        $catid = $catid > 0 ? $catid : $id; 
        //获取所有子类id
        $catlist = D('Category')->where('status=1')->select();  
        $idlist = $catid.','.  getChildId($catlist,$catid);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        $map['w.catid'] = array('in',$idlist);

        //当前分类
        $catdata = D('Category')->where('status=1')->find($id); 

        //获取下级子分类
        $childCatMap['pid']=array('eq',$id);
        $childCatMap['status']=array('eq',1);
        $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
        
        //获取省份
        $province = I('get.province','int');
        
        if($province > 0)
        {
            //省份名
            $province_name=getAreasName($province);

            //获取下级子地区
            $childAreaMap['parent_id']=array('eq',$province);
            $childAreaList=D('Areas')->where($childAreaMap)->select();
            $this->assign('childAreaList',$childAreaList);
            $this->assign('province_name',$province_name);
            $map['w.province'] = array('eq',$province);
        }

        //获取城市
        $area = I('get.area',0,'int');

        if($area > 0){
            $area_array = $this->getArea($area);
            if($area_array['area_type'] == 1)
            {
                $this->assign('city',$area_array['child']);
            }
            $map['w.city'] = array('eq',$area);
        }
        
        $select_option['area'] = $area;
        $parameter['area'] = $area;

        //获取排列方式
        $display = I('get.display','grid','string');
        $select_option['display'] = $display;
        $parameter['display'] = $display;
       
        //获取相关时间段
        $between = I('get.between',0,'int');
        if($between > 0)
        {
            $now = time();
            $target_time = $now+(86400*$between);
            $map['w.creat_time'] = array('between',array($now,$target_time));
        }
       
        $select_option['between'] = $between;
        $parameter['between'] = $between;
        
        //获取排序
        $o = I('get.o',3,'int');
        $order = $this->get_order($o);

        $select_option['o'] = $order['o'];
        $parameter['o'] = $o;

        //分类导航
        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->assign('childCatList', $childCatList);
        
        
        if(isset($_GET['tag'])){
            $tag=I('get.tag');
            if(!empty($tag)){
                $map['w.tag']=array('like','%'.$tag.'%');
            }
        }
        
        $name = $this->getActionName();
       
        //获取分页设置
        $Model=M('Model');
        $mapModel['table']=array('eq',$name);
        $mapModel['status']=array('eq',1);
        
        $pageinfo=$Model->where($mapModel)->find();

        $map['w.status']=array('eq',1);
        $prefix=C('DB_PREFIX');//表前缀

        $Form   =   M($name);
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->Table($prefix.'weixin w')->join('(select * from '.$prefix.'tuijian WHERE recommendid=3 AND (timelimit=0 OR endtime>=unix_timestamp(CURDATE())) AND status=1) t ON w.id=t.wxid')->where($map)->count();    //计算总数
        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->Table($prefix.'weixin w')->join('(select * from '.$prefix.'tuijian WHERE recommendid=3 AND (timelimit=0 OR endtime>=unix_timestamp(CURDATE())) AND status=1) t ON w.id=t.wxid')->where($map)->field('w.id,w.pubaccount,w.logo,w.weblogo,w.qrcode,w.webqrcode,w.content,w.tag,w.xingji,w.hits,w.status,t.recommendid,w.create_time,IFNULL(t.create_time,9369311152) as tjtime')->limit($Page->firstRow. ',' . $Page->listRows)->order($order['order'])->select();
        
        foreach ($list as $k => $v) {
            $list[$k]['past_time'] = $this->pastTime($v['create_time']);
        }

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();

        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        
        
        //热门城市
        $maprecommend['recommend']=array('eq',1);
        $maprecommend['area_type']=array('eq',2);
        $recommendlist=D('Areas')->where($maprecommend)->select();
        $this->assign('recommendlist',$recommendlist);

        //选择省份
        $map_province['area_type']=array('eq',1);
        $province = D('Areas')->where($map_province)->select();

        $this->assign('province',$province);
        $this->assign("data", $catdata);
        $this->assign('so', $select_option);
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->assign('count',$count);
        $this->display(); 
    }

    /*
    public function index() {
      
        if(isset($_GET['id'])){
            
            //分类id
            $id =I('get.id','','int');
            if(empty($id)){
                $this->error('操作错误');
            }

            //当前分类
            $catdata = D('Category')->where('status=1')->find($id);	

            //获取下级子分类
            $childCatMap['pid']=array('eq',$id);
            $childCatMap['status']=array('eq',1);
            $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
           
            //获取所有子类id
            $catlist = D('Category')->where('status=1')->select();	
            $idlist = $id.','.  getChildId($catlist,$id);  
            $idlist= substr($idlist, 0, strlen($idlist)-1);
            $map['w.catid'] = array('in',$idlist);

            
            //分类导航
            $position=D('Common')->getPosition($id);
            foreach ($position as $value) {
                $title=$value['catname']."_".$title;
            }
            $title=  substr($title, 0, strlen($title)-1);
            $this->assign('childCatList', $childCatList);
            
        }else if(isset ($_GET['province'])){
            
            //分类id
            $id =I('get.catid','','int');
            if(empty($id)){
                $this->error('操作错误');
            }
            
            //分类名
            $catname=  getCategoryName($id);
            
            //省份
            $province =I('get.province','','int');
            if(empty($province)){
                $this->error('操作错误');
            }
            //省份名
            $cityname=getAreasName($province);
            
            //获取下级子地区
            $childAreaMap['parent_id']=array('eq',$province);
            $childAreaList=D('Areas')->where($childAreaMap)->select();

            $where['province'] = array('eq',$province);
            $where['address']=array('like','%'.$cityname.'%');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
            $position[] = array('id'=>$id,'catname'=>$catname);
            $positionarea[] = array('id'=>$id,'province'=>$province,'areaname'=>$cityname);
            foreach ($position as $value) {
                $title=$value['catname']."_".$title;
            }
            $title=  $cityname."_".$title;
            $title=  substr($title, 0, strlen($title)-1);
            
            $this->assign('positionarea',$positionarea);
            $this->assign('childAreaList', $childAreaList);
            
        }else if(isset ($_GET['city'])){
            //分类id
            $id =I('get.catid','','int');
            if(empty($id)){
                $this->error('操作错误');
            }
            
            //分类名
            $catname=  getCategoryName($id);
            
            //城市id
            $city=I('get.city','','int');
            if(empty($city)){
                $this->error('操作错误');
            }
            //城市名称
            $cityname=getAreasName($city);
            
            $where['city'] = array('eq',$city);
            $where['address']=array('like','%'.$cityname.'%');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
            
            $position[] = array('id'=>$id,'catname'=>$catname);
            $positionarea=D('Common')->getAreaPosition($city);
            
            foreach ($positionarea as $value) {
                
                if('0'!==$value['level']){
                    $title=$value['areaname']."_".$title;
                }
                
            }
            $title=  substr($title, 0, strlen($title)-1);
            
            $this->assign('positionarea',$positionarea);
            $this->assign('childAreaList', $childAreaList);
            
        }
        
        if(isset($_GET['tag'])){
            $tag=I('get.tag');
            if(!empty($tag)){
                $map['tag']=array('like','%'.$tag.'%');
            }
        }
        
        $name = $this->getActionName();
       
        //获取分页设置
        $Model=M('Model');
        $mapModel['table']=array('eq',$name);
        $mapModel['status']=array('eq',1);
        
        $pageinfo=$Model->where($mapModel)->find();

        $map['w.status']=array('eq',1);
        $prefix=C('DB_PREFIX');//表前缀
        
        $Form   =   M($name);
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->Table($prefix.'weixin w')->join('(select * from '.$prefix.'tuijian WHERE recommendid=3 AND (timelimit=0 OR endtime>=unix_timestamp(CURDATE())) AND status=1) t ON w.id=t.wxid')->where($map)->count();    //计算总数

        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->Table($prefix.'weixin w')->join('(select * from '.$prefix.'tuijian WHERE recommendid=3 AND (timelimit=0 OR endtime>=unix_timestamp(CURDATE())) AND status=1) t ON w.id=t.wxid')->where($map)->field('w.id,w.pubaccount,w.logo,w.weblogo,w.qrcode,w.webqrcode,w.content,w.tag,w.xingji,w.hits,w.status,t.recommendid,IFNULL(t.create_time,9369311152) as tjtime')->limit($Page->firstRow. ',' . $Page->listRows)->order('tjtime ASC')->select();
  
        
        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();

        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        
        
        //热门城市
        $maprecommend['recommend']=array('eq',1);
        $recommendlist=D('Areas')->where($maprecommend)->select();
        $this->assign('recommendlist',$recommendlist);

        //选择省份
        $map_province['area_type']=array('eq',1);
        
        $province = D('Areas')->where($map_province)->select();
        $this->assign('province',$province);

        $this->assign('catid', $id);
        $this->assign("data", $catdata);
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->display(); 
    }

    */
	
	public function huoyuan()
	{	
		$type_list = D('category') -> where("pid = 1") -> select();
		$this -> assign('type_list',$type_list);
		
		if($_GET['catid'] && $_GET['catid'] > 0)
		{	
			$ids = (int)$_GET['catid'];
		}
		else
		{
			foreach( $type_list as $k => $v)
			{
				$a[] = $v['id'];
			}
			$ids = implode(',',$a);
		}
		
		$title_id = $ids;
		$id =I('get.id','','int');
        if(!empty($id)){
            $title_id = $id;
        }else{
			$title_id = $ids;
		}
		
		//当前分类
        $catdata = D('Category')->where('status=1')->find($title_id); 
		//分类导航
        $position=D('Common')->getPosition($title_id);
		$foo = $catdata['title'];
		$foo = explode(',',$foo);
		$catdata['title'] = '';
		$title = $foo[0];
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
		
		import("@.ORG.Page"); 
		$weixin = D('weixin');
		$count = $weixin -> where("catid in ($ids)") -> count();
		$Page = new Page($count,30);
		$list  = $weixin -> where("catid in ($ids)") -> order("id desc") -> limit($Page->firstRow,$Page->listRows) -> select();
		$this -> assign('list',$list);
		$this -> assign('page',$Page->show());
		$this -> display();
	}
	
	public function huoyuanshow()
	{
		$id = (int)$_GET['id'];
		if($id < 1)
		$this -> error("数据错误！");
		else
		$ids = $id;
		//当前分类
        $catdata = D('weixin')->find($ids); 
		//分类导航
        $position=D('Common')->getPosition($ids);
		$foo = $catdata['title'];
		$foo = explode(',',$foo);
		$catdata['title'] = '';
		$title = $foo[0];
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
		
		$da = D('weixin') -> where("id = '{$id}'  ") -> find();

        D('weixin') -> query("update wxq_weixin set hits=hits+1 where id = '{$id}' ");

		$this -> assign('da',$da);
		//相关评论
		 $this -> assign('count',D('comment') -> where("objectid = '{$id}' ") -> count());
        $list = D('comment') -> where("objectid = '{$id}' ") -> select();
        $this -> assign('list',$list);
		

		//上一条
		$this -> assign('beforeda',D('weixin') -> where("id > '{$id}' ") -> order("id asc ") -> limit(1)->find());
		//xia一条
		$this -> assign('afterda',D('weixin') -> where("id < '{$id}' ") -> order("id desc ") -> limit(1)->find());
		$this -> display();
	}

    public function search()
    {
        if(IS_GET){
            $search =I('get.search','','urldecode,strip_tags,htmlspecialchars');
        }
        if(IS_POST){
            $search =I('post.search','','strip_tags,htmlspecialchars');
        }
        import("@.ORG.Page"); 
        $weixin = D('weixin');
        if($search)
        {
            $where = " (title like '%{$search}%' or content like '%{$search}%') and status = 1  ";
            $_where = "(w.title like '%{$search}%' or w.content like '%{$search}%') and w.status = 1 and c.pid > 0";
        }
        else
        {
            $where = " status = 1  ";
            $_where = "w.status = 1 and c.pid > 0 ";
        }
        $count = $weixin -> where($where) -> count();
        $Page =  new Page($count,20);
        $sql = "select w.*,c.pid  from wxq_weixin w 
         left join wxq_category c on w.catid = c.id 
         where {$_where} 
         order by w.id desc
         limit {$Page->firstRow},{$Page->listRows}  ";
        $list = $weixin -> query($sql);
        
        $now = time();
        foreach($list as $k => $v)
        {
            //$da_1 = D('category') -> where("id = '{$v['pid']}' ") -> find(); 
            if($v['pid'] == 44)
                $list[$k]['weixin_type_name'] = '微信群';
            if($v['pid'] == 1)
                $list[$k]['weixin_type_name'] = '微商货源';
            if($v['pid'] == 48)
                $list[$k]['weixin_type_name'] = '个人微信';
            if($v['pid'] == 47)
                $list[$k]['weixin_type_name'] = '微信公众号';
            $a = round(( $now - $v['create_time']) / 60) ;
            if($a > 60)
            {
                $list[$k]['shangchuan_time'] = '<font class="fs">'.round($a/60).'</font>'.'小时前上传';
            }
            else
            {
                $list[$k]['shangchuan_time'] = '<font class="fs">'.$a.'</font>'.'分钟前上传';  
            }
        }

       // var_dump($list);exit;
        $this -> assign('list',$list);
        $this -> assign('page',$Page->show());
        $this -> display();

    }

    public function search11123() {

        /*$id =I('get.id','','int');
        
        if(empty($id)){
            $this->error('操作错误');
        }
         //当前栏目分类
        $catdata = D('Category')->where('status=1')->find($id);	
        $this->assign(id, $id);*/
        
        //关键字
        if(IS_GET){
            $keyword =I('get.keyword','','urldecode,strip_tags,htmlspecialchars');
            
        }
        if(IS_POST){
            $keyword =I('post.search','','strip_tags,htmlspecialchars');
            
        }
        
        if(!empty($keyword)){ 
            
            $where['pubaccount'] =array('like','%'.$keyword.'%');
            $where['wxaccount'] = array('like','%'.$keyword.'%');
            $where['_logic'] = 'OR';
            $map['_complex'] = $where;

            //搜索词处理
            $mapsearch["search"]=array('eq',$keyword);
            $Search=M('Search');
            $searchInfo=$Search->where($mapsearch)->find();

            if($searchInfo){
                D('Search')->where($mapsearch)->setInc('hits',1);//浏览次数
            }else{
                
                $_POST['search']=$keyword;
                $searchvo=$Search->create();
                if (false === $searchvo) {
                    $this->error($Search->getError());
                }

                $Search->hits=1;
                $Search->status=1;
                $Search->create_time=time();
                //保存当前数据对象
                $searchList = $Search->add();
                
            }

            $this->assign(text, urlencode($keyword));
        }
        
        //选择分类
        $catid =I('catid','','int');
        if(!empty($catid)){
            
            //获取所有子类id
            $catlist = D('Category')->where('status=1')->select();	
            $idlist = $catid.','.  getChildId($catlist,$catid);  
            $idlist= substr($idlist, 0, strlen($idlist)-1);
            $map['catid'] = array('in',$idlist); 
            
            $curcat = D('Category')->where('status=1')->find($catid);
            if($curcat['level']==2){
                
                $this->assign(parentcatid, $catid);//父级分类

                //下级分类
                $mapcat['status']=array('eq',1);
                $mapcat['pid']=array('eq',$catid);
                $childcatlist=D('Category')->where($mapcat)->order('listorder')->select();	 
                $this->assign(childcatlist, $childcatlist);
                
            }else{
                $this->assign(parentcatid, $curcat['pid']);//父级分类
                $this->assign(catid, $catid);
                
                //下级分类
                $mapcat['status']=array('eq',1);
                $mapcat['pid']=array('eq',$curcat['pid']);
                $childcatlist=D('Category')->where($mapcat)->order('listorder')->select();	 
                $this->assign(childcatlist, $childcatlist);
            }

        }


        //省份id
        $province =I('province','','int');
        if(!empty($province)){
            //省份名
            $cityname=getAreasName($province);
            $where['province'] = array('eq',$province);
            $where['address']=array('like','%'.$cityname.'%');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
            $this->assign(province, $province);//省份id
            
             //下级城市
            $mapcity['parent_id'] = array('eq',$province);
            $citylist=M('Areas')->where($mapcity)->select();
            $this->assign(citylist,$citylist);
            
            
        }

        //城市id
        $city=I('city','','int');
        if(!empty($city)){
            //城市名称
            $cityname=getAreasName($city);
            $where['city'] = array('eq',$city);
            $where['address'] =array('like','%'.$cityname.'%');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
            $this->assign(city, $city);
        }
        
        
        $name = $this->getActionName();

        //获取分页设置
        $Model=M('Model');
        $mapmodel['table']=array('eq',$name);
        $pageinfo=$Model->where($mapmodel)->find();

        $Form   =   M($name);

        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数

        $Page = new Page($count, $pageinfo['listrows']);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('create_time desc')->select();

        //分页跳转的时候保证查询条件
        $Page -> parameter .= "id=".$id."&";
        if(!empty($catid)){
            $Page -> parameter .= "catid=".$catid."&";
        }
        if(!empty($keyword)){
            $Page -> parameter .= "keyword=".urlencode($keyword)."&";
        }
        if(!empty($province)){
            $Page -> parameter .= "province=".$province."&";
        }
        if(!empty($city)){
            $Page -> parameter .= "city=".$city."&";
        }
        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();

        $this->assign("page", $page);
        $this->assign("list", $list);

        
        //加载分类
        $cate=new WeixinModel();
        $menu =$cate->getModelCategory('Weixin'); 
        $categorylist=  arrToMenu($menu,0);
        $this->categorylist=$categorylist;

        //获取省级地区
        $provincelist=D('areas')->where(array('area_type'=>1))->select();
        $this->assign('provincelist',$provincelist);

        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        
        if(!empty($keyword)){
            $title=$keyword.'_'.$title;
        }
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        $this->assign("data", $catdata);
        $this->display(); 
        
    }
    
    //地区微信
    public function area() {
        
        
//        import('@.ORG.Net.IpLocation');// 导入IpLocation类
//        $IpL = new IpLocation(); // 实例化类
//        $location = $IpL->getlocation(); // 获取某个IP地址所在的位置
//        $country = iconv('gbk','utf-8',$location['country']);
//        $countryarea =iconv('gbk','utf-8',$location['area']);
//        $this->assign('country',$country);
//        $this->assign('countryarea',$countryarea);
        
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }
        $catdata = D('Category')->where('status=1')->find($id);	
        
        //热门城市
        $maprecommend['recommend']=array('eq',1);
        $recommendlist=D('Areas')->where($maprecommend)->select();
        $this->assign('recommendlist',$recommendlist);
        
        //选择省份
        $map['area_type']=array('eq',1);
        $map['recommend']=array('neq',1);
        
        $arealist = D('Areas')->where($map)->select();
        if(is_array($arealist)){
            foreach ($arealist as $key=>$val){
                $mapsub['parent_id']=array('eq',$val['id']);
                $mapsub['recommend']=array('neq',1);
                $arealist[$key]['subarealist'] = D('Areas')->where($mapsub)->select();//选择城市
            }
        }
        $this->assign('arealist',$arealist);
        
        //seo
        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        
        $this->assign("data", $catdata);
        $this->display();
    }
    //热门推荐
    public function hot() {
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }
        $where = "1";
        $select_option['id'] = $id;
        $catdata = D('Category')->where('status=1')->find($id);	
        
        $catid = I('get.catid',0,'int');
        $select_option['catid'] = $catid;
        $catid = $catid > 0 ? $catid : $id; 

        //获取所有子类id
        $catlist = D('Category')->where('status=1')->select();  
        $idlist = $catid.','.  getChildId($catlist,$catid);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        //$map['w.catid'] = array('in',$idlist);
        $where .= " AND w.catid in ($idlist)";

        if(isset($_GET['o']) && trim($_GET['o']))
        {
            $o = trim($_GET['o']) ? trim($_GET['o']): 'create_time' ;
            $order_array = array('create_time','hits','xh');
            if(!in_array($o, $order_array))
                $this -> error("数据错误！");
            $select_option['o'] = $o;
            $order = " w.{$o} desc ";
        }

        //获取相关时间段
        $between = I('get.between',0,'int');
        if($between > 0)
        {
            $now = time();
            $target_time = $now-(86400*$between);
            
        }

        $select_option['between'] = $between;
        $time=time();
        /*
        $where['t.starttime']=array('elt',$time);
        $where['t.endtime']=array('egt',$time-(24*3600));
        $where['_logic'] = 'and';
        $mapa['_complex'] = $where;
        $mapa['t.timelimit']=array('eq',0);
        $mapa['_logic'] = 'or';
        $mapb['_complex'] = $mapa;
        $mapb['t.creat_time'] = array('between',array($now,$target_time));
        $mapb['_logic'] = 'and';
        $map['_complex'] = $mapb;
        */
        $time_where = " AND ((t.starttime <= $time AND t.endtime >= $time) OR (t.timelimit = 0)) ";
        $where .= $time_where;
        $target_time > 0 ? $where .= " AND (t.create_time <= $now AND t.create_time >=$target_time) " : '';
        $prefix=C('DB_PREFIX');
        //获取热门推荐的公号
        
        /*
        $map['t.status']=array('eq',1);
        $map['t.recommendid']=array('eq',1);
        */
        $where .= " AND t.status = 1 AND t.recommendid = 1 ";
        //取所有热门推荐数据
        $name = $this->getActionName();

        //获取分页设置
        $Model=M('Model');
        $modelmap['table']=array('eq',$name);
        $pageinfo=$Model->where($modelmap)->find();

        $tuijian=M('Tuijian');
        import("@.ORG.Page");       //导入分页类
        $count  = $tuijian->Table($prefix.'tuijian t')->join($prefix.'weixin w ON w.id=t.wxid')->where($where)->count();    //计算总数
//        $Page = new Page($count, $pageinfo['listrows']);
        $Page = new Page($count, 42);
        $list = $tuijian->Table($prefix.'tuijian t')->join($prefix.'weixin w ON w.id=t.wxid')->where($where)->field('w.*')->order($order)->limit($Page->firstRow. ',' . $Page->listRows)->select();

        foreach ($list as $k => $v) {
            $list[$k]['past_time'] = $this->pastTime($v['create_time']);
        }

        // 设置分页显示
        $Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);
        $page = $Page->show();
        
        $this->assign("page", $page);
        $this->assign("list", $list);
        $this->assign("data", $catdata);
        $this->assign("so", $select_option);
        $this->assign("count", $count);
        //获取下级子分类
        $childCatMap['pid']=array('eq',$id);
        $childCatMap['status']=array('eq',1);
        $childCatList=D('Category')->where($childCatMap)->order('listorder')->select();
        $this->assign("childCatList", $childCatList);
        //seo
        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        
        $this->display();
    }
    //关注排行榜
    public function order() {
    
        $where = " status = 1 ";$order = "hits desc";
        if(isset($_GET['catid']) &&  trim($_GET['catid']))
        {
            $catid = (int)$_GET['catid'];
        }
        else
        {
            $catid = 44;
        }
		

		if((int)$_GET['catid'] && (int)$_GET['wcatid']){
			$ids = (int)$_GET['wcatid'];
		}else if((int)$_GET['catid'] && !(int)$_GET['wcatid']){
			$ids = (int)$_GET['catid'];
		}else if((int)$_GET['id'] && !(int)$_GET['catid'] && !(int)$_GET['wcatid']){
			$ids = (int)$_GET['id'];
		}
		
		//当前分类
        $catdata = D('Category')->where('status=1')->find($ids); 
		//分类导航
        $position=D('Common')->getPosition($ids);
		$foo = $catdata['title'];
		$foo = explode(',',$foo);
		$catdata['title'] = '';
		$title = $foo[0];
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
		
		
        $catidlist = D('category') -> where("pid = '{$catid}' ") -> select();
        $this -> assign('catidlist',$catidlist);
        foreach($catidlist as $k => $v)
        {
            $catid_t[] = $v['id'];
        }
        $catids = "(".implode(',',$catid_t).")";

        //还有下而没有上
        if(isset($_GET['wcatid']) && trim($_GET['wcatid']))
        {
            $wcatid = (int)$_GET['wcatid'];
            $this -> assign('wcatid',$wcatid);
            $catids = "(".$wcatid.")";
        }
        $where .= " and catid in {$catids} ";
        $this -> assign('catid',$catid);
        $name = $this->getActionName();

        if(isset($_GET['or']) && trim($_GET['or']))
        {
            $or = trim($_GET['or']);
            if(!in_array($or, array('hits','xh')))
                $this -> error("数据错误！");
            $this -> assign('or',$or);
            $order = " {$or} desc ";
        }

        if(isset($_GET['time']) && trim($_GET['time']) > 0)
        {
            $time = (int)$_GET['time'];
            if(!in_array($time,array(3,7,30)))
            {
                $this -> error("时间数据错误！");
            }
            $this -> assign('time',$time);
            $newtime = time() - $time * 3600*24;
            $where .= " and create_time > '{$newtime}' ";
        }

        //获取分页设置
        /*$Model=M('Model');
        $map['table']=array('eq',$name);
        $pageinfo=$Model->where($map)->find();*/

        $Form   =   M($name);
        import("@.ORG.Page");       //导入分页类
        $count  = D('weixin')->where($where)->count();    //计算总数

        $Page = new Page($count, 42);
        $list   = D('weixin')->where($where)->limit($Page->firstRow. ',' . $Page->listRows)->order($order)->select();

        //var_dump($list);var_dump(D('weixin') -> getLastSql());exit;

        // 设置分页显示
        /*$Page->setConfig('header', $pageinfo['header']);
        $Page->setConfig('first', $pageinfo['first']);
        $Page->setConfig('last', $pageinfo['last']);
        $Page->setConfig('prev', $pageinfo['prev']);
        $Page->setConfig('next', $pageinfo['next']);
        $Page->setConfig('theme',$pageinfo['theme']);*/
        $page = $Page->show();
        
        $this->assign("data", $catdata);
        $this->assign("page", $page);
        $this->assign("list", $list);
         //seo
        /*$position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);*/
        
        $this->display();
    }
    public function turnshow()
    {
        $id = (int)$_GET['id'];
        if($id < 1)
            $this -> error("数据错误！");
        $da = D('weixin') -> where("id = '{$id}' ") -> find();
        if($da['status'] != 1)
        {
            $this -> error("该数据不可用！");
        }
        $da_t = D('category') -> where("id = '{$da['catid']}' ") -> find();
        if($da_t['pid'] == 1)
        {
            header("location:".U('Weixin/huoyuanshow',array('id'=>$id)));exit;
        }
        else
        {
            header("location:".U('Weixin/show',array('id'=>$id)));exit;
        }
    }
    //最新收录
    public function news() {
    
        $id =I('get.id','','int');
        if(empty($id)){
            $this->error('操作错误');
        }
        $catdata = D('Category')->where('status=1')->find($id);	
        
        $name = $this->getActionName();
        $map['status']=array('eq',1);
        
        //获取分页设置
        $Model=M('Model');
        $map['table']=array('eq',$name);
        $pageinfo=$Model->where($map)->find();

        $Form   =   M($name);
        import("@.ORG.Page");       //导入分页类
        $count  = $Form->where($map)->count();    //计算总数
//        $Page = new Page($count, $pageinfo['listrows']);
        $Page = new Page($count, 42);
        $list   = $Form->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->order('create_time desc')->select();

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
        //seo
        $position=D('Common')->getPosition($id);
        foreach ($position as $value) {
            $title=$value['catname']."_".$title;
        }
        $title=  substr($title, 0, strlen($title)-1);
        $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
        
        $this->display();
    }
    
    //提交微信
    public function add() {
        if(IS_POST){
            
            //鱼福标识码
            $yufumark=I('post.yufumark');
            
            //是否游客提交
            if(!C('ISADDACCOUNT')){
                if(C('ISAUTOVERIFY')=="1"){
                    if(trim(C('YUFUMARK'))!=trim($yufumark)){
                        $this->error('操作错误');
                    }
                }else{
                    $this->error('操作错误');
                }
                
            }
            $ip=get_client_ip();
            $time=time();
            $map['ip']=array('eq',$ip);
            
            //提交间隔
            $model = D('Weixin');
            $comment=$model->where($map)->order('id desc')->find();
            if($time-$comment['create_time']<10){
                $this->error('每次提交需间隔10秒钟!');
            }

            $_POST['pubaccount']=I('post.pubaccount');
            $_POST['wxaccount']=I('post.wxaccount');
            $_POST['ghweixin']=I('post.ghweixin');
            $_POST['website']=I('post.website');
            $_POST['sinaweibo']=I('post.sinaweibo');
            $_POST['tencentweibo']=I('post.tencentweibo');
            $_POST['title']=I('post.title');
            $_POST['keywords']=I('post.keywords');
            $_POST['description']=I('post.description');
            $_POST['weblogo']=I('post.weblogo');
            $_POST['webqrcode']=I('post.webqrcode');
            $_POST['tbshopurl']=I('post.tbshopurl');
            $_POST['ppshopurl']=I('post.ppshopurl');
            $_POST['content']=I('post.content');
            $_POST['realname']=I('post.realname');
            $_POST['phone']=I('post.phone');
            $_POST['qq']=I('post.qq','','int');
            
            
            
            //如果标题为空，默认填写公众帐号
            if(empty($_POST['title'])){
                $_POST['title']=  I('post.pubaccount');
            }
            
            //敏感词过滤
            $Badword=D('Badword');
            $Badwordlist=$Badword->select();
            foreach ($Badwordlist as $key => $value) {
                if($value['level']==1){
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['wxaccount']);
                    $_POST['ghweixin']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['ghweixin']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i',$value['weblogo'], $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i',$value['webqrcode'], $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i',$value['tbshopurl'], $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i',$value['ppshopurl'], $_POST['ppshopurl']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i',$value['replaceword'], $_POST['qq']);
                }else{
                    $_POST['pubaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['pubaccount']);
                    $_POST['wxaccount']=preg_replace('/'.$value['badword'].'/i','', $_POST['wxaccount']);
                    $_POST['website']=preg_replace('/'.$value['badword'].'/i','', $_POST['website']);
                    $_POST['sinaweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['sinaweibo']);
                    $_POST['tencentweibo']=preg_replace('/'.$value['badword'].'/i','', $_POST['tencentweibo']);
                    $_POST['title']=preg_replace('/'.$value['badword'].'/i','', $_POST['title']);
                    $_POST['keywords']=preg_replace('/'.$value['badword'].'/i','', $_POST['keywords']);
                    $_POST['description']=preg_replace('/'.$value['badword'].'/i','', $_POST['description']);
                    $_POST['weblogo']=preg_replace('/'.$value['badword'].'/i','', $_POST['weblogo']);
                    $_POST['webqrcode']=preg_replace('/'.$value['badword'].'/i','', $_POST['webqrcode']);
                    $_POST['tbshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['tbshopurl']);
                    $_POST['ppshopurl']=preg_replace('/'.$value['badword'].'/i','', $_POST['ppshopurl']);
                    $_POST['content']=preg_replace('/'.$value['badword'].'/i','', $_POST['content']);
                    $_POST['realname']=preg_replace('/'.$value['badword'].'/i','', $_POST['realname']);
                    $_POST['phone']=preg_replace('/'.$value['badword'].'/i','', $_POST['phone']);
                    $_POST['qq']=preg_replace('/'.$value['badword'].'/i','', $_POST['qq']);
                }
                
            }
            
            //上传附件
            $this->_upload();
            
            if (false === $model->create()) {
                $this->error($model->getError());
            }
            if(C('ISAUTOVERIFY')=="1"){
                if(trim(C('YUFUMARK'))==trim($yufumark)){
                    $model->status=1; 
                }else{
                    $model->status=2; 
                }
                
            }else{
               $model->status=2; 
            }
            $model->typeid=1;
            $model->iscomment=1;
            //保存当前数据对象
            
            $list = $model->add();
            if ($list !== false) { //保存成功
                $this->success('提交成功!');
            } else {
                //失败提示
                $this->error('提交失败!');
            }
            
        }  else {
            
            if(!C('ISADDACCOUNT')){
                $this->error('操作错误','__APP__');
            }
            $id = I('get.id','','int');
            if(empty($id)){
                $this->error('操作错误');
            }
            $catdata = D('Category')->where('status=1')->find($id);	
            
            $cate=new WeixinModel();
            $menu =$cate->getModelCategory('Weixin'); //加载栏目
            $this->categorylist=arrToMenu($menu,0);  
            
            
            //获取省级地区
            $province=D('areas')->where(array('parent_id'=>1))->select();
            $this->assign('province',$province);
            
            //seo
            $position=D('Common')->getPosition($id);
            foreach ($position as $value) {
                $title=$value['catname']."_".$title;
            }
            $title=  substr($title, 0, strlen($title)-1);
            $this->seo(($catdata['title'])?$catdata['title']:$title, ($catdata['keywords'])?$catdata['keywords']:C(SITE_KEYWORDS), ($catdata['description'])?$catdata['description']:C(SITE_DESCRIPTION), $position);
            $this->assign("data", $catdata);
            $this->display();
        }
        
    }
    // 检查公众帐号
    public function checkPubAccount($id=NULL) {

        $User = M("Weixin");
        //检测用户名是否冲突
        $name  = I('get.pubaccount'); 
        if(isset($id)){
            $map['id']=array('neq',  intval($id));
        }

        $result  =  $User->where($map)->getFieldByPubaccount($name,'status');
        if($result==null){
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }else{
            $data['status'] = 1;
            $data['info'] = $result;
            $this->ajaxReturn($data);
        }

    }
    // 检查关联微信号
    public function checkwxaccount($id=NULL) {

        $User = M("Weixin");
        //检测用户名是否冲突
        $name  =  I('get.wxaccount');
        if(isset($id)){
            $map['id']=array('neq',  intval($id));
        }
        
        $result  =  $User->where($map)->getFieldByWxaccount($name,'status');
        if($result==null){
            //不存在
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }else{
            //存在
            $data['status'] = 1;
            $data['info'] = $result;
            $this->ajaxReturn($data);
        }
        
    }
    // 检查微信原始号
    public function checkghweixin($id=NULL) {

        $User = M("Weixin");
        //检测用户名是否冲突
        $name  =  I('get.ghweixin');
        if(isset($id)){
            $map['id']=array('neq',  intval($id));
        }
        
        $result  =  $User->where($map)->getFieldByGhweixin($name,'status');
        if($result==null){
            //不存在
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }else{
            //存在
            $data['status'] = 1;
            $data['info'] = $result;
            $this->ajaxReturn($data);
        }
        
    }
    //喜欢
    public function xh() {
        
        if(isset($_GET['id'])){
            $id= I('get.id','','int');
            if(!empty($id)){
                $type=I('get.type');
                if(!empty($type)){
                    $map['id']=array('eq',$id);
                    $name=  $this->getActionName();
                    switch ($type) {
                        case 'xh':
                            if(Cookie::is_set('xh'.$id)){
                                $this->error('顶过了');
                            }
                            $result=D($name)->where($map)->setInc('xh',1);//更新喜欢人数
                            if($result){
                                $ip=get_client_ip();
                                Cookie::set('xh'.$id,$ip,24*3600);
                                $num=D($name)->where($map)->getField('xh');//喜欢人数
                                $this->success($num);
                            } 
                            break;
                        case 'nxh':
                            if(Cookie::is_set('nxh'.$id)){
                                $this->error('踩过了');
                            }
                            $result=D($name)->where($map)->setInc('nxh',1);//更新喜欢人数
                            if($result){
                                $ip=get_client_ip();
                                Cookie::set('nxh'.$id,$ip,24*3600);
                                $num=D($name)->where($map)->getField('nxh');//喜欢人数
                                $this->success($num);
                            } 
                            break;

                    }
                    
                }
                
            }
        }
    }

    //获取排序
    public function get_order($order){
        $data = array();
        switch ($order) {
            case '33':
                $data['order'] .= ' w.create_time asc';
                $data['o'] = 33;
                break;
            case '2':
                $data['order'] .= ' w.hits desc';
                $data['o'] = 2;
                break;
            case '22':
                $data['order'] .= ' w.hits asc';
                $data['o'] = 22;
                break;
            case '1':
                $data['order'] .= ' w.xh desc';
                $data['o'] = 1;
                break;
            case '11':
                $data['order'] .= ' w.xh asc';
                $data['o'] = 11;
                break;
            default:
                $data['order'] .= ' w.create_time desc';
                $data['o'] = 3;
                break;
        }

        return $data;
    }
    

    //根据ID获得区域及其子区域信息
    function getArea($id){
        if(!isset( $id )){
            return false;
        }
        $Areas = D ( "Areas" );
        $where = " (id = $id OR parent_id = $id) AND area_type < 3 ";
        $list = $Areas->where($where)->select();

        $data = array();
        foreach ($list as $key => $value) {
            if($value['id'] == $id)
            {
                $data = $value;
            }
            else
            {
                $data['child'][$value['id']] = $value;  
            }

        }

        return $data;
    }

    //根据时间戳算出距今时间
    function pastTime($timestamp){
        if(!isset($timestamp) || $timestamp <= 0){
            return '时间出错';
        }

        $now = time();
        if($now < timestamp)
            return '时间出错';
        $minus = $now - $timestamp;

        switch ($minus) {
            case $minus < 60:
                return $minus.'秒';
                break;
            case $minus < 3600:
                return ceil($minus/60).'分钟';
                break;
            case $minus < 86400:
                return ceil($minus/3600).'小时';
                break;
            default:
                return ceil($minus/86400).'天';
                break;
        }
    }
   
}

?>
