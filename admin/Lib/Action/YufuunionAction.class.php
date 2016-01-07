<?php

class YufuunionAction extends CommentAction{
    public function index() {
        //加载用户配置文件信息

        require "../config.ini.php";

        $this->user_config=$array;
        $this->display();  
    }
    public function save() {
        require "../config.ini.php";
        $user_config = $array;

        
        $user_config["ISAUTOVERIFY"] = intval($_POST["ISAUTOVERIFY"]);
        $user_config["ISTASK"] = intval($_POST["ISTASK"]);
        if(intval($_POST["ISAUTOVERIFY"])){
           if(empty($_POST["YUFUMARK"])){
                $this->error('开启自动审核，标识码必须填写');
            } 
        }
        if(intval($_POST["ISTASK"])){
           if(empty($_POST["YUFUMARK"])){
                $this->error('开启任务提交，标识码必须填写');
            } 
        }
        
        $user_config["YUFUMARK"] = $_POST["YUFUMARK"];
        
        if(intval($_POST["ISTASK"])){
            if(empty($_POST["TASKURL"])){
                $this->error('开启任务提交，必须填写任务接口地址');
            }
        }
        $user_config["TASKURL"] = $_POST["TASKURL"];
        
        $config = "<?php\r\nif(!defined('THINK_PATH')) exit();\r\nreturn \$array = ".var_export($user_config, TRUE).";\r\n?>";

        //保存配置
        file_put_contents("../config.ini.php",$config);
        
        //清空前台缓存
//        clearCache();

        
        $this->success("设置成功");
    }
}

?>
