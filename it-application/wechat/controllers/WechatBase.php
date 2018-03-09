<?php

namespace wechat\controllers;

use common\api\BrandApi;
use common\exceptions\WechatException as Exception;
use wechat\models\service\WechatUserService;
use wechat\models\WechatVip;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class WechatBase extends Controller
{
    public $wechat;

    public $brand_config;

    public $brand_id = false;

    public function beforeAction($action)
    {
        $this->brand_id = Yii::$app->request->get('brand_id', Yii::$app->session->get('brand_id',1));
        $lang = Yii::$app->session->get('lang');
        if (!$lang) {
            $lang = 'cn';
        }
        Yii::$app->language = $lang;
        if (!$this->brand_id) {
            if (Yii::$app->session->get('brand_id')) {
                $this->brand_id = Yii::$app->session->get('brand_id');
            } else {
                return $this->redirect(['error/brand'])->send();
            }
        }
        if (Yii::$app->session->get('brand_id') && $this->brand_id != Yii::$app->session->get('brand_id')) {
            Yii::warning('品牌信息已经更换，需要清理session来获取授权', 'wechat');
            $this->resetSessions();
            return $this->redirect(['index/index', 'brand_id' => $this->brand_id])->send();
        }
        if (!$this->getBrand()) {
            return $this->redirect(['error/brand-error'])->send();
        }
        //get_brand之后获取brand表里面的信息，根据identify字段选择样式
        Yii::$app->setLayoutPath('@wechat/theme/' . $this->brand_config['theme'] . '/layouts');
        $this->setViewPath('@wechat/theme/' . $this->brand_config['theme'] . '/' . $this->id);
        //$this->wechat = Yii::$app->wechat->app($this->brand_config);
        $userInfo = $this->userInfo();
        if (true === $userInfo) {
            return true;
        } else {
            return $this->redirect(Url::to(['error/brand']))->send();
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
            'scopes' => ['snsapi_userinfo'],
            'callback' => Yii::$app->request->getAbsoluteUrl(),
        ];
        $config['brand_id'] = $this->brand_id;

        $this->brand_config = $config;
        return true;
    }

    //检查是否为会员。是会员直接跳转到会员中心，不是会员则跳转到登录页

    private function userInfo()
    {
         if (Yii::$app->params['local'] == true) {
             if ($this->brand_id == 2) {
                 $this->setSessions(2, 'oX5m8vssuB1KbbWdqQL05Tmn6JR4', 'hk');
             } else {
                 $this->setSessions(1, 'oEsf7twyzWUjM4xBYpxMeIWS2YGY', 'hk');
             }
         }
        if (!Yii::$app->session->get('wechat_id')) {
            Yii::warning('没有登录信息,准备跳转授权', 'wechat');
            $oauth = $this->wechat->oauth;
            if (Yii::$app->request->get('code', false)) {
                try {
                    $user = $oauth->user();
                } catch (\Exception $e) {
                    Yii::error('获取用户信息异常' . $e->getMessage());
                    return false;
                }
                $openid = $user->id;
                $wechatUser = WechatUserService::findByOpenid($openid);
                if (!$wechatUser) {
                    Yii::warning('没有微信用户，准备新增', 'wechat');
                    $create_id = WechatUserService::createWechatUser($user->getOriginal(),
                        $this->brand_config['brand_id']);
                    if ($create_id !== false) {
                        Yii::warning('创建用户成功' . $create_id);
                        $this->setSessions($create_id, $openid);
                    }
                } else {
                    Yii::error('获取到用户信息' . $wechatUser->nickname, 'wechat');
                    Yii::error('用户的语言设置为' . $wechatUser->lang);
                    $this->setSessions($wechatUser->id, $wechatUser->openid, $wechatUser->lang);
                }
            } else {
                Yii::warning('跳转获取微信用户信息', 'wechat');
                $response = $oauth->scopes(['snsapi_userinfo'])->redirect();
                $response->send();
            }
        } else {

            if ($this->brand_id != Yii::$app->session->get('brand_id')) {
                Yii::warning('品牌信息与session不一致，需要重新授权', 'wechat');
                return false;
            }
            Yii::warning('获取到微信id' . Yii::$app->session->get('wechat_id'), 'wechat');
        }
        return true;
    }

    private function setSessions($wechat_id, $openid, $lang = 'cn')
    {
        Yii::$app->session->set('wechat_id', $wechat_id);
        Yii::$app->session->set('openid', $openid);
        Yii::$app->session->set('brand_id', $this->brand_id);
        if ($lang != 'hk') {
            $lang = 'cn';
        }
        Yii::$app->session->set('lang', $lang);
        Yii::$app->language = $lang;
        return true;
    }

    public function resetSessions()
    {
        Yii::$app->session->set('wechat_id', null);
        Yii::$app->session->set('openid', null);
        Yii::$app->session->set('brand_id', null);
        Yii::$app->session->set('vip', null);
        Yii::$app->session->set('lang', null);
        return true;
    }

    public function checkVip($flag = true)
    {
        $wechat_id = Yii::$app->session->get('wechat_id');

        $key = 'brand_id' . $this->brand_id . 'wechat_id' . $wechat_id;
        $wechat_vip = $this->getCache($key);
        if ($wechat_vip) {
            return $wechat_vip;
        } else {
            $wechat_vip = WechatVip::find()
                ->where([
                    'wechat_user_id' => $wechat_id,
                    'brand' => $this->brand_id,
                ])
                ->with('info')
                ->one();
            $this->cache($key, $wechat_vip, 60);
        }
        return $wechat_vip;
//        if (!Yii::$app->session->get('vip')) {
//            $vip = WechatVip::find()
//                ->where([
//                    'wechat_user_id' => Yii::$app->session->get('wechat_id'),
//                    'brand' => $this->brand_id,
//                ])
//                ->with('info')
//                ->one();
//            if ($vip) {
//                Yii::$app->session->set('vip', $vip);
//                return $vip;
//            } else {
//                return false;
//            }
//        } else {
//            return Yii::$app->session->get('vip');
//        }
    }


    protected function cache($key, $value, $exp = 60)
    {
        return Yii::$app->cache->set($key, $value, $exp);
    }

    protected function getCache($key)
    {
        $cache = Yii::$app->cache->get($key);
        return $cache;
    }

    /**
     * @param mixed $data 返回数据
     * @param int $code 错误代码
     * @param string $msg 返回消息内容
     */
    protected function response($data = null, $code = 0, $msg = 'SUCCESS')
    {
        $ret = ['code' => $code, 'msg' => $msg, 'data' => $data];
        $response = Yii::$app->response;
        $response->format = 'json';
        $response->data = $ret;
        Yii::error($ret, 'response');
        return $response->send($ret);
    }

}
