<?php

namespace backend\service\wechat;

use backend\models\Store;
use backend\service\fans\FansList;
use common\models\logic\StaffLogic;
use common\models\WechatReplys;
use common\models\WechatScanLog;
use EasyWeChat\Support\Collection;
use yii;

class EventHandler
{
    public static function send(Collection $message, $brand_id)
    {
        switch ($message->Event) {
            //关注事件
            case 'subscribe':
                Yii::error('关注事件', 'wechat');
                Yii::error($message->EventKey, 'wechat');
                //检查关注事件中是否有EventKey,如果有的话，则是二维码扫描，需要解析二维码
                if ($message->EventKey) {
                    $content = '';
                    Yii::error('带参二维码关注事件', 'wechat');
                    $eventkey = explode('_', $message->EventKey);
                    $key = $eventkey[1];
                    Yii::error('获取到二维码的值' . $key, 'wechat');
                    self::saveScan($message->FromUserName, $key, true);
                    $setreply = WechatReplys::find()->where(['brand_id' => $brand_id, 'response_type' => 2])->one();
                    if ($setreply) {
                        $content = $setreply->response_text . "\n\n";
                    }
                    $staff = \backend\service\staff\StaffLogic::getStaffInfo($key);
                    $add = '';
                    if ($staff) {
                        $staff_name = $staff['staff_name'];
                        $staff_store_record = Store::findOne($staff['store_id']);
                        if ($staff_store_record) {
                            $staff_store = $staff_store_record->store_name;
                            $add = "当前为您服务的是【{$staff_store}店】的{$staff_name}！";
                        }
                    } else {
                        $add = '';
                    }
                    $ret = str_replace("{content}", $add, $content);
                    return $ret;
                } else {
                    $setreply = WechatReplys::find()->where(['brand_id' => $brand_id, 'response_type' => 2])->one();
                    if ($setreply) {
                        $content = $setreply->response_text . "\n\n";
                    }
                    $ret = str_replace("{content}", '', $content);
                    return $ret;
                }
                # code...
                break;
            //取消关注
            case 'unsubscribe':
                Yii::error('取消关注事件', 'wechat');
                break;
            //扫描事件
            case 'SCAN':
                Yii::error('扫码事件', 'wechat');
                $content = '';
                self::saveScan($message->FromUserName, $message->EventKey);
                $setreply = WechatReplys::find()->where(['brand_id' => $brand_id, 'response_type' => 3])->one();
                if ($setreply) {
                    $content = $setreply->response_text . "\n\n";
                }
                $staff = \backend\service\staff\StaffLogic::getStaffInfo($message->EventKey);
                $add = '';
                if ($staff) {
                    $staff_name = $staff['staff_name'];
                    $staff_store_record = Store::findOne($staff['store_id']);
                    if ($staff_store_record) {
                        $staff_store = $staff_store_record->store_name;
                        $add = "当前为您服务的是【{$staff_store}店】的{$staff_name}！";
                    }
                } else {
                    $add = '';
                }
                $ret = str_replace("{content}", $add, $content);
                return $ret;
                break;
            default:
                return '';
                break;
        }
        return '';
    }

    /**
     * 记录用户最后扫描记录，方便取到最后的评价数据
     * @param string $openid
     * @param string $key 二维码的值
     * @param boolean $subsribe 是否关注
     */
    public static function saveScan($openid, $key, $subsribe = false)
    {
        $log = new WechatScanLog();
        $log->openid = $openid;
        $log->scan_key = $key;
        $log->subscribe = $subsribe ? 1 : 0;
        $log->scan_date = date('Y-m-d H:i:s');
        $log->save();
        StaffLogic::scan($key, $subsribe);
    }

}
