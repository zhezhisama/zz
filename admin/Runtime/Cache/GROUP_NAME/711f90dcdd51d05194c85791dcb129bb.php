<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>微信群管理平台登录</title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><link rel='stylesheet' type="text/css" href="../Public/css/style.css" /><script type="text/javascript" src="../Public/js/jquery-1.7.2.min.js"></script><script type="text/javascript" src="../Public/js/jquery.form.js"></script><style type="text/css">            body{background: #E9E9E9;}
        </style><script type="text/javascript"><!--
    $(function(){

        $('#form1').ajaxForm({
            beforeSubmit:  checkForm,  
            success:       complete,  
            dataType: 'json'
        });
        
        function complete(data){
            if (data.status==1){
                 window.location.href = "<?php echo U('Index/index');?>";
            }else{
                alert(data.info);
                var timenow = new Date().getTime();
                var url="<?php echo U('Public/verify',array('t'=>'"+timenow+"'));?>";
                $('#verifyImg').attr('src',url);
            }
        }
        function checkForm(){
            if( '' === $.trim($('#account').val())){
                //$('#result').html('用户名不能为空!').show();
                alert('用户名不能为空!');
                return false;
            }
            if( '' === $.trim($('#password').val())){
                //$('#result').html('密码不能为空!').show();
                alert('密码不能为空!');
                return false;
            }
            if( '' === $.trim($('#verify').val())){
                //$('#result').html('验证码不能为空!').show();
                alert('验证码不能为空!');
                return false;
            } 
        }
       
        $('#verifyImg').click(function(){
            //重载验证码
            $('#verifyImg').attr('src',"<?php echo U('Public/verify',array('t'=>time()));?>");
            
        });
        
    });
 
    //--></script></head><body><div class="login"><div class="version">微信群管理平台</div><form method='post' name="login" id="form1" action="<?php echo U('Public/checkLogin');?>" ><div class="loginbox"><label>用户名：</label><input type="text" class="ipt3" id="account" name="account"><label>密 码：</label><input type="password" class="ipt3" id="password" name="password"><label>验证码：</label><input type="text" class="ipt2" id="verify" name="verify"><img id="verifyImg" src="<?php echo U('Public/verify');?>"  border="0" alt="点击刷新验证码" style="cursor:pointer; margin-left: 5px;" align="absmiddle"><input type="hidden" name="ajax" value="1"><input type="submit" id="submit" value="登 录" class="btn4"></div></form><div id="result" class="red"></div><div class="shuom">在操作过程中如果有问题，可以联系客服寻求帮助</div></div></body></html>