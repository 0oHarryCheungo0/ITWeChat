<?php

namespace api\controllers;

use api\models\service\BPlogService;
use Yii;

class BonusController extends APIBase
{

    public function actionTest()
    {
        $this->logError('test', '123', ['gogogo' => 123]);
    }

    public function actionQuery()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        $ret = BPlogService::getLogs($vip_code);
        $this->response($ret);
    }

    public function actionSpendPoint()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        $memono = Yii::$app->request->get('memono');
        $point = BPlogService::getOne($vip_code, $memono);
        return $this->response($point);
    }

    public function actionUsed()
    {
        $day = Yii::$app->request->get('date');
        $vips = BPlogService::getUsedByDay($day);
        $this->response($vips);
    }

    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if (isset($data['vip_code'])) {
                //整理数据键值
                $insert_data = [
                    'VIPKO' => $data['vip_code'],
                    'VIPTYPE' => $data['vip_type'],
                    'VBGROUP' => $data['vb_group'],
                    'VBID' => $data['vbid'],
                    'EXPDATE' => $data['exp_date'],
                    'TXDATE' => $data['tx_date'],
                    'MEMTYPE' => 'WI',
                    'BP' => $data['bp'],
                    'REFAMT' => 0,
                    'RECID' => date('YmdHis'),
                    'LOCKO' => 'WX',
                    'STDNO' => '0',
                    'MEMONO' => '0',
                ];
            } else {
                $insert_data = $data;
            }

            try {
                BPlogService::add($insert_data);
                $this->response($data);
            } catch (\Exception $e) {
                Yii::warning('添加积分异常' . $e->getMessage());
                $this->logError('BOUNS', $data['vip_code'] . " 添加积分失败,原因" . $e->getMessage(), $insert_data);
                $data = ['vip_code' => $data['vip_code'], 'reason' => '添加积分失败', 'params' => $insert_data];
                $this->response($data, 500, 'ERROR');
            }
        } else {
            $this->response('', 503, '方法错误,只接受post方法');
        }
    }

}
