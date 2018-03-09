<?php

namespace backend\service\wechat\functions;

use common\api\BrandApi;
use Yii;

class Qrcode
{
    /**
     * 生成临时带参二维码。
     * @param int $brand_id 品牌ID
     * @param string $key 二维码键值
     * @param int $exp 过期时间
     * @return bool/string 返回false 或 二维码url
     */
    public static function temporary($brand_id, $key, $exp = 3600)
    {
        $config = BrandApi::getBrandById($brand_id);
        if (!$config) {
            Yii::error('获取品牌信息失败', 'wechat-functions');
            return false;
        }
        $qrcode = Yii::$app->wechat->app($config)->qrcode;
        $result = $qrcode->temporary($key, $exp);
        $ticket = $result->ticket;
        return $qrcode->url($ticket);
    }

    /**
     * 生成永久带参二维码
     * @param int $brand_id 品牌ID
     * @param string $key 键值
     * @return bool/string 返回false或二维码url
     */
    public static function forever($brand_id, $key)
    {
        $config = BrandApi::getBrandById($brand_id);
        if (!$config) {
            Yii::error('获取品牌信息失败', 'wechat-functions');
            return false;
        }
        $qrcode = Yii::$app->wechat->app($config)->qrcode;
        $result = $qrcode->forever($key);
        $ticket = $result->ticket;
        return $qrcode->url($ticket);
    }
}
