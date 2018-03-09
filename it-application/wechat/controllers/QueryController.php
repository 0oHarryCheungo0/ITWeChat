<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/9/8
 * Time: 下午4:51
 */

namespace wechat\controllers;


use common\api\BrandApi;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use yii\web\Controller;
use yii;

class QueryController extends Controller
{
    public $enableCsrfValidation = false;
    private $brand_config;

    public function actionFindByOpenid()
    {
        $openid = Yii::$app->request->get('openid');
        if ($openid) {
            /** @var WechatUser $wechat */
            $wechat = WechatUser::find()->where(['openid' => $openid])->one();
            if (!$wechat) {
                return $this->response('', 400, 'openid错误');
            } else {
                $wechat_info = [
                    'openid' => $wechat->openid,
                    'nickname' => $wechat->nickname,
                    'headimgurl' => $wechat->headimgurl,
                ];
                /** @var WechatVip $vip */
                $vip = WechatVip::find()->where(['wechat_user_id' => $wechat->id])->with('info')->one();
                if ($vip) {
                    $is_vip = true;
                    $vip_info = [
                        'vip_code' => $vip->vip_no,
                        'vip_type' => $vip->vip_type,
                        'phone' => $vip->phone,
                        'addr' => $vip->info->addr1,
                    ];
                } else {
                    $is_vip = false;
                    $vip_info = null;
                }
                $res = ['wechat_info' => $wechat_info, 'is_vip' => $is_vip, 'vip_info' => $vip_info];
                return $this->response($res);
            }
        } else {
            return $this->response('', 400, 'openid错误');
        }
    }


    public function actionJssdk()
    {
        $ref = Yii::$app->request->post('ref');
        if (!$ref) {
            return $this->response('', 501, 'need ref link');
        }
        $apis = Yii::$app->request->post('apis');
        if (!$apis) {
            return $this->response('', 502, 'need api list');
        }
        $apis_array = explode(',', $apis);
        $this->getBrand();
        try {
            $wechat = Yii::$app->wechat->app($this->brand_config);
            $js = $wechat->js;
            $js->setUrl($ref);
            $config_data = $js->config($apis_array, false, false, false);
            $res = [
                'appId' => $config_data['appId'],
                'nonceStr' => $config_data['nonceStr'],
                'timestamp' => $config_data['timestamp'],
                'signature' => $config_data['signature'],
                'jsApiList' => $config_data['jsApiList'],
            ];
            return $this->response($res);
        } catch (\Exception $e) {
            return $this->response('', 500, 'system error');
        }


    }


    /**
     * 获取品牌信息
     * @return bool [description]
     */
    private function getBrand()
    {
        //调用接口，获取品牌微信号app_id,secret,token，theme
        $config = BrandApi::getBrandById(1);
        if (!$config) {
            return false;
        }
        $config['brand_id'] = 1;
        $this->brand_config = $config;
        return true;
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