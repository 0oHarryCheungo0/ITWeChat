<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/14
 * Time: 下午5:24
 */

namespace console\controllers;


use common\middleware\Queue;
use wechat\models\VipBonus;
use yii\console\Controller;
use yii;

class SendMessageController extends Controller
{
    /**
     * 获取消费记录，推送到队列
     * @param bool $date
     */
    public function actionSpend($date = false)
    {
        if (!$date) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }
        $url = Yii::$app->params['day_spned'] . "?date=" . $date;
        $spends_json = file_get_contents($url);
        $spends = json_decode($spends_json, true);
        foreach ($spends['data'] as $spend) {
            Queue::sendSpendMsg($spend);
        }
    }


    /**
     * 推送积分消费记录
     * @param $date
     */
    public function actionPointSend($date = false)
    {
        if (!$date) {
            $date = date('Y-m-d', strtotime('-1 day'));
        }
        $url = Yii::$app->params['bonus_used'] . "?date=" . $date;
        $spends_json = file_get_contents($url);
        $spends = json_decode($spends_json, true);
        foreach ($spends['data'] as $spend) {
            Queue::sendPointMsg($spend);
        }
    }


    /**
     * 推送过期积分信息，每月第一天推送
     */
    public function actionExpPoint()
    {
        /** @var VipBonus $vips */
        $vips = VipBonus::find()->where(['>','update_time', time()-3600])->all();
        foreach ($vips as $vip) {
            Queue::sendExpMsg($vip->vip_code);
        }

    }

}