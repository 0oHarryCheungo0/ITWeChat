<?php

namespace wechat\controllers;

use wechat\models\logic\News;
use Yii;

class NewsController extends VipBase
{
    public $brand_id;

    /**
     * 资讯列表页
     * @author
     * @return [type] [description]
     */
    public function actionGetlist()
    {
        $this->view->title = 'NEWS';
        $uid               = $this->getUid();
        $brand_id          = $this->brand_id;
        $model             = new News();
        $member_news       = $model->getMemberList($uid);
        $point_news        = $model->getPointList($uid);
        $is_point          = $this->getPoint($uid);
        $is_member         = $this->getMemberNews($uid);
        Yii::warning('未读积分资讯' . $is_point);
        Yii::warning('未读会员资讯' . $is_member);
        return $this->render('list1', ['is_point' => $is_point, 'is_member' => $is_member, 'member_news' => $member_news, 'point_news' => $point_news]);

    }

    /**
     * 资讯详情页
     * @author
     * @return [type] [description]
     */
    public function actionDetail()
    {
        $this->view->title = 'NEWS';
        $id                = Yii::$app->request->get('id', 3);
        $type              = Yii::$app->request->get('type', 3);
        $children_type     = Yii::$app->request->get('children_type', 0);

        $brand_id = $this->brand_id;
        $model    = new News();
        $result   = $model->getDetail($id, $type);
        if ($type == 3 && $children_type == 2) {
            return $this->redirect('/index/profile');
        }
        if ($type == 3 && $children_type == 1) {
            // return $this->redirect('/index/index');
        }
        if ($type == 3 && $children_type == 4) {
            // return $this->goBack();
        }
        return $this->render('detail', ['data' => $result, 'children_type' => $children_type, 'type' => $type]);
    }

    public function actionCrontab()
    {
        $users = \wechat\models\WechatVip::find()->all();
        foreach ($users as $k => $v) {
            $uid      = $v->wechat_user_id;
            $brand_id = $v->brand;
            $vip_no   = $v->vip_no;
            $result   = \wechat\models\service\WechatVipService::getBonus($vip_no);
            \wechat\models\logic\NewsPointLogic::expirePoint($uid, $brand_id, $result->exp_bonus);
        }
    }

    /**
     * 获取会员资讯未读条数
     * @author
     * @param  [type] $uid 会员id
     * @return [type]      未读条数
     */
    public function getMemberNews($uid)
    {
        return News::getMemberNews($uid);
    }

    /**
     * 获取动态资讯未读条数
     * @author
     * @param  [type] $uid 会员id
     * @return [type]      未读条数
     */
    public function getDynamic($uid)
    {
        return News::getDynamic($uid);
    }

    /**
     * 获取积分资讯未读条数
     * @author
     * @param  [type] $uid 会员id
     * @return [type]      未读条数
     */
    public function getPoint($uid)
    {
        return News::getPoint($uid);
    }

    /**
     * 获取所有资讯未读条数
     * @author
     * @param  [type] $uid 会员id
     * @return [type]      未读条数
     */
    public function getTotal($uid)
    {
        return News::getTotal($uid);
    }

    public function actionTest()
    {

    }

    private function getUid()
    {
        $uid = Yii::$app->session->get('wechat_id');
        $vip = \wechat\models\WechatVip::find()->where(['wechat_user_id' => $uid])->one();
        if (!empty($vip)) {
            return $vip->vip_no;
        } else {
            return 0;
        }
    }
}
