<?php

namespace wechat\controllers;

use backend\models\Staff;
use common\models\MemberRelation;
use yii;


class RelationController extends yii\web\Controller
{
    /**
     * 会员关系
     * @author
     * @return [type] [description]
     */
    public function actionBind()
    {
        $member_id = Yii::$app->session->get('wechat_id');
        $staff_id = Yii::$app->request->get('staff_id');
        $store_id = Staff::getStoreIdById($staff_id);
        $result = MemberRelation::find()->where(['store_id' => $store_id, 'member_id' => $member_id])->one();
        if (empty($result)) {
            $model = new MemberRelation();
            $model->store_id = $store_id;
            $model->staff_id = $staff_id;
            $model->create_time = time();
            $model->member_id = $member_id;
            $model->save();
        }
    }
}