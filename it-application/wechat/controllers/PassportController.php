<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/9/8
 * Time: 下午3:12
 */

namespace wechat\controllers;

use common\api\BrandApi;
use wechat\models\service\WechatUserService;
use wechat\models\WechatVip;
use yii;

class PassportController extends yii\web\Controller
{
    public $wechat;

    private $brand_id = 1;

    private $brand_config;

    private $openid;

    private $hasWechat;

    private $wechat_id;

    public function beforeAction($action)
    {
        $this->getBrand();
        $this->wechat = Yii::$app->wechat->app($this->brand_config);
        if ($this->userInfo()) {
            return true;
        }
        return false;
    }

    public function actionIndex()
    {
        $callback = 'https://www.bestwang.cn';
        $callback = Yii::$app->request->get('callback', $callback);
        $reg_url = yii\helpers\Url::to(['login/index', 'brand_id' => 1, 'callback' => $callback], true);
        Yii::error('注册链接为' . $reg_url);
        if (!$this->hasWechat) {
            Yii::error('微信在系统中不存在，跳转到注册页面' . $reg_url);
            return $this->redirect($reg_url);
        } else {
            $user_id = $this->wechat_id;
            $vip = WechatVip::find()->where(['wechat_user_id' => $user_id])->one();
            if ($vip) {
                if (strpos($callback, '?')) {
                    $callback = $callback . "&openid=" . $this->openid;
                } else {
                    $callback = $callback . "?openid=" . $this->openid;
                }
                Yii::error('会员存在,跳转到具体活动页面' . $callback);
                return $this->redirect($callback);
            } else {
                //没会员,跳转到VIP注册
                Yii::error('有微信记录，但是没有会员记录，跳转到注册页面' . $reg_url);
                return $this->redirect($reg_url);
            }
        }
    }

    /**
     * 获取品牌信息
     * @return bool [description]
     */
    private function getBrand()
    {
        //调用接口，获取品牌微信号app_id,secret,token，theme
        $config = BrandApi::getBrandById($this->brand_id);
        if (!$config) {
            return false;
        }
        $config['oauth'] = [
            'scopes' => ['snsapi_base'],
            'callback' => Yii::$app->request->getAbsoluteUrl(),
        ];
        $config['brand_id'] = $this->brand_id;
        $this->brand_config = $config;
        return true;
    }

    //检查是否为会员。是会员直接跳转到会员中心，不是会员则跳转到登录页
    private function userInfo()
    {
        $oauth = $this->wechat->oauth;
        if (Yii::$app->request->get('code', false)) {
            try {
                $user = $oauth->user();
            } catch (\Exception $e) {
                Yii::error('获取用户信息异常' . $e->getMessage());
                return false;
            }
            $openid = $user->id;
            $this->openid = $openid;
            $wechatUser = WechatUserService::findByOpenid($openid);
            if (!$wechatUser) {
                $this->hasWechat = false;
            } else {
                Yii::error('获取到用户信息' . $wechatUser->nickname, 'wechat');
                $this->hasWechat = true;
                $this->wechat_id = $wechatUser->id;
            }
            return true;
        } else {
            $response = $oauth->scopes(['snsapi_base'])->redirect();
            $response->send();
        }
    }

}