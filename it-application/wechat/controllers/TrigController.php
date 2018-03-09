<?php

namespace wechat\controllers;

use wechat\models\logic\DiscountLogic;
use wechat\models\logic\News;
use wechat\models\logic\NewsPointLogic;
use wechat\models\WechatVip;
use yii;

class TrigController extends \yii\web\Controller
{

    /**
     * 信息初始化
     * @return [type] [description]
     */
    public function check($uid, $brand_id)
    {
        $news        = new News();
        $logic       = new DisCountLogic();
        $result      = WechatVip::find()->where(['wechat_user_id' => $uid, 'brand' => $brand_id])->asArray()->one();
        $vip_type    = $result['vip_type'];
        $expire_date = $result['exp_date'];
        $birthday    = $result['birthday'];
        $rank        = $news->getMemberRank($vip_type, $brand_id);
        $change      = $news->checkVipChange($vip_type, $brand_id, $uid);
        if ($change) {
            $news->getUserRank($uid, $brand_id, $rank);
            $logic->memberDiscount($uid, $brand_id, $rank);
        }
        $news->memberExpire($uid, $brand_id, $vip_type, $expire_date);
        $point = new NewsPointLogic();
        //签到赢取积分
        $point->sign($uid, $brand_id);
        $point->prefected($uid);
        $logic->checkBirth($uid, $brand_id, $vip_type, $birthday);
    }

    public function actionReset()
    {
        $brand_id = Yii::$app->request->get('brand_id');
        $ret      = WechatVip::find()->where(['brand' => $brand_id])->all();
        foreach ($ret as $k => $v) {
            $this->check($v->wechat_user_id, $brand_id);
        }
    }

    public function actionDistribute()
    {
        //type  1会员等级资讯  2会员到期资讯  3等级专属优惠  4生日月优惠  5积分资讯
        $type     = Yii::$app->request->get('type'); 
        $type_c   = Yii::$app->request->get('type_children', 0);
        $vip_type = Yii::$app->request->get('vip_type');
        $brand_id = Yii::$app->request->get('brand_id');
        switch ($type) {
            case 1:
                // 会员等级资讯
                $news = new News();
                $all  = WechatVip::find()->where(['vip_type' => $vip_type])->all();
                foreach ($all as $k => $v) {
                    $rank = $news->getMemberRank($vip_type, $brand_id);
                    //资讯会员等级变动type=1
                    $change = $news->checkVipChange($vip_type, $brand_id, $v->wechat_user_id, 1);
                    if ($change) {
                        //发布会员等级变动资讯
                        $news->getUserRank($v->wechat_user_id, $brand_id, $rank);
                    }
                }
                break;
            case 2:
                //会员到期资讯
                $news = new News();
                $all  = WechatVip::find()->where(['vip_type' => $vip_type])->all();
                break;
            case 3:
                # 会员等级优惠
                break;
            case 4:
                # 会员生日月优惠
                break;
            case 5:
                # 会员积分优惠
                break;
            default:
                # code...
                break;
        }
    }
}
