<?php

namespace common\api;

use wechat\models\service\WechatVipService;
use wechat\models\WechatVip;
use common\models\service\TmplMsg;
use Yii;

class WxPushApi
{
    /**
     * 会员消息模板推送
     * @param  [type] $uid  1升级会员  2续会会员 3会员卡到期
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private static function upgradeData($type, $vip_type, $vip_no, $expire_date)
    {
        $date     = date('m-d');
        $vip_data = ['CN_BIT', 'CN_SIT', 'CN_IPASS'];
        $vip_c    = substr($vip_type, 0, 6);
        Yii::info('vip_type父类型为'.$vip_c);
        if ($type != 1) {
        	Yii::info('检查是否属于三大类');
            if (!in_array($vip_c, $vip_data)) {
                \Yii::info('会员等级不属于三种(CN_BIT,CN_SIT)类型之一');
                return false;
            }else{
            	Yii::info('会员等级在范围内');
            }
        }
        $vip_b = substr($vip_type, -2);
        Yii::info('vip是否为T0--:'.$vip_b);
        $points = WechatVipService::getBonus($vip_no);


        $total_point  = $points['bonus'];
        $point        = $points['exp_bonus'];
        $expire_point = empty($point) ? 0 : $point;
        $data         = [];
        switch ($type) {
            case 1:
                $title = $date . '' . '亲爱的会员，恭喜您成功升级为会员卡';
                break;
            case 2:
                # code...
                $title       = $date . '' . '亲爱的会员，您已成功续会会员卡籍';
                $expire_date = $vip_b == 'T0' ? '永久有效' : $expire_date;

                break;
            case 3:
                # code...
                $title       = $date . '' . '亲爱的会员，您的会员卡即将到期，请留意续籍时间，防止过期哦！';
                $expire_date = $vip_b == 'T0' ? '永久有效' : $expire_date;
                break;

        }
        $data['first'] = ['value' => $title];
        //会员卡等级
        $data['keyword1'] = ['value' => $vip_type];
        //会员卡有效期
        $data['keyword2'] = ['value' => $expire_date];
        //积分余额
        $data['keyword3'] = ['value' => $point];
        //30过期积分
        $data['keyword4'] = ['value' => $expire_point];
        //更多
        $data['remark'] = ['value' => '更多会员权益，请进入\'会员中心\'查看'];
        return $data;
    }

    /**
     * 会员积分模版消息推送
     * @param  [type] $uid       [description]
     * @param  [type] $type      1积分消费提醒 2注册赠送积分 3绑定赠送积分 4签到积分到帐
     * @param  [type] $add_point [description]
     * @return [type]            [description]
     */
    private static function pointData($type, $vip_no,$add_point)
    {
        $data = [];

        $points = WechatVipService::getBonus($vip_no);

      
        $total_point  = $points['bonus'];
        $point        = $points['exp_bonus'];
        $expire_point = empty($point) ? 0 : $point;
        $active_type  = '活动积分';
        switch ($type) {
            case 1:
                # code...
                $title       = '亲爱的会员，您使用积分消费成功。';
                $active_type = '积分消费';
                break;
            case 2:
                $title = '亲爱的会员，会员注册赠送积分已到账';
                break;
            case 3:
                $title = '亲爱的会员，会员绑定赠送积分已到帐';
                break;
            case 4:
                $title = '亲爱的会员，您的答到积分已到帐';
        }
        $data['first'] = ['value' => $title];
        //时间
        $data['keyword1'] = ['value' => date('Y-m-d H:i:s')];
        //活动积分
        $data['keyword2'] = ['value' => $active_type];
        //积分变动
        $data['keyword3'] = ['value' => $add_point];
        //积分余额
        $data['keyword4'] = ['value' => $point];
        //30过期积分
        $data['keyword5'] = ['value' => $expire_point];
        //更多
        $data['remark'] = ['value' => '积分详情,请进入\'会员中心\'查看；'];
        return $data;
    }

    public static function upgrade($uid, $type)
    {
        $templateId  = 'ITzr7pC0IKEDWvaqL_jlRSYvx5YGDFJ1q_P54-3UOgY';
        $url         = 'http://www.baidu.com';
        $vip         = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        $openid      = $vip->wechat->openid;
        $vip_type    = $vip->vip_type;
        $vip_no      = $vip->vip_no;
        $expire_date = $vip->exp_date;
        $datas       = self::upgradeData($type, $vip_type, $vip_no, $expire_date);
        $ret = self::sendMessage($templateId, $openid, $url, $datas);
        return $ret;
    }

    /**
     * @param  [type] $type 1积分消费提醒 2注册赠送积分 3绑定赠送积分 4签到积分到帐
     * @param  [type] $uid  [description]
     * @return [type]       [description]
     */
    public static function point($uid, $type, $add_point)
    {
        $templateId = '';
        $url        = '';
        $vip        = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        $openid     = $vip->wechat->openid;
        $vip_no     = $vip->vip_no;
        Yii::info('积分变动为'.$add_point);
        $datas      = self::pointData($type, $vip_no,$add_point);
        self::sendMessage($templateId, $openid, $url, $datas);
    }

    public static function order(){
    	$templateId = '';
    	$url = '';
    }

    private static function sendMessage($templateId, $openid, $url, $datas)
    {
        \Yii::info('发送的数据为' . json_encode($datas, JSON_UNESCAPED_UNICODE) . ',' . '模版id' . $templateId . ';openid:' . $openid . ';url:' . $url);
        return ['id'=>$templateId,'openid'=>$openid,'url'=>$url,'datas'=>$datas];exit;
        try {
            $ret = TmplMsg::sendMsg($templateId, $openid, $url, $datas);
            return $ret;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
