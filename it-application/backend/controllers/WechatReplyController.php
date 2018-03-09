<?php

namespace backend\controllers;

use common\models\WechatReplys;
use common\models\WechatResource;
use yii\helpers\FileHelper;
use yii;

class WechatReplyController extends AdminBaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionReplys()
    {
        $list = WechatReplys::find();
        //查询字段
        $list->where(['brand_id' => $this->brand_id]);
        $list->andWhere(['<>', 'status', 2]);
        $list->andWhere(['not in', 'response_type', [2, 3, 4]]);
        $total = $list->count();
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        $this->response($data);
    }


    public function actionMsgReply()
    {
        $this->layout = 'layer';
        $content = WechatReplys::find()->where(['response_type' => 4, 'brand_id' => $this->brand_id])->one();
        if (!$content) {
            $text = "";
        } else {
            $text = $content->response_text;
        }
        return $this->render('msg', ['text' => $text]);

    }


    public function actionSubReply()
    {
        $this->layout = 'layer';
        $content = WechatReplys::find()->where(['response_type' => 2, 'brand_id' => $this->brand_id])->one();
        if (!$content) {
            $text = "";
        } else {
            $text = $content->response_text;
        }
        return $this->render('subreply', ['text' => $text]);
    }

    public function actionSaveSub()
    {
        $str = Yii::$app->request->post('content');
        $reply = WechatReplys::find()->where(['response_type' => 2, 'brand_id' => $this->brand_id])->one();
        if (!$reply) {
            $reply = new WechatReplys();
            $reply->keyword = 'sub';
            $reply->brand_id = $this->brand_id;
            $reply->response_type = 2;
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->response_text = $str;
        if ($reply->save()) {
            return $this->response();
        } else {
            return $this->response('', 400, $reply->getErrors());
        }
    }

    public function actionSaveScan()
    {
        $str = Yii::$app->request->post('content');
        $reply = WechatReplys::find()->where(['response_type' => 3, 'brand_id' => $this->brand_id])->one();
        if (!$reply) {
            $reply = new WechatReplys();
            $reply->keyword = 'scan';
            $reply->brand_id = $this->brand_id;
            $reply->response_type = 3;
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->response_text = $str;
        if ($reply->save()) {
            return $this->response();
        } else {
            return $this->response('', 400, $reply->getErrors());
        }

    }

    public function actionSaveMsg()
    {
        $str = Yii::$app->request->post('content');
        $reply = WechatReplys::find()->where(['response_type' => 4, 'brand_id' => $this->brand_id])->one();
        if (!$reply) {
            $reply = new WechatReplys();
            $reply->keyword = 'msg';
            $reply->brand_id = $this->brand_id;
            $reply->response_type = 4;
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->response_text = $str;
        if ($reply->save()) {
            return $this->response();
        } else {
            return $this->response('', 400, $reply->getErrors());
        }

    }

    public function actionScanReply()
    {
        $this->layout = 'layer';
        $content = WechatReplys::find()->where(['response_type' => 3, 'brand_id' => $this->brand_id])->one();
        if (!$content) {
            $text = "";
        } else {
            $text = $content->response_text;
        }
        return $this->render('scan', ['text' => $text]);
    }

    public function actionSaveImage()
    {
        $targetFolder = Yii::$app->basePath . '/web/uploads/' . date('Y/md');
        $file = new FileHelper();
        $file->createDirectory($targetFolder);
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $fileParts = pathinfo($_FILES['file']['name']);
            $extension = $fileParts['extension'];
            $random = time() . rand(1000, 9999);
            $randName = $random . "." . $extension;
            $targetFile = rtrim($targetFolder, '/') . '/' . $randName;
            $fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'JPG');
            $uploadfile_path = 'uploads/' . date('Y/md') . '/' . $randName;
            $callback['url'] = $uploadfile_path;
            $callback['filename'] = $fileParts['filename'];
            $callback['randName'] = $random;
            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
                $config = \common\api\BrandApi::getBrandById($this->brand_id);
                $wechat = Yii::$app->wechat->app($config);
                $material = $wechat->material;
                try {
                    $result = $material->uploadImage($uploadfile_path);
                } catch (\Exception $e) {
                    return $this->response('', 500, $e->getMessage());
                }

                if ($result) {
                    $media = json_decode($result, true);
                    $media['file'] = Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . "/" . $uploadfile_path;
                    return $this->response($media);
                } else {
                    return $this->response('', 201, '上传素材失败');
                }
            } else {
                return $this->response('', 500, '不能上传后缀为' . $fileParts['extension'] . '文件');
            }
        } else {
            return $this->response('', 501, '没有上传文件');
        }
    }

    public function actionAdd()
    {
        $this->layout = 'layer';
        return $this->render('add');
    }

    public function actionEdit()
    {
        $this->layout = 'layer';
        $reply_id = Yii::$app->request->get('reply_id');
        $reply = WechatReplys::find()->where(['id' => $reply_id, 'brand_id' => $this->brand_id])->one();
        if ($reply->response_type == 1) {
            $source = WechatResource::find()
                ->where(['in', 'id', json_decode($reply->response_source_ids, true)])
                ->andWhere(['status' => 1])
                ->all();
        } else {
            $source = [];
        }
        return $this->render('edit', ['data' => $reply, 'source' => $source]);
    }

    public function actionDelete()
    {
        $reply_id = Yii::$app->request->post('reply_id');
        $reply = WechatReplys::findOne($reply_id);
        $reply->status = 2;
        $reply->save();
        return $this->response();
    }

    public function actionSave()
    {
        $reply_id = Yii::$app->request->post('reply_id', false);
        $json_data = Yii::$app->request->post('json', false);
        if (!$json_data) {
            return $this->response('', 500, '传入值为空');
        }
        $datas = json_decode($json_data, true);
        //检查关键词重复
        $disable = ['1'];
        if (in_array($datas['keyword'], $disable)) {
            return $this->response('', 500, $datas['keyword'] . '不能作为关键词');
        }
        if (!$reply_id) {
            $reply = WechatReplys::find()->where(['keyword' => $datas['keyword'], 'brand_id' => $this->brand_id, 'status' => ['<>', 3]])->one();
            if ($reply) {
                return $this->response('', 101, '关键词已存在');
            }
            $reply = new WechatReplys();
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
            $reply->brand_id = $this->brand_id;
            $reply->match_times = 0;
        } else {
            $reply = WechatReplys::find()->where(['id' => $reply_id, 'brand_id' => $this->brand_id])->one();
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->setAttributes($datas);
        if ($reply->save()) {
            return $this->response($reply);
        } else {
            return $this->response('', 102, '保存失败');
        }
    }

}
