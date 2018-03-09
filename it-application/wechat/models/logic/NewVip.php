<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/18
 * Time: 上午10:10
 */

namespace wechat\models\logic;

use wechat\models\service\VipCreater;
use wechat\models\WechatUser;
use yii;

/**
 * 新会员创建，根据微信基础信息创建会员。
 * Class NewVip
 * @package wechat\models\logic
 */
class NewVip
{
    protected $brand_id = 1;

    protected $wechat_info = [];

    protected $vip_type;

    protected $phone;

    protected $creater;

    public function __construct($brand_id)
    {
        $this->brand_id = $brand_id;
        switch ($this->brand_id) {
            case 1:
                $this->vip_type = 'CN_BITT0';
                break;
            case 2:
                $this->vip_type = 'CN_SITT0';
                break;
            default:
                $this->vip_type = 'CN_BITT0';
                break;
        }
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function setWechatInfo($wechat_id)
    {
        /** @var WechatUser $info */
        $info = WechatUser::findOne($wechat_id);
        $this->wechat_info = [
            'name' => $this->filterEmoji($info->nickname),
            'phone' => $this->phone,
            'sex' => $info->sex == 1 ? 1 : 2,
            'email' => '',
            'birthday' => '2017-01-01',
            'vip_type' => $this->vip_type,
        ];
    }

    public function filterEmoji($string)
    {
        $string = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $string);
        return $string;
    }

    public function create()
    {
        try {
            $creater = new VipCreater($this->wechat_info);
            $creater->getConfig($this->brand_id)
                ->setWechatId(Yii::$app->session->get('wechat_id'))
                ->setBrand($this->brand_id)
                ->setRequestUrl(Yii::$app->params['middleware_reg']);
            $vip = $creater->buildPost()->createVip();
            return $vip;
        } catch (\Exception $e) {
            Yii::error('创建会员失败');
            Yii::error($e->getMessage());
            return false;
        }
    }

}