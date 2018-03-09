<?php
namespace backend\controllers;

use yii\web\Controller;
use common\models\User;
use common\models\WechatVipsActiveLogs;
use wechat\models\WechatVip;
use common\middleware\Bonus;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $c=\yii::$app->user;
        var_dump($c);
        //\yii::$app->test->test();
    }

    public function actionTest(){
        $brand_id =2;
        $vip_code = 'WX00000007';
        $result = Bonus::tmpPoint($brand_id,$vip_code);
        if ($result) {
            echo '成功';
        }else{
            echo '失败';
        }
    }
}