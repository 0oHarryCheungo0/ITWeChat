<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/13
 * Time: 上午9:25
 */

namespace common\middleware;

use GuzzleHttp\Client;
use wechat\models\logic\VIPBuilder;
use wechat\models\VipInfomation;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use yii;

class User
{
    public static function findByPhone($phone, $type)
    {
        $url    = Yii::$app->params['middleware_find'];
        $client = new Client();
        try {
            $request = $client->request('GET', $url, ['query' => ['phone' => $phone, 'type' => $type, 'access_token' => 1]]);
            $return  = $request->getBody()->getContents();
            $ret     = json_decode($return, true);
            return $ret;
        } catch (\Exception $e) {
            Yii::error('异常' . $e->getMessage());
            return false;
        }

    }

    public static function getInfo($vip_code)
    {
        $url     = Yii::$app->params['middleware_user_info'];
        $client  = new Client();
        $request = $client->request('GET', $url, ['query' => ['vip_code' => $vip_code, 'access_token' => 1]]);
        $return  = $request->getBody()->getContents();
        $ret     = json_decode($return, true);
        return $ret['data'];
    }

    public static function findUser($phone, $type, $area = '中国')
    {
        //先找本地的微信用户
        $wechat = WechatUser::findOne(Yii::$app->session->get('wechat_id'));
        $name   = $wechat->nickname;
        $sex    = $wechat->sex == 1 ? 'M' : 'F';
        $url    = Yii::$app->params['middleware_phone'];
        $client = new Client();
        try {
            $request = $client->request('GET', $url,
                ['query' => ['phone' => $phone,
                    'type'               => $type,
                    'name'               => $name,
                    'sex'                => $sex,
                    'area'               => $area,
                    'wechat_id'          => $wechat->openid],
                ]);
            $return = $request->getBody()->getContents();
            $ret    = json_decode($return, true);
            return $ret;
        } catch (\Exception $e) {
            Yii::error('异常' . $e->getMessage());
            return false;
        }
    }

    public static function renewUser($phone, $type, $openid = 1)
    {
        $url    = Yii::$app->params['middleware_phone'];
        $client = new Client();
        try {
            $request = $client->request('GET', $url, [
                'query' => [
                    'phone'     => $phone,
                    'type'      => $type,
                    'name'      => '',
                    'sex'       => '',
                    'wechat_id' => $openid,
                ],
            ]);
            \Yii::error('传入wechat_id就为'.$openid);
            $return = $request->getBody()->getContents();
            $ret    = json_decode($return, true);
            return $ret;
        } catch (\Exception $e) {
            Yii::error('异常' . $e->getMessage());
            return false;
        }
    }

    public static function bindVip($vip, $profile, $brand, $store_id = false)
    {
        Yii::error('绑定VIP关系更新');
        $wechatvip = WechatVip::find()->where(['vip_no' => $vip['VIPKO'], 'brand' => $brand])->one();
        if (!$wechatvip) {
            $wechatvip             = new WechatVip();
            $wechatvip->brand      = $brand;
            $wechatvip->vip_no     = $vip['VIPKO'];
            $wechatvip->vip_type   = $vip['VIPType'];
            $wechatvip->join_date  = $vip['JoinDate'];
            $wechatvip->exp_date   = $vip['ExpDate'];
            $wechatvip->phone      = $profile['TELNO'];
            $wechatvip->name       = $profile['VIPNAME'];
            $wechatvip->email      = $profile['EMAILADDR'];
            $wechatvip->sex        = $profile['SEX'] == 'M' ? 0 : 1;
            $wechatvip->birthday   = $profile['DOB'];
            $wechatvip->name_first = $profile['W_FNAME'] == null ? $profile['VIPNAME'] : $profile['W_FNAME'];
            $wechatvip->name_last  = $profile['W_LNAME'];
        }
        $wechatvip->member_id      = $vip['AltID'];
        $wechatvip->wechat_user_id = Yii::$app->session->get('wechat_id');
        $wechatvip->update_time    = date('Y-m-d H:i:s');
        $wechatvip->bind_time      = date('Y-m-d H:i:s');
        if ($store_id) {
            $wechatvip->store_id = $store_id;
        }
        if (!$wechatvip->save()) {
            Yii::error($wechatvip->getErrors());
            return false;
        }
        $vipProfile = VipInfomation::find()->where(['altid' => $profile['ALTID']])->one();
        if (!$vipProfile) {
            $vipProfile        = new VipInfomation();
            $vipProfile->altid = $profile['ALTID'];
        }
        $vipProfile->addr1     = $profile["ADDR1"];
        $vipProfile->addr2     = $profile['ADDR2'];
        $vipProfile->income    = $profile['W_SALARY'];
        $vipProfile->education = $profile['W_EDUCATION'];
        $vipProfile->interest  = $profile['W_INTERESTS'];
        $vipProfile->career    = $profile['W_OCCUPATION'];
        $vipProfile->area      = $profile['W_COUNTRY'];
        $vipProfile->province  = $profile['W_PROVINCE'];
        $vipProfile->city      = $profile['W_CITY'];
        $vipProfile->town      = $profile['W_DISTRICT'];
        if (!$vipProfile->save()) {
            Yii::error($vipProfile->getErrors());
            return false;
        }
        return true;

    }

    public static function create($phone, $brand)
    {
        $create = new VIPBuilder($phone, $brand);
        $create->setWechatUser(Yii::$app->session->get('wechat_id'));
        return $create->buildVip();
    }

}
