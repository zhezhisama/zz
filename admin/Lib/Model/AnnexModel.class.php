<?php
class AnnexModel extends CommonModel {
    function upAnnex($tablename,$old_Annex,$new_Annex){
        
            $result = $this->db->execute('UPDATE `'.$this->tablePrefix.$tablename.'` SET `content` = replace (`content`,\''.$old_Annex.'\',\''.$new_Annex.'\')');
            
            if($result==false) {
                    return false;
            }else {
                    return true;
            }
    }
}
?>