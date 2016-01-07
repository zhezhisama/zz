<?php

class WxapiAction extends CommonAction {
    public function index() {
        //加载用户配置文件信息
        require "../config.ini.php";
        $user_config = $array;
        
        //登录安全设置
        $set=D('Set')->find();
        $this->set=$set;
        
        $WX_URL=  str_replace(APP_NAME."/","",U('Wxapi/mapi','','','',true));
        $this->WX_URL=$WX_URL;
        if(empty($user_config['WX_TOKEN'])){
            $user_config['WX_TOKEN']=str_replace(".", "", uniqid('yf',TRUE));
        }
        $this->user_config=$user_config;

        $this->display();  
    }
    public function save() {
        require "../config.ini.php";
        $user_config = $array;
        $user_config["WX_TOKEN"] = $_POST["WX_TOKEN"];
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
    public function uniqid(){
        echo str_replace(".", "", uniqid('yf',TRUE));
    }
}

?>
