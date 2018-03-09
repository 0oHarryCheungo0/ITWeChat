<?php

namespace common\api;

use backend\models\Brand;
use Yii;

class BrandApi
{
    /**
     * 根据品牌获取详情
     * 设置缓存，不需要每次都从数据库获取
     * @author fushl 2017-05-25
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getBrandById($id)
    {
        $key = self::keyGenerator($id);
        $cache = Yii::$app->redis->get($key);
        if (is_null($cache)) {
            //null为没有设置缓存
            $result = Brand::findWxById($id);//将brand表的东西全部拿下来
            if (!$result) {
                $cache = false;
            } else {
                $cache = [
                    'app_id' => $result->appid,
                    'secret' => $result->appsecret,
                    'token' => $result->token,
                    'theme' => empty($result->identify) ? 'default' : $result->identify,
                ];
                $cache = json_encode($cache);
            }
            Yii::$app->redis->set($key, $cache);
        }

        return $cache ? json_decode($cache, true) : false;
    }

    /**
     * redis键值生成器
     * @param  int $id 品牌ID
     * @return string     生成的键值
     */
    public static function keyGenerator($id)
    {
        return 'it_wechat_brand' . $id;
    }

    /**
     * 缓存重置
     * 在后台编辑更新品牌ID时，需要删除 key;
     * @param  int $id 品牌ID
     * @return boolean
     */
    public static function resetBrand($id)
    {
        $key = self::keyGenerator($id);
        Yii::$app->redis->del($key);
        return true;
    }
}
