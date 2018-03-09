<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/19
 * Time: 下午5:43
 */

namespace common\middleware;

use yii;

class Queue
{
    public static function getUrl($alias)
    {
        $urls = [
            //短信接口
            'msg' => Yii::$app->params['queue_msg'],
            //更新会员信息接口
            'renew' => Yii::$app->params['queue_renew'],
            //添加积分接口
            'bonus' => Yii::$app->params['queue_bonus'],
            //异步更新积分记录表
            'sync_bonus' => Yii::$app->params['queue_sync_bonus'],
            //资讯
            'news' => Yii::$app->params['news'],

            'discount' => Yii::$app->params['discount'],

            'news_discout_reset' => Yii::$app->params['news_discout_reset'],

            'discount_rank' => Yii::$app->params['discount_rank'],

            'discount_birthday' => Yii::$app->params['discount_birthday'],

            'news_rank' => Yii::$app->params['news_rank'],

            'news_expire' => Yii::$app->params['news_expire'],

            'news_sign' => Yii::$app->params['news_sign'],

            'send_message' => Yii::$app->params['queue_send_message'],

            'update_vip' => Yii::$app->params['update_vip'],

            'news_prefect' => Yii::$app->params['news_prefect'],

            'renew_info' => Yii::$app->params['renew_info'],

            'import_staff' => Yii::$app->params['import_staff'],

            'send_spend_message' => Yii::$app->params['send_spend_message'],

            'send_point_message' => Yii::$app->params['send_point_message'],

            'send_exp_message' => Yii::$app->params['send_exp_message'],
        ];
        return $urls[$alias];
    }

    /**
     * 发送请求到队列。失败则返回false
     * @param $url
     * @param array $data
     * @param string $type
     * @return bool
     * @throws \Exception  
     */
    public static function sendQueue($url, $data = [], $type = 'get')
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        try {
            $client->connect('127.0.0.1', 9502, -1);
        } catch (\Exception $e) {
            throw new \Exception('连接队列服务器失败');
            return false;
        }
        $package = [
            'url' => $url,
            'request_type' => $type,
            'param' => $data,
        ];
        $client->send(json_encode($package));
        $client->close();
        return true;
    }

    /**
     * 推送短信
     * @param $phone
     * @param $code
     */
    public static function sendMsg($phone, $code)
    {

    }

    /**
     * 请求增加积分
     * @param $data
     * @return bool
     */
    public static function sendBonus($data)
    {
        return self::sendQueue(self::getUrl('bonus'), $data, 'post');
    }

    /**
     * 更新会员资料
     * @param $vip_code
     * @return bool
     */
    public static function renew($vip_code)
    {
        return self::sendQueue(self::getUrl('renew'), ['vip_code' => $vip_code]);
    }

    /**
     * 异步更新积分记录
     */
    public static function syncBonus()
    {
        return self::sendQueue(self::getUrl('sync_bonus'));
    }

    public static function sendNews($id)
    {
        return self::sendQueue(self::getUrl('news'), ['id' => $id]);
    }

    /**
     * 队列限时优惠
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function sendDiscount($id)
    {
        return self::sendQueue(self::getUrl('discount'), ['id' => $id]);
    }

    /**
     * 队列资讯优惠初始化信息
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function resetNews($brand_id)
    {
        return self::sendQueue(self::getUrl('news_discout_reset'), ['brand_id' => $brand_id]);
    }

    public static function sendRank($news_id, $no, $brand_id)
    {
        return self::sendQueue(self::getUrl('discount_rank'), ['news_id' => $news_id, 'vip_no' => $no, 'brand_id' => $brand_id]);
    }

    public static function sendBirth($news_id, $no, $brand_id)
    {
        return self::sendQueue(self::getUrl('discount_birthday'), ['news_id' => $news_id, 'vip_no' => $no, 'brand_id' => $brand_id]);
    }

    public static function sendNewsRank($news_id, $no, $brand_id)
    {
        return self::sendQueue(self::getUrl('news_rank'), ['news_id' => $news_id, 'vip_no' => $no, 'brand_id' => $brand_id]);
    }

    public static function sendNewsExpire($news_id, $no, $brand_id)
    {
        return self::sendQueue(self::getUrl('news_expire'), ['news_id' => $news_id, 'vip_no' => $no, 'brand_id' => $brand_id]);
    }

    public static function sendNewsPoint($brand_id)
    {
        return self::sendQueue(self::getUrl('news_sign'), ['brand_id' => $brand_id]);
    }

    public static function sendWechatMessage($message_id)
    {
        return self::sendQueue(self::getUrl('send_message'), ['send_id' => $message_id], 'post');
    }

    public static function updateVip($vip_code)
    {
        return self::sendQueue(self::getUrl('update_vip'), ['vip_code' => $vip_code]);
    }

    public static function sendNewsPrefect($brand_id)
    {
        return self::sendQueue(self::getUrl('news_prefect'), ['brand_id' => $brand_id]);
    }

    public static function renewInfo($vip_code)
    {
        return self::sendQueue(self::getUrl('renew_info'), ['vip_code' => $vip_code]);
    }

    public static function importStaff($brand_id, $store_id, $staff_name, $code, $extra)
    {
        return self::sendQueue(self::getUrl('import_staff'), ['brand_id' => $brand_id, 'store_id' => $store_id, 'staff_name' => $staff_name, 'code' => $code, 'extra' => $extra]);
    }

    public static function sendSpendMsg($params)
    {
        return self::sendQueue(self::getUrl('send_spend_message'), $params);
    }

    public static function sendPointMsg($params)
    {
        return self::sendQueue(self::getUrl('send_point_message'), $params);
    }

    public static function sendExpMsg($vip_code)
    {
        return self::sendQueue(self::getUrl('send_exp_message'), ['vip_code' => $vip_code]);
    }
}
