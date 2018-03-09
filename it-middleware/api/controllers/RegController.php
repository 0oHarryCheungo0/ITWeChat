<?php

namespace api\controllers;

use api\models\logic\VipBuilder;
use api\models\service\VipReg;
use api\models\VipIndexs;
use Yii;

class RegController extends APIBase
{
    public function actionIndex()
    {
        $a = Yii::$app->redis->incr('test');
        var_dump($a);
    }

    public function actionVip()
    {
        $post_data = Yii::$app->request->getRawBody();
        //注册，先验证手机号
        $request = json_decode($post_data, true);
        if (!$request) {
            $this->response('', 501, '请求错误');
        }
        try {
            $rs = VipReg::reg($request);
            return $this->response($rs);
        } catch (\Exception $e) {
            return $this->response('', 500, $e->getMessage());
        }
    }

    public function actionReg()
    {
        $phone = Yii::$app->request->post('phone');
        $vrid = Yii::$app->request->post('vrid');
        $type = Yii::$app->request->post('type');
        $exp_date = Yii::$app->request->post('exp_date');


        $builder = new VipBuilder($phone,$type);
        $builder->setVRID($vrid);
        $builder->setExp($exp_date);
        $res = $builder->build();
        if($res){
            return $this->response($res);
        } else {
            return $this->response('',500,'创建会员失败');
        }




    }



    public function actionTest()
    {
        $set = VipIndexs::findOne(1);
        $vip_code_suffix = sprintf("%08d", $set['value']);
        $set->value++;
        $set->update();
        echo $vip_code_suffix;
    }
}
