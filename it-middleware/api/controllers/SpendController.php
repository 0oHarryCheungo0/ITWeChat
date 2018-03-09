<?php
namespace api\controllers;

use api\models\service\SpendService;
use Yii;

/**
 * 用户消费历史记录
 * @package api\controllers
 */
class SpendController extends APIBase
{

    public function actionQuery()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        $spend    = SpendService::getSpends($vip_code);
        $this->response($spend);
    }

    public function actionDetail()
    {
        $id = Yii::$app->request->get('id');
        $detail = SpendService::getSpendById($id);
        $this->response($detail);
    }

    public function actionToday()
    {
        $day = Yii::$app->request->get('date');
        $vips = SpendService::getSpendByDay($day);
        $this->response($vips);
    }
}
