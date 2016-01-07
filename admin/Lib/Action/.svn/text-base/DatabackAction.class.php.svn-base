<?php

class DatabackAction extends CommonAction {
    
    public $config = '';                                                        //相关配置
    public $model = '';                                                         //实例化一个model
    public $content;                                                            //内容
    public $dbName = '';                                                        //数据库名
    public $dir_sep = '/';                                                      //路径符号
 
    //初始化数据
 
    function _initialize() {
        parent::_initialize();
        header("Content-type: text/html;charset=utf-8");
        set_time_limit(0);                                                      //不超时
        ini_set('memory_limit','500M');
        $this->config = array(
            'path' => C('DB_BACKUP'),                                           //备份文件存在哪里
            'isCompress' => 0,                                                  //是否开启gzip压缩      
            'isDownload' => 0                                                   //备份完成后是否下载文件
        );
        $this->dbName = C('DB_NAME');                                           //当前数据库名称
        $this->model = new Model();
        //$sql = 'set interactive_timeout=24*3600';                             //空闲多少秒后 断开链接
        //$this->model>execute($sql);
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 已备份数据列表
     * +------------------------------------------------------------------------
     */
 
    function index() {

        $list = $this->model->query("SHOW TABLE STATUS FROM {$this->dbName}");  //得到表的信息
        //echo $Backup->getLastSql();
        
        $this->assign('list', $list);
        $this->display();
    }
 
    function back(){
        $type=$_POST['filetype'];
        
        if($type==0){
            $this->backall();
        }elseif ($type==1) {
            $this->backtables();
        }
    }
    /* -
     * +------------------------------------------------------------------------
     * * @ 获取数据表
     * +------------------------------------------------------------------------
     */
 
    function tablist() {
        $list = $this->model->query("SHOW TABLE STATUS FROM {$this->dbName}");  //得到表的信息
        //echo $Backup->getLastSql();
        
        $this->assign('list', $list);
        $this->display();
    }
    /* -
     * +------------------------------------------------------------------------
     * * @ 按表备份，可批量
     * +------------------------------------------------------------------------
     */
 
    function backtables() {
        $tab = $_REQUEST['tab'];
        if (is_array($tab))
            $tables = $tab;
        else
            $tables[] = $tab;
        if ($this->backup($tables)) {
            if (is_array($tab))
                $this->success('数据库备份成功！');
            else
                $this->success('数据库备份成功！');
        } else {
            $this->error('数据库备份失败！');
        }
    }
    /* -
     * +------------------------------------------------------------------------
     * * @ 备份整个数据库
     * +------------------------------------------------------------------------
     */
 
    function backall() {
        $tables = $this->getTables();
        if ($this->backup($tables)) {
            $this->success('数据库备份成功！');
        } else {
            $this->error('数据库备份失败！');
        }
    }
 
 
    /********************************************************************************************** */
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 读取备份文件
     * +------------------------------------------------------------------------
     * * @ $fileName 文件名
     * +------------------------------------------------------------------------
     */
 
    private function getFile($fileName) {
        $this->content = '';
        $fileName = $this->trimPath($this->config['path'] . $this->dir_sep . $fileName);
        if (is_file($fileName)) {
            $ext = strrchr($fileName, '.');
            if ($ext == '.sql') {
                $this->content = file_get_contents($fileName);
            } elseif ($ext == '.gz') {
                $this->content = implode('', gzfile($fileName));
            } else {
                $this->error('无法识别的文件格式!');
            }
        } else {
            $this->error('文件不存在!');
        }
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 把数据写入磁盘
     * +------------------------------------------------------------------------
     */
 
    private function setFile() {
        $recognize = '';
        $recognize = $this->dbName;

        $strrand=randstrletters(30);
//      $strrand=mt_rand(100000000, 999999999)
        $fileName = $this->trimPath($this->config['path'] . $this->dir_sep . $recognize . '_' . date('Ymd') . '_' . $strrand . '.sql');
     
        $path = $this->setPath($fileName);
        if ($path !== true) {
            $this->error("无法创建备份目录目录 '$path'");
        }
        if ($this->config['isCompress'] == 0) {
            if (!file_put_contents($fileName, $this->content, LOCK_EX)) {
                $this->error('写入文件失败,请检查磁盘空间或者权限!');
            }
        } else {
            if (function_exists('gzwrite')) {
                $fileName .= '.gz';
                if ($gz = gzopen($fileName, 'wb')) {
                    gzwrite($gz, $this->content);
                    gzclose($gz);
                } else {
                    $this->error('写入文件失败,请检查磁盘空间或者权限!');
                }
            } else {
                $this->error('没有开启gzip扩展!');
            }
        }
        if ($this->config['isDownload']) {
            $this->downloadFile($fileName);
        }
    }
 
    private function trimPath($path) {
        return str_replace(array('/', '\\', '//', '\\\\'), $this->dir_sep, $path);
    }
 
    private function setPath($fileName) {
        $dirs = explode($this->dir_sep, dirname($fileName));
        $tmp = '';
        foreach ($dirs as $dir) {
            $tmp .= $dir . $this->dir_sep;
            if (!file_exists($tmp) && !@mkdir($tmp, 0777))
                return $tmp;
        }
        return true;
    }
 

    private function downloadFile($fileName) {
        ob_end_clean();
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($fileName));
        header('Content-Disposition: attachment; filename=' . basename($fileName));
        readfile($fileName);
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 给字符串添加 ` `
     * +------------------------------------------------------------------------
     * * @ $str 字符串
     * +------------------------------------------------------------------------
     * * @ 返回 `$str`
     * +------------------------------------------------------------------------
     */
 
    private function backquote($str) {
        return "`{$str}`";
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 获取数据库的所有表
     * +------------------------------------------------------------------------
     * * @ $dbName  数据库名称
     * +------------------------------------------------------------------------
     */
 
    private function getTables($dbName = '') {
        if (!empty($dbName)) {
            $sql = 'SHOW TABLES FROM ' . $dbName;
        } else {
            $sql = 'SHOW TABLES ';
        }
        $result = $this->model->query($sql);
        $info = array();
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 把传过来的数据 按指定长度分割成数组
     * +------------------------------------------------------------------------
     * * @ $array 要分割的数据
     * * @ $byte  要分割的长度
     * +------------------------------------------------------------------------
     * * @ 把数组按指定长度分割,并返回分割后的数组
     * +------------------------------------------------------------------------
     */
 
    private function chunkArrayByByte($array, $byte = 5120) {
        $i = 0;
        $sum = 0;
        $return = array();
        foreach ($array as $v) {
            $sum += strlen($v);
            if ($sum < $byte) {
                $return[$i][] = $v;
            } elseif ($sum == $byte) {
                $return[++$i][] = $v;
                $sum = 0;
            } else {
                $return[++$i][] = $v;
                $i++;
                $sum = 0;
            }
        }
        return $return;
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 备份数据 { 备份每张表、视图及数据 }
     * +------------------------------------------------------------------------
     * * @ $tables 需要备份的表数组
     * +------------------------------------------------------------------------
     */
 
    private function backup($tables) {
        
        if (empty($tables))
            $this->error('没有需要备份的数据表!');
        $this->content = '/* This file is created by MySQLReback ' . date('Y-m-d H:i:s') . ' */';
        foreach ($tables as $i => $table) {
            $table = $this->backquote($table);                                  //为表名增加 ``
            $tableRs = $this->model->query("SHOW CREATE TABLE {$table}");       //获取当前表的创建语句
            if (!empty($tableRs[0]["Create View"])) {
                $this->content .= "\r\n /* 创建视图结构 {$table}  */";
                $this->content .= "\r\n DROP VIEW IF EXISTS {$table};/* MySQLReback Separation */ " . $tableRs[0]["Create View"] . ";/* MySQLReback Separation */";
            }
            if (!empty($tableRs[0]["Create Table"])) {
                $this->content .= "\r\n /* 创建表结构 {$table}  */";
                $this->content .= "\r\n DROP TABLE IF EXISTS {$table};/* MySQLReback Separation */ " . $tableRs[0]["Create Table"] . ";/* MySQLReback Separation */";
                $tableDateRow = $this->model->query("SELECT * FROM {$table}");
                $valuesArr = array();
                $values = '';
                if (false != $tableDateRow) {
                    foreach ($tableDateRow as &$y) {
                        foreach ($y as &$v) {
                           if ($v=='')                                  //纠正empty 为0的时候  返回tree
                                $v = 'null';                                    //为空设为null
                            else
                                $v = "'" . mysql_escape_string($v) . "'";       //非空 加转意符
                        }
                        $valuesArr[] = '(' . implode(',', $y) . ')';
                    }
                }
                $temp = $this->chunkArrayByByte($valuesArr);
                if (is_array($temp)) {
                    foreach ($temp as $v) {
                        $values = implode(',', $v) . ';/* MySQLReback Separation */';
                        if ($values != ';/* MySQLReback Separation */') {
                            $this->content .= "\r\n /* 插入数据 {$table} */";
                            $this->content .= "\r\n INSERT INTO {$table} VALUES {$values}";
                        }
                    }
                }
//                dump($this->content);
//                exit;
            }
        }

        if (!empty($this->content)) {
            $this->setFile();
        }
        return true;
    }
 
    /* -
     * +------------------------------------------------------------------------
     * * @ 还原数据
     * +------------------------------------------------------------------------
     * * @ $fileName 文件名
     * +------------------------------------------------------------------------
     */
 
    private function recover_file($fileName) {
        $this->getFile($fileName);
        if (!empty($this->content)) {
            $content = explode(';/* MySQLReback Separation */', $this->content);
            foreach ($content as $i => $sql) {
                $sql = trim($sql);
                if (!empty($sql)) {
                    $mes = $this->model->execute($sql);
                    if (false === $mes) {                                       //如果 null 写入失败，换成 ''
                        $table_change = array('null' => '\'\'');
                        $sql = strtr($sql, $table_change);
                        $mes = $this->model->execute($sql);
                    }
                    if (false === $mes) {                                       //如果遇到错误、记录错误
                        $log_text = '以下代码还原遇到问题:';
                        $log_text.="\r\n $sql";
                        set_log($log_text);
                    }
                }
            }
        } else {
            $this->error('无法读取备份文件!');
        }
        return true;
    }

    
    
    
    
    
    
}

?>
