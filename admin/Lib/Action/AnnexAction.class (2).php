<?php

class AnnexAction extends CommonAction {
    public function index() {
       if(IS_POST){
           $Annex=new AnnexModel();
           $Annex->upAnnex('article',$_POST['old_Annex'],$_POST['new_Annex']);
           $Annex->upAnnex('announce',$_POST['old_Annex'],$_POST['new_Annex']);
           $Annex->upAnnex('download',$_POST['old_Annex'],$_POST['new_Annex']);
           $Annex->upAnnex('photo',$_POST['old_Annex'],$_POST['new_Annex']);
           $Annex->upAnnex('product',$_POST['old_Annex'],$_POST['new_Annex']);
           $Annex->upAnnex('category',$_POST['old_Annex'],$_POST['new_Annex']);
       
           $this->success('提交成功!');

       }else{
           $this->display();  
       }
       
    }
    
}

?>
