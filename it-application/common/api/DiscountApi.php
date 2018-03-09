<?php

namespace common\api;

use backend\models\Brand;
use backend\models\Discount;
use backend\models\NewsLevelTemp;
use wechat\models\logic\DiscountLogic;
use wechat\models\WechatVip;
use Yii;

class DiscountApi
{
    /**
     * 发布专属优惠
     * @return [type] [description]
     */
    public static function sendExclusive($uid, $brand_id)
    {
        $vip_info = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        if (!empty($vip_info)) {
            $vip_type    = $vip_info->vip_type;
            $vip_no = $vip_info->vip_no;
            $member_rank = self::getMemberRank($brand_id, $vip_type);
            \Yii::error('member_rank'.$member_rank);
            if ($member_rank != false) {
                $news_id = self::getRankId($member_rank, $brand_id);
                if (!empty($news_id)) {
                       $ret = Discount::find()->where(['uid'=>$vip_no,'news_id'=>$news_id,'type'=>1])->one();
                        if (!empty($ret)){
                            Yii::error('常规优惠已存在');
                            return false;
                        }
                    $model = DiscountLogic::sendRank($brand_id, $vip_no, $news_id);
                    return $model;
                }
            }
        }
        return false;
    }

    /**
     * 发布生日月优惠
     */
    public static function sendBirthDay($uid, $brand_id)
    {
        $vip_info = WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        $vip_type = $vip_info->vip_type;
        $birthday = $vip_info->birthday;
        $vip_no = $vip_info->vip_no;
        $type = date('m',strtotime($birthday));
        $member_rank = self::getMemberRank($brand_id, $vip_type);
           if ($member_rank != false) {
                $news_id = self::getBirthId($member_rank, $brand_id,intval($type));
                if (!empty($news_id)) {
                    $ret = Discount::find()->where(['uid'=>$vip_no,'news_id'=>$news_id,'type'=>2])->one();
                        if (!empty($ret)){
                            Yii::error('生日月优惠已存在');
                            return false;
                        }
                    $model =  DiscountLogic::sendBirth($brand_id, $vip_no, $news_id);
                    return $model;
                }
            }
        return false;
    }

    public static function getBirthId($member_rank,$brand_id,$type)
    {
        $template = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'member_rank' => $member_rank, 'type' => 4, 'is_set' => 1])->one();
        if (!empty($template)) {
           return  $news_id = $template->id;
        }
    }

    public static function getRankId($member_rank, $brand_id)
    {
        $template = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'member_rank' => $member_rank, 'type' => 3, 'is_set' => 1])->one();
        if (!empty($template)) {
            return $news_id = $template->id;
        }
        return false;
    }

    public static function getMemberRank($brand_id, $vip_type)
    {
        $brand = Brand::getMemberRank($brand_id);
        if (!empty($brand)) {
            
            foreach ($brand as $k => $v) {
                if ($v['name'] == $vip_type) {
                    Yii::error('品牌存在');
                    return $v['rank'];
                }
            }
        }
        return false;

    }

}
