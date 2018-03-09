<?php
namespace backend\controllers;

use GuzzleHttp\json_encode;
use yii\web\UploadedFile;
use backend\components\UploadForm;
use backend\models\Replymenu;

class ReplymenuController extends AdminBaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $brand_id = self::brand_id();
        $model = new UploadForm();
        $menu = new Replymenu();
        $data = $_POST;
        $data['brand_id'] = $brand_id;
        if (\Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'pic');
            if ($model->imageFile == null) {
                $menu->setAttributes($data);
                if ($menu->save()) {
                    $this->redirect(["replymenu/keywordlist"]);
                } else {
                    throw new \Exception("错误", 0);
                    exit;
                }
            }
            if ($model->upload()) {
                $data['image'] = $model->url;
                $menu->setAttributes($data);
                if ($menu->save()) {
                    $this->redirect(["replymenu/keywordlist"]);
                } else {
                    throw new \Exception("错误", 0);
                }
            }
        }
        return $this->render('index');
    }

    //验证配置的关键字是否唯一
    public function actionValidatekeword()
    {
        $keyword = $_POST['keyword'];
        $brand_id = self::brand_id();
        $model = Replymenu::findAll(["brand_id" => $brand_id, "keyword" => $keyword]);
        if ($model) {
            echo json_encode(['code' => 1]);
        } else {
            echo json_encode(['code' => 0]);
        }


    }

    public function actionKeywordedit()
    {
        if (\yii::$app->request->isPost) {
            $upload = new UploadForm();
            $model = Replymenu::findOne(['id' => $_POST['id']]);
            $model->keyword = $_POST['keyword'];
            $model->title = $_POST['title'];
            $model->description = $_POST['description'];
            $model->sort = $_POST['sort'];
            $model->url = $_POST['url'];
            $upload->imageFile = UploadedFile::getInstance($upload, 'pic');
            if ($upload->imageFile == null) {
                if ($model->save()) {
                    $this->redirect(["replymenu/keywordlist"]);
                } else {
                    throw new \Exception("错误", 0);
                    exit;
                }
            }
            if ($upload->upload()) {
                @unlink($model->image);
                $model->image = $upload->url;
                if ($model->save()) {
                    $this->redirect(["replymenu/keywordlist"]);
                } else {
                    throw new \Exception("错误", 0);
                    exit;
                }
            }

        }
        $id = \yii::$app->request->get("id");
        $data = Replymenu::findOne(['id' => $id]);
        return $this->render("edit", ["data" => $data]);
    }


    public function actionKeyworddelete()
    {
        if (\yii::$app->request->get("id")) {
            $id = $_GET['id'];
            $model = replymenu::findOne(["id" => $id]);
            @unlink($model->image);
            if ($model->delete()) {
                $this->redirect(["replymenu/keywordlist"]);
            } else {
                throw new \Exception("错误", 0);
                exit;
            }
        }
    }

    public function actionKeywordlist()
    {
        if (\Yii::$app->request->isPost) {
            $brand_id = self::brand_id();
            $data = Replymenu::find()->where(['brand_id' => $brand_id])->asArray()->orderBy('id desc')->all();

            echo json_encode($data);
            exit;
        }
        return $this->render("list");
    }

    public function actionTest()
    {

    }


}