<?php
/*
分销和交友整合程序
*/

class JiekouAction extends Action {
    public function is_buy() {
        $openid = trim($_GET['openid']);
        $code = trim($_GET['code']);
        $is_buy = array(); //声明回复的数组
        $buy_button ='http://'.$_SERVER['HTTP_HOST'].'/index.php?'.'g=App&m=Index&a=listsp&id=1';
        $is_buy['url'] = $buy_button;
        if ($code = 'fans') { //如果code=fans获取是否是购买过的
            $usersresult = R("Api/Api/getuser", array(
                $openid
            ));
            if ($usersresult['member'] == 1) {
                $is_buy['s'] = 1;
                $is_buy['r'] = '通过';
            } else if ($usersresult['member'] == 0) {
                $is_buy['s'] = 0;
                $is_buy['r'] = '您还不是vip会员无法发布二维码！购买vip会员即可发布二维码，享受主动被加的特权，结识更多的人脉朋友';
            }
        }
       

        echo json_encode($is_buy);
     
    }
}
