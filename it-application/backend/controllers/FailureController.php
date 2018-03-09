<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/9/25
 * Time: 上午10:11
 */

namespace backend\controllers;

use common\models\FailureBonus;
use yii;
use yii\web\Controller;

class FailureController extends Controller
{
    //关闭csrf验证
    public $enableCsrfValidation = false;

    public function bonus()
    {
        $vip_code = Yii::$app->request->post('vip_code');
        $reason = Yii::$app->request->post('reason', 'UNKOWN');
        $params = Yii::$app->request->post('params');
        $record = new FailureBonus();
        $record->create_time = date('Y-m-d H:i:s');
        $record->process_time = date('Y-m-d H:i:s');
        $record->reason = $reason;
        $record->vip_code = $vip_code;
        $record->params = $params;
        $record->status = FailureBonus::WAIT_PROCESS;
        if ($record->save()){
            Yii::error('保存积分添加失败成功');
        } else{
            Yii::error('保存积分添加失败');
            Yii::error(json_encode($record->getErrors()));
        }

    }

}