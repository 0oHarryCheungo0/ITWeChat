<?php

namespace backend\controllers;

use backend\service\member\MemberList;
use backend\models\Staff;
use Yii;

class MembersController extends AdminBaseController
{

    public function actionList()
    {
        if (Yii::$app->request->isAjax) {
            $model  = new MemberList();
            $brand_id =Yii::$app->session->get('brand_id');
            $limit  = Yii::$app->request->get('limit');
            $offset = Yii::$app->request->get('offset');
            $condition = json_decode(Yii::$app->request->get('text'),true);
            $data   = $model->getList($brand_id, $limit, $offset,$condition);
            return $this->response($data, 200);
        }
        return $this->render('list');
    }

    public function actionAddgroup()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {

        }
        return $this->render('add_group');
    }

    public function actionGetmember()
    {
        if (Yii::$app->request->isAjax) {
            $data = [
                0 => ['uid' => 1, 'name' => '小明', 'status' => 0],
                1 => ['uid' => 2, 'name' => '小红', 'status' => 0],
            ];
            return $this->response($data, 200);
        }
    }

    public function actionExport()
    {
        $data = Yii::$app->request->post('data');
        $data = json_decode($data, true);
        //$data = MemberList::export();
        //var_dump($data);exit;
    }

    public function actionTest(){
        $result =Staff::find()->joinWith('store')->all(); 
        var_dump($result);exit;
    }
}
