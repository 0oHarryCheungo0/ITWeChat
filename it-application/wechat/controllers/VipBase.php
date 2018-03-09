<?php

namespace wechat\controllers;

use yii;

class VipBase extends WechatBase
{
    public $vip;

    public function beforeAction($action)
    {
        parent::beforeAction($action);
        $check = $this->checkVip();
        if (!$check) {
            return $this->redirect(['login/index', 'brand_id' => $this->brand_id])->send();
        } else {
            $this->vip = $check;
            return true;
        }
    }

}
