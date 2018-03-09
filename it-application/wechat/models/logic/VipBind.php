<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/10
 * Time: 下午5:14
 */

namespace wechat\models\logic;


use wechat\models\WechatVip;

class VipBind
{
    static function updateBind($vip_code, $brand_id, $store_id = false)
    {
        /** @var WechatVip $vip */
        $vip = WechatVip::find()->where(['vip_no' => $vip_code, 'brand' => $brand_id])->one();
        if ($store_id !== false) {
            $vip->store_id = $store_id;
        }
        $vip->bind_time = date('Y-m-d H:i:s');
        $vip->save();
    }

}