<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/21
 * Time: 下午2:47
 */

namespace wechat\models\logic;


use wechat\models\VipInfomation;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use common\models\BrandVipConfig;
use GuzzleHttp\Client;
use yii;

class VIPBuilder
{
    private $phone;

    private $vrid;

    private $exp_date;

    private $type;

    private $brand;

    private $request_url;

    private $response;

    private $wechat_user;

    private $store_id = null;

    public function __construct($phone, $brand, $store_id = false)
    {
        $this->phone = $phone;
        $this->getBrandConfig($brand);
        $this->brand = $brand;
        if ($brand == 1) {
            $this->type = 'BIT';
        } else {
            $this->type = 'SIT';
        }
        $this->request_url = Yii::$app->params['create_vip'];
        return true;
    }

    public function bindStore($store_id)
    {
        if ($store_id) {
            $this->store_id = $store_id;
        }
    }

    public function setWechatUser($id)
    {
        $this->wechat_user = $id;
    }


    public function buildVip()
    {
        $post = [
            'phone' => $this->phone,
            'vrid' => $this->vrid,
            'type' => $this->type,
            'exp_date' => $this->exp_date,
        ];
        if ($this->request($post)) {
            $this->setLocalProfile();
            $this->setLocalVip();
            return $this->response;
        } else {
            //创建会员失败，返回false;
            return false;
        }
    }

    private function setLocalVip()
    {
        //设置本地VIP信息
        $vip = new WechatVip();
        $vip->vip_no = $this->response->vip->VIPKO;
        $vip->vip_type = $this->response->vip->VIPTYPE;
        $vip->exp_date = $this->response->vip->EXPDATE;
        $vip->wechat_user_id = $this->wechat_user;
        $vip->brand = $this->brand;
        $vip->member_id = $this->response->vip->ALTID;
        $vip->join_date = $this->response->vip->JOINDATE;
        $vip->phone = $this->phone;
        $vip->name = $this->response->profile->VIPNAME;
        $vip->email = $this->response->profile->EMAILADDR;
        $vip->sex = $this->response->profile->SEX == 'F' ? 0 : 1;
        $vip->birthday = $this->response->profile->DOB ?: '2017-01-01 00:00:00';
        $vip->name_first = $this->response->profile->W_FNAME;
        $vip->name_first = $this->response->profile->W_FNAME == null ? $this->response->profile->VIPNAME : $this->response->profile->W_FNAME;

        $vip->name_last = $this->response->profile->W_LNAME;
        $vip->update_time = date('Y-m-d H:i:s');
        $vip->bind_time = date('Y-m-d H:i:s');
        $vip->store_id = $this->store_id;
        if (!$vip->save()) {
            Yii::error('VIP保存失败');
            Yii::error($vip->getErrors());
        }
    }

    private function setLocalProfile()
    {
        $profile = VipInfomation::find()->where(['altid' => $this->phone])->one();
        if (!$profile) {
            $profile = new VipInfomation();
            $profile->altid = $this->phone;
        }
        $profile->addr1 = $this->response->profile->ADDR1;
        $profile->addr2 = $this->response->profile->ADDR2;
        $profile->income = $this->response->profile->W_SALARY;
        $profile->education = $this->response->profile->W_EDUCATION;
        $profile->interest = $this->response->profile->W_INTERESTS;
        $profile->career = $this->response->profile->W_OCCUPATION;
        $profile->area = $this->response->profile->W_COUNTRY;
        $profile->province = $this->response->profile->W_PROVINCE;
        $profile->city = $this->response->profile->W_CITY;
        $profile->town = $this->response->profile->W_DISTRICT;
        if (!$profile->save()) {
            Yii::error('资料保存失败');
            Yii::error($profile->getErrors());
        }
    }

    private function request($post)
    {
        $client = new Client();
        try {
            $request = $client->request('POST', $this->request_url, ['form_params' => $post]);
        } catch (\Exception $e) {
            Yii::error('请求接口错误' . $e->getMessage(), 'middleware');
            return false;
        }
        $return = $request->getBody()->getContents();
        Yii::error('=========请求返回结果' . $return, 'REQUEST');
        $ret = json_decode($return);
        if ($ret->code == 0) {
            $this->response = $ret->data;
            return true;
        } else {
            Yii::error('生成会员失败', 'middleware');
            return false;
        }
    }


    private function getBrandConfig($brand)
    {
        /** @var BrandVipConfig $brand_config */
        $brand_config = BrandVipConfig::find()->where(['brand_id' => $brand])->one();
        if (!$brand_config)
            throw new \Exception('后台没有设置会员生成规则');
        $this->vrid = $brand_config->vr_prefix;
        if ($brand_config['exp_time'] == 0) {
            $exp_date = '2099-12-31 00:00:00';
        } else {
            $later = $brand_config['exp_time'] + 1;
            $str = '+' . $later . ' month';
            $more_1day_month = date('Y-m', strtotime($str) - 1);
            $exp_date = date('Y-m-d', strtotime($more_1day_month) - (60 * 60 * 24));
        }
        $this->exp_date = $exp_date;
        return $this;
    }
}