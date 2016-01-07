<?php

class ClearcacheAction extends CommonAction {
    public function index() {
        //清除缓存
        clearCache();
        $this->home=HOME_PATH;
        $this->admin=APP_PATH;
        $this->m="../".M_PATH;
        $this->display();
    }
    
}

?>
