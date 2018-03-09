<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/26
 * Time: 下午4:59
 */

namespace wechat\controllers;


use wechat\models\WechatUser;
use wechat\models\WechatVip;
use yii\helpers\Url;
use yii\web\Controller;
use yii;

class ServerController extends Controller
{

    public function actionIndex()
    {

        $flag = false;
        $openid = Yii::$app->request->get('openid');
        $brand_id = Yii::$app->request->get('brand_id');
        /** @var WechatUser $user */
        $user = WechatUser::find()->where(['openid' => $openid])->one();
        if (!$user) {
            $flag = false;
        } else {
            $vip = WechatVip::find()->where(['wechat_user_id' => $user->id])->one();
            if ($vip) {
                $flag = true;
            } else {
                $flag = false;
            }
        }
        $url = Url::to(['index/index', 'brand_id' => $brand_id]);
        $assign = ['flag' => $flag, 'url' => $url];
        return $this->renderPartial('index', $assign);
    }

}