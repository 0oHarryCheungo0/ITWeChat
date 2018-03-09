<?php

namespace wechat\models\service;

use wechat\models\WechatUser;

class WechatUserService
{
    public static function findByOpenid($openid)
    {
        return WechatUser::find()->where(['openid' => $openid])->one();
    }

    public static function createWechatUser($user, $brand_id)
    {
        $model = new WechatUser();
        $user['lang'] = 'cn';
        $model->setAttributes($user);
        $model->nickname = self::filterEmoji($user['nickname']);
        $model->create_time = date('Y-m-d H:i:s');
        $model->sex = $user['sex'] == 1 ? 1 : 2;
        $model->brand = $brand_id;
        if ($model->save()) {
            \Yii::warning('创建用户成功', 'wechat');
            return $model->id;
        } else {
            \Yii::warning('创建用户失败' . var_export($model->getErrors()), 'wechat');
            return false;
        }
    }

    public static function filterEmoji($string)
    {
        $string = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $string);
        return $string;
    }
}
