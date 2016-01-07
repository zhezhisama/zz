<?php
    return array(
        'APP_AUTOLOAD_PATH'=>'@.TagLib',
        'TAGLIB_PRE_LOAD'=>'yufu',
        'TMPL_ACTION_ERROR'=>'Public:error',
        'TMPL_ACTION_SUCCESS'=>'Public:success',
        'TOKEN_ON'=>true,  // 是否开启令牌验证 默认关闭
        'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
        'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
        'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
        'COOKIE_PREFIX'=>'YFIndex_',
        'SESSION_PREFIX'=>'YFIndex_',
        'LOAD_EXT_CONFIG' => 'user,other',
        
    );
?>