<?php
//import('TagLib');
class TagLibYufu extends TagLib {
   //标签定义
   protected $tags=array(
       'category'=>array('attr'=>'catid,result','level'=>1),
       'content'=>array('attr'=>'catid,result','level'=>1),
       'article'=>array('attr'=>'catid,field,order,num,result','level'=>1),
       'promotion'=>array('attr'=>'catid,field,order,num,result','level'=>1),
       'product'=>array('attr'=>'catid,field,order,num,result','level'=>1),
       'download'=>array('attr'=>'catid,field,order,num,result','level'=>1),
       'photo'=>array('attr'=>'catid,field,order,num,result','level'=>1),
       'weixin'=>array('attr'=>'catid,field,order,num,tuijian,curtuijian,status,result','level'=>1),
	   'weixin1'=>array('attr'=>'catid,field,order,num,tuijian,curtuijian,status,result','level'=>1),
       'tuijian'=>array('attr'=>'catid,field,order,num,status,result','level'=>1),
       'search'=>array('attr'=>'field,order,num,result','level'=>1),
       'other'=>array('attr'=>'settag,result','close'=>0),
       'announce'=>array('attr'=>'catid,field,order,num,result','level'=>1),
       'set'=>array('attr'=>'settag,result','close'=>0),
       'advert'=>array('attr'=>'settag,result','close'=>0),
       'slide'=>array('attr'=>'field,order,num,result','level'=>1),
       'hits'=>array('attr'=>'catid,field,order,num,status,result','level'=>1),
   );
    //定义查询数据库标签
   public function _category($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'category');
        $result= !empty($tag['result'])?$tag['result']: 'category';
        
        if(isset($tag['catid'])){
            $catid=$tag['catid'];
            $catlist = D('Category')->where('status=1')->select();	
            $idlist = $catid.','.getChildId($catlist,$catid);  
            $idlist= substr($idlist, 0, strlen($idlist)-1);
        }
        
        $map="'status=1";
        $map.=($catid)?" and id in ($idlist)'":"'";
        $sql ='M(\'Category\')->where('.$map.')->order(\'level,listorder\')->select()';

        //下面拼接输出语句
        $parsestr = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr; 
       

   }
   //定义查询数据库标签
   public function _content($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'content');
       
        $result= !empty($tag['result'])?$tag['result']: 'content';
        
        $map.="'status=1";
        $map.=($tag['catid'])?" and id={$tag['catid']}'":"'";
        
        $sql ="M('Category')->";
        $sql.="where({$map})->";
        $sql.="find()";

        //下面拼接输出语句
        $parsestr  = '<?php $'.$result.'=$_result='.$sql.';?>';
        $parsestr .= $content;//解析在article标签中的内容
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _article($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'article');
       
        $result= !empty($tag['result'])?$tag['result']: 'article';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        
        
        $map.="'status=1";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";
        
        $sql ="M('Article')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _promotion($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'promotion');
       
        $result= !empty($tag['result'])?$tag['result']: 'promotion';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        
        
        $map.="'status=1";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";
        
        $sql ="M('Promotion')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   
   //定义查询数据库标签
   public function _product($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'product');
     
        $result= !empty($tag['result'])?$tag['result']: 'product';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        
        
        $map.="'status=1";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";
        
        $sql ="M('Product')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _download($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'download');
       
        $result= !empty($tag['result'])?$tag['result']: 'download';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        
        
        $map.="'status=1";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";
        
        $sql ="M('Dowonload')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _photo($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'photo');
       
        $result= !empty($tag['result'])?$tag['result']: 'photo';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
        
        
        $map.="'status=1";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";
        
        $sql ="M('Photo')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   
   //公众帐户标签
   public function _weixin($attr,$content) {

		
		$tag=$this->parseXmlAttr($attr, 'weixin');
       
        $result= !empty($tag['result'])?$tag['result']: 'weixin';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);

          
        if(strtolower($tag['status'])=='all'){
            $map.="'status>=-1";

        }else{
            $map.="'status=1";
        }
        $map.=($tag['tuijian'])?" and tuijian=".$tag['tuijian']."":"";
        $map.=($tag['curtuijian'])?" and curtuijian=".$tag['curtuijian']."":"";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";

        $sql ="M('Weixin')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;
   


   }
   
   
    public function _weixin1($attr,$content) {
	
			
		
        $tag=$this->parseXmlAttr($attr, 'weixin');
       
        $result= !empty($tag['result'])?$tag['result']: 'weixin';
        
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);
          
        if(strtolower($tag['status'])=='all'){
            $map.="'status>=-1";

        }else{
            $map.="'status=1";
        }
		$map.=" and publish_type_id = 4 ";
        $map.=($tag['tuijian'])?" and tuijian=".$tag['tuijian']."":"";
        $map.=($tag['curtuijian'])?" and curtuijian=".$tag['curtuijian']."":"";
        $map.=($tag['catid'])?" and catid in ($idlist)'":"'";
		
		
		
//return var_dump($map);
        $sql ="M('Weixin')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";
		
		
		
        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;
   


   }
   //推荐公众帐户标签
   public function _tuijian($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'tuijian');
       
        $result= !empty($tag['result'])?$tag['result']: 'tuijian';
        
        $prefix=C('DB_PREFIX');
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);

        $map.="'".$prefix."weixin.status=1";

        $time=time();
        $time1=$time-(24*3600);
        $map.=" and ((starttime<=".$time."";
        $map.=" and endtime>=".$time1.")";
        $map.=" or timelimit=0)";
        $map.=" and ".$prefix."tuijian.status=1";
        $map.=" and recommendid=".$tag['recommendid']."";
        $map.=($tag['catid'])?" and ".$prefix."weixin.catid in ($idlist)'":"'";

        $sql ="M('tuijian')->";
        $sql.="join('".$prefix."weixin ON ".$prefix."weixin.id=".$prefix."tuijian.wxid')->";
        $sql.="where({$map})->";
        $sql.="field('".$prefix."weixin.*')->";
        $sql.="order('timelimit desc,".$prefix."tuijian.create_time desc')->";

        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   //浏览标签
   public function _hits($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'hits');
       
        $result= !empty($tag['result'])?$tag['result']: 'hits';
        
        $prefix=C('DB_PREFIX');
        $catlist = D('Category')->where('status=1')->select();	
        $idlist = $tag['catid'].','.getChildId($catlist,$tag['catid']);  
        $idlist= substr($idlist, 0, strlen($idlist)-1);

        $map.="'".$prefix."weixin.status=1";
        if(strtolower($tag['order'])=="d"){
            $map.=" and date(FROM_UNIXTIME(hittime)) = curdate()";
        }elseif(strtolower($tag['order'])=="w"){
            $map.=" and DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(FROM_UNIXTIME(hittime))";
        }  elseif (strtolower($tag['order'])=="m") {
            $map.=" and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(FROM_UNIXTIME(hittime))";
        }
        
       
        $map.=($tag['catid'])?" and ".$prefix."weixin.catid in ($idlist)'":"'";

        $sql ="M('hits')->";
        $sql.="join('".$prefix."weixin ON ".$prefix."weixin.id=".$prefix."hits.accountid')->";
        $sql.="where({$map})->";
        $sql.="field('".$prefix."weixin.*')->group('accountid')->";
        $sql.="order('hitnum desc')->";

        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _search($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'search');
       
        $result= !empty($tag['result'])?$tag['result']: 'search';

        $map.="'status=1'";

        $sql ="M('Search')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';

        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
  //广告标签
   public function _advert($attr) {

        $tag=$this->parseXmlAttr($attr, 'advert');
       
        $result= !empty($tag['result'])?$tag['result']: 'advert';

        $map.=($tag['settag'])?"adtag=\"{$tag['settag']}\"":"'";
        
        $sql ="M('Advert')->";
        $sql.="where('{$map}')->";
        $sql.="find()";

        $time=time();
        
        //下面拼接输出语句
        $parsestr  = '<?php $'.$result.'=$_result='.$sql.'; ';
        $parsestr  .= 'if($'.$result.'[\'timelimit\']==0):';
            $parsestr  .= 'echo $'.$result.'[\'adcontent\'];';
         $parsestr  .= 'else:';
            $parsestr  .= 'if($'.$result.'[\'endtime\']>='.$time.'):';
            $parsestr  .= 'echo $'.$result.'[\'adcontent\'];';
            $parsestr  .= 'else:';
            $parsestr  .= 'echo $'.$result.'[\'adpastcontent\'];';
            $parsestr  .= 'endif;';
        $parsestr  .= 'endif;';

        $parsestr  .= '?>';
        //$parsestr .= $content;//解析在article标签中的内容
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _other($attr) {

        $tag=$this->parseXmlAttr($attr, 'other');
       
        $result= !empty($tag['result'])?$tag['result']: 'other';
        $map.="status=1";
        $map.=($tag['settag'])?" and settag=\"{$tag['settag']}\"":"'";
        
        $sql ="M('Other')->";
        $sql.="where('{$map}')->";
        $sql.="find()";

        //下面拼接输出语句
        $parsestr  = '<?php $'.$result.'=$_result='.$sql.'; echo $'.$result.'[\'setvalue\'];?>';
        //$parsestr .= $content;//解析在article标签中的内容
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _set($attr) {

        $tag=$this->parseXmlAttr($attr, 'set');
       
        $result= !empty($tag['result'])?$tag['result']: 'set';

//        $map.=($tag['settag'])?"settag=\"{$tag['settag']}\"":"'";
        $Field=($tag['settag'])?"{$tag['settag']}":"id";
        
        $sql ="M('Set')->";
        $sql.="getField('{$Field}',1)";
        //下面拼接输出语句
        $parsestr  = '<?php $'.$result.'=$_result='.$sql.'; echo $'.$result.';?>';
        //$parsestr .= $content;//解析在article标签中的内容
        return  $parsestr;

   }
    //定义查询数据库标签
   public function _announce($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'announce');
        
        $result= !empty($tag['result'])?$tag['result']: 'announce';
        
        $date=date('Y-m-d',time());
        $map.="status=1";
        $map.=(" and endtime >= \"$date\"");
        $sql ="M('Announce')->";
        $sql.="where('{$map}')->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   //定义查询数据库标签
   public function _slide($attr,$content) {
        $tag=$this->parseXmlAttr($attr, 'slide');
       
        $result= !empty($tag['result'])?$tag['result']: 'slide';

        $map.="'status=1'";

        $sql ="M('Slide')->";
        $sql.="where({$map})->";
        $sql.=($tag['field'])?"field('{$tag['field']}')->":"";
        $sql.=($tag['order'])?"order('{$tag['order']}')->":"";
        $sql.=($tag['num'])?"limit({$tag['num']})->":"";
        $sql.="select()";

        //下面拼接输出语句
        $parsestr  = '<?php $_result='.$sql.';';
        $parsestr .= 'foreach($_result as $key=>$'.$result.'):?>';
        $parsestr .= $content;//解析在article标签中的内容
        $parsestr .= '<?php endforeach;?>';
        return  $parsestr;

   }
   
}

?>
