<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/19
 * Time: 下午3:35
 */

namespace wechat\models\service;


use common\models\BrandVipConfig;

class NewVipCreater
{
    private $brand_id;

    private $vr_prefix;

    private $exp_date;

    public function setBrand($brand)
    {
        $this->brand_id = $brand;

    }

    //新建会员


    /**
     * 获取会员注册配置
     * @return $this
     * @throws \Exception
     */
    public function getConfig()
    {
        /** @var BrandVipConfig $brand_config */
        $brand_config = BrandVipConfig::find()->where(['brand_id' => $this->brand_id])->one();
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