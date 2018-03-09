<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/5
 * Time: 下午4:30
 */

namespace api\controllers;
use api\models\service\BPlogService;
use yii;

/**
 * 用户积分获取、使用记录
 * @package api\controllers
 */
class RecordController extends APIBase
{
    public function actionGet()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        $spend    = BPlogService::getRecord($vip_code);
        $this->response($spend);
    }

    public function actionSpend()
    {
        $id = Yii::$app->request->get('vip_code');
        $detail = BPlogService::spendRecord($id);
        $this->response($detail);
    }

    public function actionDetail()
    {
        $id = Yii::$app->request->get('id');
        $detail = BPlogService::detail($id);
        $this->response($detail);
    }

}