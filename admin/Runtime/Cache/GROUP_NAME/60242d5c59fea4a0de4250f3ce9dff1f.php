<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>微信群，微信群大全</title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><link rel='stylesheet' type="text/css" href="../Public/css/style.css" /><base target="main" /></head><body><div class="top"><div class="logo"><a href="/">微信群管理平台</a></div><div class="info">                欢迎您！[<?php echo (session('loginUserName')); ?>]
                <a href="<?php echo U('Public/password');?>" class="textline">修改密码</a><a href="<?php echo U('Public/profile');?>" class="textline">修改资料</a><a href="<?php echo U('Public/logout');?>" class="textline" target="_top">退 出</a> |
                <a href="__ROOT__/" target="_blank" class="textline">网站首页</a></div></div><div class="nav"><ul><li class="bgn"><span><em><a href="#" onClick="sethighlight(0); parent.menu.location='<?php echo U('Public/menu');?>'; parent.main.location='<?php echo U('Public/main');?>'; return false;">起始页</a></em></span></li><?php if(is_array($nodeGroupList)): $i = 0; $__LIST__ = $nodeGroupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><li><span><em><a href="#" onClick="sethighlight(<?php echo ($i); ?>); parent.menu.location='<?php echo U('Public/menu',array('tag'=>$key));?>'; return false;"><?php echo ($tag); ?></a></em></span></li><?php endforeach; endif; else: echo "" ;endif; ?><li><span><em><a href="#" onClick="sethighlight(4); parent.menu.location='<?php echo U('Public/menu',array('tag'=>0));?>'; parent.main.location='<?php echo U('other/index');?>'; return false;">扩展功能</a></em></span></li></ul></div><script type="text/javascript">    function sethighlight(n) {
        var lis = document.getElementsByTagName('span');
        for(var i = 0; i < lis.length; i++) {
            lis[i].className = '';
        }
        lis[n].className = 'cur';
    }
    sethighlight(0);
    
</script></body></html>