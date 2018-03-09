<?php

namespace backend\controllers;

use \backend\service\fans\FansList;
use \backend\models\Staff;
use common\models\MemberRelation;
use Yii;

class FansController extends AdminBaseController
{

    public function actionFanslist()
    {
        $get_staff_name = Yii::$app->request->get('staff_name');
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
           	$type = $request->get('type');
            $data   = FansList::getList($limit, $offset,$type);
            return $this->response($data);
        }
        
        return $this->render('list',['get_staff_name'=>$get_staff_name]);
    }

    /**
     * 转移粉丝
     * @return [type] [description]
     */
    public function actionTran(){
    	$id = Yii::$app->request->get('id');
    	$brand_id  =Yii::$app->session->get('brand_id');
    	$result = MemberRelation::find()->where(['brand_id'=>$brand_id,'id'=>$id])->one();
    	$store_id =  $result['store_id'];
    	$staff = Staff::find()->where(['store_id'=>$store_id,'brand_id'=>$brand_id])->all();
    	return $this->renderPartial('trans',['staff'=>$staff,'id'=>$id]);
    }

    public function actionUpdate(){
    	$id = Yii::$app->request->post('id');
    	$staff_id = Yii::$app->request->post('staff_id');
    	$model  =MemberRelation::findOne($id);
        $staff_tid =  $model->staff_id;
    	$model->staff_id = $staff_id;
    	$model->save();
        \Yii::$app->db->createCommand()->update('staff_counts', ['vip_count' => new \yii\db\Expression('vip_count+1')], 'staff_id='.$staff_id)->execute();
        Yii::$app->db->createCommand()->update('staff_counts', ['vip_count' => new \yii\db\Expression('vip_count-1')], 'staff_id='.$staff_tid)->execute();
    	return $this->response('',200);
    }
}
