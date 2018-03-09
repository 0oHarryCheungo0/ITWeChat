<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/19
 * Time: 下午3:28
 */

namespace wechat\models\service;


class VipProfile
{

    public function __construct($vip_code)
    {
        $this->vip_code = $vip_code;
        return true;
    }


    /**
     * 设置VIP信息
     */
    public function setVips()
    {

    }

}