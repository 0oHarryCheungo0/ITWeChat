<?php

namespace wechat\controllers;

use wechat\models\logic\DiscountLogic;
use Yii;

class DiscountController extends VipBase
{
    public function actionList()
    {
        $this->view->title = 'OFFER';
        $uid               = $this->getUid();
        $request           = Yii::$app->request;
        $brand_id = $this->brand_id;
        $type   = $request->get('type', 1);
        $expire = DiscountLogic::countExclusive($uid);
        $normal = DiscountLogic::countNormal($uid);
        $page   = Yii::$app->request->get('page');
        $model  = new DiscountLogic();
        $data   = $model->getList($uid, '', $page);
        $ret    = ['data' => $data];
        return $this->render('list1', ['expire' => $expire, 'normal' => $normal, 'type' => $type, 'data' => $ret]);
    }

    public function actionDetail()
    {
        $this->view->title = 'OFFER';
        $id                = Yii::$app->request->get('id');
        $result            = DiscountLogic::getOne($id);

        return $this->render('detail', ['data' => $result]);
    }

    public function actionProfile()
    {
        //Yii::$app->language ='cn';
        $this->view->title = 'BENEFITS';
        $brand_id = $this->brand_id;
        $language          = Yii::$app->language;
        if ($language == 'cn') {
            return $this->renderPartial('profile',['brand_id'=>$brand_id]);
        } else {
            return $this->renderPartial('profile-hk',['brand_id'=>$brand_id]);
        }

    }

    public function actionPolicy()
    {
        //Yii::$app->language ='cn';
        $language = Yii::$app->language;
        if ($language == 'cn') {
            return $this->renderPartial('policy');
        } else {
            return $this->renderPartial('policy-hk');
        }
    }

    private function getUid()
    {
        $uid = Yii::$app->session->get('wechat_id');
        $vip = \wechat\models\WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        if (!empty($vip)) {
            return $vip->vip_no;
        } else {
            return 0;
        }
    }

}
