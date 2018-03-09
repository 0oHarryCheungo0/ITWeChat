<?php
namespace backend\controllers;

use backend\controllers\AdminController;
use backend\models\Template;

class TemplatenewsController extends AdminController
{
    public function actionConfig()
    {
        if (\yii::$app->request->isPost) {
            $model = new Template ();
            $data = $_POST;
            $data ['brand_id'] = self::brand_id();
            $model->setAttributes($data);
            if ($model->save()) {
                $this->redirect([
                    "templatenews/list"
                ]);
            }
        }
        return $this->render('config');
    }

    public function actionList()
    {
        if (\yii::$app->request->isPost) {
            $brand_id = self::brand_id();
            $data = Template::find()->where(["brand_id" => $brand_id])->asArray()->all();
            echo json_encode([
                'data' => $data
            ]);
            exit ();
        }
        return $this->render('list');
    }

    public function actionEdit()
    {
        if (\yii::$app->request->isGet) {
            $id = $_GET ['id'];
            $data = Template::findOne([
                'id' => $id
            ]);
            return $this->render('edit', [
                'data' => $data
            ]);
        }
        if (\yii::$app->request->isPost) {
            $id = $_POST ['id'];
            $model = Template::findOne([
                'id' => $id
            ]);
            $model->title = $_POST ['title'];
            $model->orderid = $_POST ['orderid'];
            $model->orderstatus = $_POST ['orderstatus'];
            $model->content = $_POST ['content'];
            if ($model->save()) {
                $this->redirect([
                    'list'
                ]);
            } else {
                die ("error");
            }
        }
    }

    public function actionDelete()
    {
        if (\yii::$app->request->isGet) {
            $id = $_GET ['id'];
            $model = Template::findOne($id);
            if ($model->delete()) {
                $this->redirect([
                    'list'
                ]);
            }
        }
    }


}