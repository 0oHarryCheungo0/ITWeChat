<?php

namespace backend\service\wechat;

use backend\service\staff\StaffLogic;
use common\models\WechatScanLog;
use EasyWeChat\Support\Collection;
use yii\helpers\Url;
use yii;

class TextHandler
{
    public static function send(Collection $message, $brand_id)
    {
        $content = '';
        //暂时先关掉1的回复
        if ($message->Content == 1 && ture == false) {
            //先查找最后的扫描记录
            $log = WechatScanLog::find()->where(['openid' => $message->FromUserName])->orderBy('id desc')->one();
            //判断是否有扫描记录，如果有扫描记录，判断是否是在一天内的扫描记录。
            if (!$log) {
                $url = Yii::$app->params['wechat_url'] . "/server/index?brand_id=".$brand_id .'&openid='.$message->FromUserName;
                $callback = urlencode($url);
                $url = 'http://app.ithk.com/qrsc?staff_code=&callback='.$callback;
                $content = "<a href='" . $url . "'>点击这里进行服务评价</a>";
            } else {
                $staff = StaffLogic::getStaffInfo($log->scan_key);
                if ($staff) {
                    //组装url
                    $url = Yii::$app->params['wechat_url'] . "/server/index?brand_id=".$brand_id .'&openid='.$message->FromUserName;
                    $callback = urlencode($url);
                    $staff_code = $staff['staff_code'];
                    $url = 'http://app.ithk.com/qrsc/?staff_code=' . $staff_code . '&callback='.$callback;
                    Yii::error('URL为'.$url);
                    $content = "<a href='" . $url . "'>点击这里对 【" . $staff['staff_name'] . " 】进行评价</a>";
                    Yii::error('CONTNET='.$content);
                } else {
                    $content = '请重新扫描店员二维码';
                }

            }
        } else {
            $content = AutoReply::getContent($message->Content, $brand_id);
        }
        return $content;
    }
}
