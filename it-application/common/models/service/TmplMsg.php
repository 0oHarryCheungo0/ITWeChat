<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/5
 * Time: 下午4:23
 */

namespace common\models\service;

use yii;

class TmplMsg
{
    public static function sendMsg($templateId, $openid, $url, $datas)
    {

        try {
            $notice = Yii::$app->wechat->app()->notice;
            $notice->uses($templateId)
                ->withUrl($url)
                ->andData($datas)
                ->andReceiver($openid)
                ->send();
            Yii::info('=====推送消息======');
            Yii::info($datas);

            return true;
        } catch (\Exception $e) {
            Yii::info('=========推送消息失败========');
            return false;
        }
    }

}