<?php

namespace wechat\models\service;

use common\middleware\Bonus;
use wechat\models\VipBonus;
use wechat\models\WechatVip;

class WechatVipService
{
    public static function findByWechatUser($id)
    {
        return WechatVip::find()->where(['wechat_user_id' => $id])->one();
    }

    public static function vipGenerator($data)
    {

    }

    public static function updateProfile($vip_code, $datas)
    {
        $vip = WechatVip::findOne(['vip_no' => $vip_code]);
        print_r($vip);

    }

    /**
     * 获取到用户基础信息
     * @param string $vip_code 会员卡号
     * @param int $limit 刷新时间
     * @return array|null|VipBonus|\yii\db\ActiveRecord
     */
    public static function getBonus($vip_code, $limit = 60)
    {
        $bonus = VipBonus::find()->where(['vip_code' => $vip_code])->one();
        if (!$bonus) {
            $point = Bonus::query($vip_code);
            $bonus = new VipBonus();
            $bonus->vip_code = $vip_code;
            $bonus->bonus = $point['bp'];
            $bonus->exp_bonus = $point['exp_in_30day'];
            $bonus->update_time = time();
            $bonus->save();
            return $bonus;
        } else {
            if (time() - $bonus->update_time < $limit) {
                return $bonus;
            } else {
                //$point = Bonus::query($vip_code);
                $bonus->bonus =100; //$point['bp'];
                $bonus->exp_bonus = 0;//$point['exp_in_30day'];
                $bonus->update_time = time();
                $bonus->save();
                return $bonus;

            }
        }

    }
}
