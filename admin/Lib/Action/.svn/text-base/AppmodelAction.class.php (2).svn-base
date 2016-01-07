<?php

class AppmodelAction extends CommonAction {
    public function index() {
        //加载用户配置文件信息
        require '../'.M_PATH."Conf/user.php";
        
        $user_config = $array;
        //模板路径
        $templateDir = M_PATH."Tpl/";
        $templateList = array();
        if (is_dir('../'.$templateDir)) {
            
            if ($dh = opendir('../'.$templateDir)) {
                while (($template = readdir($dh)) !== false) {
                    if($template != "." && $template != ".." && filetype('../'.$templateDir.$template) == "dir")
                    $templateList[$template] = $template;
                }
                closedir($dh);
            }
        }
            
        $this->httphost=$_SERVER['HTTP_HOST'];
        $this->templateDir=$templateDir;
        $this->user_config=$user_config;
        $this->templateList=$templateList;
        
        $this->display();  
    }
    public function save() {
        require '../'.M_PATH."Conf/user.php";
        $user_config = $array;
        
        $user_config["DEFAULT_THEME"] = $_POST["DEFAULT_THEME"];
        
        $config = "<?php\r\n\$config=  require '../config.ini.php';\r\n\$array= ".var_export($user_config, TRUE).";\r\n\r\nreturn array_merge(\$config,\$array);\r\n?>";

        //保存配置
        file_put_contents('../'.M_PATH."Conf/user.php",$config);
        
        //清空前台缓存(使用了扩展配置不用在清缓存了)
        //clearCache();
        
        
        //生成手机浏览地址二维码
        createQrCode("http://".$_SERVER['HTTP_HOST'].__ROOT__.'/m');
        
        $this->success("模板设置成功");
    }
}

?>
