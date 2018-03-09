<?php

namespace backend\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    public function actionLoad()
    {

        return $this->renderPartial('load');
    }

    public function actionUpdate()
    {
        $vip_no = Yii::$app->request->get('vip_no');
        $phone  = Yii::$app->request->get('phone');
        $array  = ['WX00003733', 'WX00003758'];
        if (in_array($vip_no, $array)) {
            $relation = MemberRelation::find()->where(['member_id' => $vip_no])->one();
            if (!empty($relation)) {
                if ($relation->phone) {
                    echo '电话号码已存在';
                } else {
                    $relation->phone = $phone;
                    $relation->save();
                }

            } else {
                throw new \Exception('会员不存在关系表');
            }
        } else {
            echo 'VIP_NO不属于空缺电话号码';
        }
    }

    public function action()
    {

        return $this->render('aa');

    }

}
