<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/1
 * Time: 下午5:10
 */

namespace wechat\models\logic;


use common\models\WechatVipsActiveLogs;

class VipPrizeCheck
{

    public static function check($vip_code)
    {
        $bind = WechatVipsActiveLogs::find()->where(['vip_code' => $vip_code, 'types' => 1])->one();
        $profile = WechatVipsActiveLogs::find()->where(['vip_code' => $vip_code, 'types' => 2])->one();
        $ret = ['bind' => $bind ? 1 : 0, 'profile' => $profile ? 1 : 0];
        return $ret;

    }

}