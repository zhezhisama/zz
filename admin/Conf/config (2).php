<?php
    return array(
        'SESSION_AUTO_START'=>true,
        'USER_AUTH_ON'      =>true,
        'USER_AUTH_TYPE'    =>1,	// 默认认证类型 1 登录认证 2 实时认证
        'USER_AUTH_KEY'     =>'authId',	// 用户认证SESSION标记
        'ADMIN_AUTH_KEY'    =>'administrator',
        'USER_AUTH_MODEL'   =>'User',	// 默认验证数据表模型
        'AUTH_PWD_ENCODER'  =>'md5',	// 用户认证密码加密方式
        'USER_AUTH_GATEWAY' =>'Public/login',// 默认认证网关
        'NOT_AUTH_MODULE'   =>'Public',	// 默认无需认证模块
        'GUEST_AUTH_ON'     =>false,    // 是否开启游客授权访问
        'GUEST_AUTH_ID'     =>0,        // 游客的用户ID
        'DB_LIKE_FIELDS'    =>'title|remark',
        'SHOW_PAGE_TRACE'   =>0,            //显示调试信息
        'APP_AUTOLOAD_PATH'=>'@.TagLib',
        'TMPL_ACTION_ERROR' => 'Public:error',      //默认错误跳转对应的模板文件  
        'TMPL_ACTION_SUCCESS' => 'Public:success',  //默认成功跳转对应的模板文件
//        'COOKIE_PREFIX'=>'YFAdmin_',
//        'SESSION_PREFIX'=>'YFAdmin_',
        'TMPL_PARSE_STRING'=>array(
            '__PUBLIC__'=>__ROOT__.'/'.APP_NAME.'/Tpl/Public',
            '__UPLOADS__'=>__ROOT__.'/Uploads',
        ),
        'VAR_PAGE'=>'p',
        'LOAD_EXT_CONFIG' => 'user,other',
    );
?>