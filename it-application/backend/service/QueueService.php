<?php

namespace backend\service;

use backend\models\Brand;
use backend\models\NewsLevelTemp;
use backend\models\NewsQueue;
use wechat\models\WechatVip;

class QueueService
{

    /**
     * 存入队列表
     * @param  [type]  $news_id  资讯或优惠id
     * @param  integer $vip_type 会员类型 0代表所有会员
     * @param  [type]  $brand_id 品牌id
     * @param  [type]  $type     类型 0对应dynamic 1对应newsleveltemp
     * @return [type]            $id|false
     */
    public static function saveQueue($news_id, $vip_type = 0, $brand_id, $type)
    {
        $model           = new NewsQueue();
        $model->news_id  = $news_id;
        $model->vip_type = $vip_type;
        $model->brand_id = $brand_id;
        $model->type     = $type;
        $model->is_send  = 0;
        $ret             = $model->save();
        if ($ret) {
            $id = $model->getOldPrimaryKey();
            return $id;
        } else {
            return false;
        }

    }

    /**
     * 获取数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getData($id)
    {

        $result = NewsQueue::find()->where(['id' => $id])->one();
        if (!empty($result)) {
            return $result->toArray();
        } else {
            return false;
        }

    }

    /**
     * 更新队列表状态
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function update($id)
    {
        $model          = NewsQueue::findOne($id);
        $model->is_send = 1;
        $result         = $model->save();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取资讯id
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getNewsId($id)
    {
        $model = NewsQueue::findOne($id);
        if (!empty($model)) {
            $news_id = $model->news_id;
            return $news_id;
        } else {
            return false;
        }
    }

    /**
     * 获取会员类型
     * @param  [type] $news_id [description]
     * @return [type]          [description]
     */
    public static function getVipNo($news_id)
    {
        $news = NewsLevelTemp::findOne($news_id);
        if (!empty($news)) {
            $member_rank = $news->member_rank;
            if (!empty($member_rank)) {
                $user = self::getUser($member_rank);
                return $user;
            }
        }
        return false;
    }

    public static function getUser($member_rank = 1)
    {
        $brand    = Brand::getMemberRank();
        $vip_type = '';
        foreach ($brand as $k => $v) {
            if ($v['rank'] == $member_rank) {
                $vip_type = $v['name'];
            }
        }
        if (!empty($vip_type)) {
            $vip = WechatVip::find()->select('vip_no')->where(['vip_type' => $vip_type])->asArray()->all();
            if (!empty($vip)) {
                return $vip;
            } else {
                return false;
            }
        }
        return false;
    }

    public static function getNews($id)
    {
        $model = NewsQueue::findOne($id);
        if (!empty($model)) {
            return $model;
        } else {
            return false;
        }
    }

    public static function getExpireVipNo($news_id)
    {
        $news = NewsLevelTemp::findOne($news_id);
        if (!empty($news)) {
            $member_rank = $news->member_rank;
            $type        = $news->type_children;
            if (!empty($member_rank)) {
                $user = self::getExpireUser($member_rank, $type);
                return $user;
            }
        }
        return false;

    }

    public static function getExpireUser($member_rank = 1, $type)
    {
        $brand    = Brand::getMemberRank();
        $vip_type = '';
        foreach ($brand as $k => $v) {
            if ($v['rank'] == $member_rank) {
                $vip_type = $v['name'];
            }
        }
        if (!empty($vip_type)) {
            $vip = WechatVip::find()->where(['vip_type' => $vip_type])->asArray()->all();
            if (!empty($vip)) {
                foreach ($vip as $k => $v) {
                    $exp_date = $v['exp_date'];
                    $current = time();
                    //30天后的日期
                    if ($type == 1) {
                        $exp = date("Y-m-d", strtotime("+30 day"));
                    } else {
                        $exp = date("Y-m-d", strtotime("+90 day"));
                    }
                    //过期时间大于预计时间unset掉
                    if (strtotime($exp_date) > strtotime($exp)) {
                        unset($vip[$k]);
                    }
                    //已经过期 unset掉
                    if (strtotime($exp_date)<time()){
                        unset($vip[$k]);
                    }

                }
                return $vip;
            }
        }
        return false;
    }

    public static function getBirthVipNo($news_id){
         $news = NewsLevelTemp::findOne($news_id);
        if (!empty($news)) {
            $member_rank = $news->member_rank;
            $type        = $news->type_children;
            if (!empty($member_rank)) {
                $user = self::getBirthUser($member_rank, $type);
                return $user;
            }
        }
        return false;
       

    }

    public static function getBirthUser($member_rank,$type){
        $brand    = Brand::getMemberRank();
        $vip_type = '';
        foreach ($brand as $k => $v) {
            if ($v['rank'] == $member_rank) {
                $vip_type = $v['name'];
            }
        }

        if (!empty($vip_type)) {
            $vip = WechatVip::find()->where(['vip_type' => $vip_type])->asArray()->all();
            if (!empty($vip)) {
                return $vip;
            }
        }
        return false;
    }
}
