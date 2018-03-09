<?php
/**
 * 使用easywechat包，构建 Yii::$app->wechat->app 对象，直接在构造函数里面返回 Application对象
 * 默认把token设置在redis里。方便后续分布式调用
 */

namespace common\components;

use common\exceptions\WechatException as Exception;
use common\extend\RedisCache;
use EasyWeChat\Foundation\Application;
use yii\base\Component;

class WechatApp extends Component
{

    /**
     * 是否开启redis，适用于 分布式架构 多个主机共享 token
     * @var boolean
     */
    public $redis = false;

    public $cache_type;

    private static $_app;

    /**
     * @param array $config
     * @return Application
     * @throws Exception
     */
    public function app($config = [])
    {
        if (!self::$_app instanceof Application) {
            if (!isset($config['app_id'])) {
                throw new Exception("缺少app_id参数");
            }
            if (!isset($config['secret'])) {
                throw new Exception("缺少secret参数");
            }
            if (!isset($config['token'])) {
                throw new Exception("缺少token参数");
            }
            if ($this->redis !== false) {
                $cacheDriver = new RedisCache();
                $redis = \Yii::$app->redis;
                $cacheDriver->setRedis($redis);
                $config['cache'] = $cacheDriver; 
            }
            self::$_app = new Application($config);
        }
        return self::$_app;

    }
}
