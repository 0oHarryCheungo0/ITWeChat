<?php

namespace wechat\models\logic;

use backend\models\Brand;
use backend\models\NewsLevelTemp;
use common\models\UserCache;
use yii\helpers\ArrayHelper;

class BaseNews
{
    /**
     * 获取会员vip_type
     * @author fushl 2017-06-02
     * @param  int $rank_value    rank
     * @param  [type] $brand_id   [description]
     * @return [type]             [description]
     */
    public function getMemberRank1($rank_value, $brand_id)
    {
        $data = Brand::getMemberRank($brand_id);

        \Yii::info('会员等级' . $rank_value . '品牌为' . $brand_id);
        $result = ArrayHelper::map($data, 'rank', 'name');
        \Yii::info(json_encode($data));
        if (!array_key_exists($rank_value, $result)) {
            throw new \Exception('获取vip_type,后台会员等级设置错误');
        }
        return $result[$rank_value];
    }

    /**
     * 获取编号
     * @param  [type] $rank_value vip_type
     * @param  [type] $brand_id   [description]
     * @return [type]             [description]
     */
    public function getMemberRank($rank_value, $brand_id)
    {
        $data = Brand::getMemberRank($brand_id);

        \Yii::info('会员等级' . $rank_value . '品牌为' . $brand_id);
        $result = ArrayHelper::map($data, 'name', 'rank');
        \Yii::info(json_encode($data));
        if (!array_key_exists($rank_value, $result)) {
            throw new \Exception('获取编号,后台会员等级设置错误');
        }
        return $result[$rank_value];
    }

    public function checkVipChange($vip_type, $brand_id, $uid, $type)
    {
        $rank = $this->getMemberRank($vip_type, $brand_id);
        if ($this->checkUserCache($uid, $rank, $type)) {
            return true;
        }
    }

    /**
     * [checkUserCache description]
     * @param  [type] $uid  [description]
     * @param  [type] $rank [description]
     * @param  [type] $type 1资讯 2优惠
     * @return [type]       [description]
     */
    public function checkUserCache($uid, $rank, $type)
    {
        $result = UserCache::find()->where(['uid' => $uid, 'type' => $type])->one();
        if (!empty($result)) {
            //如果当前会员等级变动
            if ($result['rank'] != $rank) {
                $result->rank = $rank;
                $result->save();
                return true;
            } else {
                return false;
            }
        } else {
            $model              = new UserCache();
            $model->uid         = $uid;
            $model->rank        = $rank;
            $model->type        = $type;
            $model->create_time = time();
            $model->update_time = time();
            $model->save();
            //新增会员缓存
            return true;
        }
    }

    /**
     * 获取模版
     * @author fushl 2017-06-02
     * @param  [type]  $type          [description]
     * @param  [type]  $brand_id      [description]
     * @param  [type]  $member_rank   [description]
     * @param  integer $type_children [description]
     * @return [type]                 [description]
     */
    public function getTemplate($type, $brand_id, $member_rank = 1, $type_children = null)
    {
        if ($type_children != null) {
            $type_children = intval($type_children);
        }
        \Yii::info('member_rank为' . $member_rank);
        $template = NewsLevelTemp::find()->where(['type' => intval($type), 'brand_id' => intval($brand_id), 'member_rank' => intval($member_rank), 'type_children' => $type_children])->one();
        if (!empty($template)) {
            return $template;
        } else {
            throw new \Exception('后台模版未设置' . $member_rank . '类型' . $type);
        }
    }

    public static function getEnd($news_id, $type = 1)
    {
        if ($type == 1) {
            $news = NewsLevelTemp::findOne($news_id);
            if (!empty($news)) {
                return $news->end_time;
            }
            return false;
        } else {

        }

    }

    /**
     * 获取子类型
     * @param  [type] $news_id [description]
     * @return [type]          [description]
     */
    public static function getChildren($news_id)
    {
        $news = NewsLevelTemp::findOne($news_id);
        if (!empty($news)) {
            return $news->type_children;
        }
        return false;
    }
}
