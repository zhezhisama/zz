<?php
class CommonModel extends Model {
    //获取父类id
    public function getPosition($id){
        $type = D('Category')->where('status=1')->find($id);
        if($type['pid']==0){
                $position[] = array('id'=>$id,'catname'=>$type['catname']);
        }else{
                $position =$this->getPosition($type['pid']);
                $position[]=array('id'=>$id,'catname'=>$type['catname']);
        }
        return $position;
    }
    //获取地区父类id
    public function getAreaPosition($id){
        $type = D('Areas')->find($id);
        if($type['parent_id']==0){
                $position[] = array('id'=>$id,'areaname'=>$type['area_name'],'level'=>$type['area_type']);
        }else{
                $position =$this->getAreaPosition($type['parent_id']);
                $position[]=array('id'=>$id,'areaname'=>$type['area_name'],'level'=>$type['area_type']);
        }
        return $position;
    }
    //获取分类列表
    public function getCategoryMap($id){
        $type = D('Category')->where('status=1')->find($id);
        if($type['pid']==0){
                $types = D('Category')->where('status=1 AND pid='.$type['id'])->select();
                if(is_array($types)){
                    foreach($types as $val) $ary[]=$val['id'];
                }
                $map['catid'] = array('in',$ary);
        }else{
                $map['catid'] = array('eq',$id);
        }
        return $map;		
    }
}