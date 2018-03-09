<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/29
 * Time: 上午11:49
 */

namespace wechat\models\service;


use common\middleware\Bonus;
use common\models\BrandVipConfig;
use common\models\service\TmplMsg;
use GuzzleHttp\Client;
use wechat\models\logic\VipBind;
use wechat\models\VipInfomation;
use wechat\models\WechatVip;
use yii;

class VipCreater
{
    private $vr_prefix;

    private $exp_date;

    private $brand_id;

    private $wechat_id;

    private $data = null;

    private $post_json;

    private $response = null;

    private $request_url;

    private $type = 'BIT';

    private $vip;

    private $info;

    public function __construct($data = false)
    {
        if (!$data)
            throw new \Exception('设置基础信息失败');
        $rules = ['name', 'phone', 'sex', 'birthday', 'email', 'vip_type'];
        foreach ($rules as $rule) {
            if (!isset($data[$rule])) {
                throw new \Exception($rule . '不能为空');
            }
        }
        $this->data = $data;
    }

    /**
     * 设置WECHATID
     * @param $id
     * @return $this
     */
    public function setWechatId($id)
    {
        $this->wechat_id = $id;
        return $this;
    }

    /**
     * 设置品牌，
     * @param $brand_id
     * @return $this
     */
    public function setBrand($brand_id)
    {
        $this->brand_id = $brand_id;
        switch ($this->brand_id) {
            case 1:
                $this->type = 'BIT';
                break;
            case 2:
                $this->type = 'SIT';
                break;
            default:
                $this->type = 'BIT';
        }
        return $this;
    }


    /**
     * 构造POST数据
     * @return $this
     */
    public function buildPost()
    {
        $this->post_json = [
            'BRAND_ID' => $this->brand_id,
            'WECHAT_ID' => $this->wechat_id,
            'VIPNAME' => $this->getData('name'),
            'VRPREFIX' => $this->vr_prefix,
            'VIPTYPE' => $this->getData('vip_type'),
            'EXPDATE' => $this->exp_date,
            'EMAIL' => $this->getData('email'),
            'EMAILSUB' => $this->getData('sub_email'),
            'DOB' => '2017-01-01 00:00:00',
            'ADDR1' => '',
            'TELNO' => $this->getData('phone'),
            'SEX' => $this->getData('sex') == 1 ? 'M' : 'F',
            'GROUP' => $this->type,
        ];
        $this->request();
        return $this;
    }

    /**
     * 请求
     * @return bool
     */
    private function request()
    {
        $client = new Client();
        try {
            $request = $client->request('POST', $this->request_url, ['json' => $this->post_json]);
        } catch (\Exception $e) {
            Yii::error('请求接口错误' . $e->getMessage(), 'middleware');
            return false;
        }
        $return = $request->getBody()->getContents();
        Yii::error('=========请求放回结果' . $return, 'REQUEST');
        $ret = json_decode($return);
        if ($ret->code == 0) {
            $this->response = $ret->data;
            return true;
        } else {
            Yii::error('生成会员失败', 'middleware');
            return false;
        }
    }

    /**
     * 设置远程注册路径
     * @param $url
     * @return $this
     */
    public function setRequestUrl($url)
    {
        $this->request_url = $url;
        return $this;
    }

    /**
     * 获取填入参数
     * @param $key
     * @return string
     */
    private function getData($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return '';
        }
    }

    /**
     * 创建会员信息
     * @throws \Exception
     */
    public function createVip()
    {
        if ($this->response == null) {
            throw new \Exception('添加会员失败');
        }
        $vip = new WechatVip();
        $vip->exp_date = $this->response->exp_date;
        $vip->join_date = $this->response->join_date;
        $vip->vip_no = $this->response->vip_code;
        $vip->member_id = $this->response->alt_id;
        $vip->wechat_user_id = $this->wechat_id;
        $vip->brand = $this->brand_id;
        $vip->setAttributes($this->data);
        $vip->birthday = '2017-01-01 00:00:00';


        $info = new VipInfomation();
        $info->setAttributes($this->data);
        $info->vip_no = $this->response->vip_code;
        $info->send_point = 0;
        if (!isset($this->data['interest'])) {
            $info->interest = json_encode([]);
        } else {
            $info->interest = json_encode($this->data['interest']);
        }
        if ($vip->save() && $info->save()) {
            $this->vip = $vip;
            $this->info = $info;
            Bonus::regAdd($this->brand_id, $vip->vip_no);
            $this->afterCreate();
            return $vip->vip_no;
        }
    }

    /**
     * 注册完成后触发动作
     */
    private function afterCreate()
    {
        //1.添加注册积分
//        VipInfomation::canGetBonus($this->info);

        $tmpl_id = Yii::$app->params['template_id'][$this->brand_id]['reg'];
//        $tmpl_id = 'najwmXm2YiZkSSWSqtsmt9FGrhjUEOHjIA6d9obIMn8';
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $this->brand_id], true);
        $type = 'IT';
        if ($this->brand_id == 2) {
            $type = 'SIT';
        }
        $datas = [
            'first' => '亲爱的会员，您已成功注册会员卡，并获赠200积分。',
            'cardNumber' => $this->response->vip_code,
            'address' => $type,
            'VIPName' => $this->getData('name'),
            'VIPPhone' => $this->getData('phone'),
            'expDate' => $this->response->exp_date,
            'remark' => '请进入”会员中心“完善会员资料，领取积分奖励',
        ];
        TmplMsg::sendMsg($tmpl_id, Yii::$app->session->get('openid'), $url, $datas);

    }


    /**
     * 获取会员注册配置
     * @param $brand_id
     * @return $this
     * @throws \Exception
     */
    public function getConfig($brand_id)
    {
        $brand_config = BrandVipConfig::find()->where(['brand_id' => $brand_id])->one();
        if (!$brand_config)
            throw new \Exception('后台没有设置会员生成规则');
        $this->vr_prefix = $brand_config->vr_prefix;
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