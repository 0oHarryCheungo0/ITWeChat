<?php

namespace common\api;

use backend\models\Brand;
use backend\models\Discount;
use backend\models\NewsLevelTemp;
use common\models\NewsMember;
use wechat\models\logic\News;
use wechat\models\logic\NewsPointLogic;
use wechat\models\WechatVip;
use backend\models\Dynamic;
use common\models\NewsPoint;
use Yii;

class NewsApi
{
    /**
     * 会员等级资讯
     * @param  int     $uid 会员id
     * @return boolean      true|false
     */
    public static function memberRank($uid, $brand_id)
    {
        $vip_info = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        if (!empty($vip_info)) {
            $vip_type    = $vip_info->vip_type;
            $vip_no      = $vip_info->vip_no;
            $member_rank = self::getMemberRank($brand_id, $vip_type);
            if ($member_rank != false) {
                $news_id = self::getRankId($member_rank, $brand_id);
                if ($news_id==false){
                    \Yii::info('不存在默认模版');
                    return false;
                }
                $result = NewsMember::find()->where(['type'=>1,'uid'=>$vip_no,'news_id'=>$news_id])->one();
                if ($result){
                    \Yii::error('存在此条会员等级资讯，不发送');
                    return false;
                }
                if (!empty($news_id)) {
                    $model = \wechat\models\logic\News::sendNews($brand_id, $vip_no, $news_id);
                    return $model;
                }
            }else{
                \Yii::info('不存在会员');
            }
        }
        return false;
    }

    public static function getMemberRank($brand_id, $vip_type)
    {
        $brand = Brand::getMemberRank($brand_id);
        if (!empty($brand)) {
            foreach ($brand as $k => $v) {
                if ($v['name'] == $vip_type) {
                    return $v['rank'];
                }
            }
        }
        return false;

    }

    public static function getRankId($member_rank, $brand_id)
    {
        $template = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'member_rank' => $member_rank, 'type' => 1, 'is_set' => 1])->one();
        if (!empty($template)) {
            return $news_id = $template->id;
        }
        return false;
    }

    /**
     * 发布会员等级将过期资讯，先检测是否一个月内过期，如果是，则发布。如果不是，再检测是否三个月
     * 内过期。
     * @param  [type] $uid      [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function expire($uid, $brand_id)
    {
        $vip_info = WechatVip::find()->select(['vip_type'])->where(['wechat_user_id' => $uid])->one();
        $vip_type = $vip_info->vip_type;
        $exp_date = $vip_info->exp_date;
        $model    = new News();
        $model->memberExpire($uid, $brand_id, $vip_type, $exp_date);
        return true;
    }

    /**
     * 积分签到资讯
     * @param  [type] $uid      [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function pointSign($uid, $brand_id)
    {
        $vip_info = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        $uid      = $vip_info->vip_no;
        $model    = new NewsPointLogic();
        $model->sign($uid, $brand_id);
    }

    /**
     * 完成资料获取积分
     * @param  [type] $uid      [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function pointPrefect($uid, $brand_id)
    {
        $vip_info = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        $uid      = $vip_info->vip_no;
        $model    = new NewsPointLogic();
        $model->prefected($uid, $brand_id);

    }

    /**
     * 发布未过期动态资讯
     * @param  [type] $uid      [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function sendPoint($uid,$brand_id){
        $current = time();
        $dynamic = Dynamic::find()->where(['brand_id'=>$brand_id,'type'=>1,'is_send'=>1])->andWhere(['>','end',$current])->all();
        if (!empty($dynamic)){
            Yii::error('存在未过期资讯');
            $vip = WechatVip::find()->where(['wechat_user_id'=>$uid])->one();
            if (!empty($vip)){
                $vip_no = $vip->vip_no;
                foreach ($dynamic as $k=>$v){
                    $news_id = $v->id;
                    $point = NewsPoint::find()->where(['type'=>3,'news_id'=>$news_id,'uid'=>$vip_no])->one();
                    if (empty($point)){
                        $model = new NewsPoint();
                        $model->uid = $vip_no;
                        $model->news_id = $news_id;
                        $model->type = 3;
                        $model->create_time = time();
                        $model->end_time = $v->end;
                        $model->is_look = 0;
                        $re = $model->save();
                        if ($re){
                            return true;
                        }
                    }
                }
            }
        }else{
            Yii::error('示存在过期积分资讯');
            return true;
        }
        return false;
    }

    public static function sendDiscount($uid,$brand_id){
        $current = time();
        $dynamic = Dynamic::find()->where(['brand_id'=>$brand_id,'type'=>3,'is_send'=>1])->andWhere(['>','end',$current])->all();

        if (!empty($dynamic)){
            Yii::error('存在未过期资讯');
            $vip = WechatVip::find()->where(['wechat_user_id'=>$uid])->one();
            if (!empty($vip)){
                $vip_no = $vip->vip_no;
                foreach ($dynamic as $k=>$v){
                    $news_id = $v->id;
                    $point = Discount::find()->where(['type'=>3,'news_id'=>$news_id,'uid'=>$vip_no])->one();
                    if (empty($point)){
                        $model = new Discount();
                        $model->uid = $vip_no;
                        $model->news_id = $news_id;
                        $model->type = 3;
                        $model->create_time = time();
                        $model->end_time = $v->end;
                        $model->is_look = 0;
                        $re = $model->save();
                        if ($re){
                            return true;
                        }
                    }
                }
            }
        }else{
            Yii::error('未存在限时优惠');
            return true;
        }
        return false;

    }

    public static function trigger($uid, $brand_id)
    {
        \Yii::error('调用触发');
        try {
            \common\api\DiscountApi::sendBirthDay($uid, $brand_id);
            \common\api\NewsApi::memberRank($uid, $brand_id);
            \common\api\DiscountApi::sendExclusive($uid, $brand_id);
            \common\api\NewsApi::pointPrefect($uid, $brand_id);
            \common\api\NewsApi::pointSign($uid, $brand_id);
            \common\api\NewsApi::sendDiscount($uid,$brand_id);
            \common\api\NewsApi::sendPoint($uid,$brand_id);
            return true;
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }

    }

}
