<?php

class SetAction extends CommonAction {
    public function index() {
        //加载用户配置文件信息

        require "../config.ini.php";
        $user_config = $array;
        
        //模板路径
        $templateDir = HOME_PATH."Tpl/";
        $templateList = array();
        if (is_dir($templateDir)) {
            if ($dh = opendir($templateDir)) {
                while (($template = readdir($dh)) !== false) {
                    if($template != "." && $template != ".." && filetype($templateDir.$template) == "dir")
                    $templateList[$template] = $template;
                }
                closedir($dh);
            }
        }
        //登录安全设置
        $set=D('Set')->find();
        $this->set=$set;
        $this->user_config=$user_config;

        $this->templateList=$templateList;
        $this->display();  
    }
    public function save() {
        require "../config.ini.php";
        $user_config = $array;

        $user_config["SITE_NAME"] = $_POST["SITE_NAME"];
        $user_config["SITE_TITLE"] = $_POST["SITE_TITLE"];
        $user_config["SITE_KEYWORDS"] = $_POST["SITE_KEYWORDS"];
        $user_config["SITE_DESCRIPTION"] = $_POST["SITE_DESCRIPTION"];
        $user_config["DEFAULT_THEME"] = $_POST["DEFAULT_THEME"];
        $user_config["ISCOMMENT"] = intval($_POST["ISCOMMENT"]);
        $user_config["COMMENTTYPE"] = intval($_POST["COMMENTTYPE"]);
        $user_config["ISADDACCOUNT"] = intval($_POST["ISADDACCOUNT"]);
        $user_config["XFINTERGRAL"] = intval($_POST["XFINTERGRAL"]);
        
        
        $fgid=$_POST["RMB_CHANGE_INTERGRAL"];
        if(preg_match("/^\d*$/",$fgid)){
            $user_config["RMB_CHANGE_INTERGRAL"] = intval($fgid);
        }else{
            $this->error('1元人民币购买积分数量必须为数字');
        }
        
        $config = "<?php\r\nif(!defined('THINK_PATH')) exit();\r\nreturn \$array = ".var_export($user_config, TRUE).";\r\n?>";
        
        //保存配置
        file_put_contents("../config.ini.php",$config);
        
        //清空前台缓存
//        clearCache();
        
        //站点设置
        $this->_upload();
        $model = D('Set');
        if (false === $model->create()) {
            $this->error($model->getError());
        }
        // 更新数据
        $list = $model->save();
        
        $this->success("设置成功");
    }
}

?>
