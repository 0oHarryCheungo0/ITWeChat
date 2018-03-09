<?php

namespace wechat\models\logic;

use common\models\NewsPoint;
use yii\helpers\Url;

class NewsPointLogic extends BaseNews
{
    //签到赢取积分
    const SIGN = 1;
    //完善资料
    const PRE = 2;
    /**
     *  type = 1签到赢取积分
     * @author
     * @param  [type] $uid      [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public function sign($uid, $brand_id)
    {
        $point = NewsPoint::find()->where(['uid' => $uid, 'type' => self::SIGN])->one();
        //TYPE =5为签到模版
        // $template = $this->getTemplate($type = 5, $brand_id, $member_rank = 1, $type_children =self::SIGN);
        if (empty($point)) {
            $model              = new NewsPoint();
            $model->uid         = $uid;
            $model->title       = '签到送积分';
            $model->hk_title    = '簽到送積分';
            $model->url         = Url::toRoute("index/sign", true);
            $model->content     = '每天签到即可获赠5积分，每连续签到7天，第7天即可获赠50积分哦！<br>快去“会员中心”首页，点击“签到打卡”赚积分吧！';
            $model->hk_content  = '每天簽到即可獲贈5積分，每連續簽到7天，第7天即可獲贈50積分哦！<br>快去“會員中心”首頁，點擊“簽到打卡”賺積分吧！';
            $model->type        = self::SIGN;
            $model->create_time = time();
            $model->end_time    = 0;
            $model->is_look     = 0;
            $model->save();
        }
    }

    /**
     * 完善资料赢取积分
     * @author
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public function prefected($uid, $brand_id)
    {

        $point = NewsPoint::find()->where(['uid' => $uid, 'type' => self::PRE])->one();
        // $template = $this->getTemplate($type = 5, $brand_id, $member_rank = 1, $type_children =self::PRE);
        $template          = [];
        $template['title'] = '完善资料送积分';
        if (empty($point)) {
            $model              = new NewsPoint();
            $model->uid         = $uid;
            $model->title       = $template['title'];
            $model->hk_title    = '完善資料送積分';
            $model->url         = Url::toRoute("index/per", true);
            $model->type        = intval(self::PRE);
            $model->create_time = intval(time());
            $model->end_time    = 0;
            $model->is_look     = intval(0);
            $ret                = $model->save();
            if ($ret) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function expirePoint($uid, $brand_id, $bonus)
    {
        $model              = new NewsPoint();
        $model->uid         = $uid;
        $model->title       = '您三个月内将有' . $bonus . '积分到期';
        $model->hk_title    = '您三個月內將有' . $bonus . '積分到期';
        $model->url         = Url::toRoute("index/per", true);
        $model->type        = 4;
        $model->create_time = intval(time());
        $model->end_time    = 0;
        $model->is_look     = intval(0);
        $ret                = $model->save();
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

}
