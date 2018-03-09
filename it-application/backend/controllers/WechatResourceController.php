<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/26
 * Time: 下午4:44
 */

namespace backend\controllers;

use yii;
use common\models\WechatResource;

class WechatResourceController extends AdminBaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetResource()
    {
        $this->layout = 'layer';
        $list = WechatResource::find();
        $list->where(['brand_id' => $this->brand_id, 'status' => 1]);
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return $this->render('getresource', ['all' => $all]);
    }

    public function actionResource()
    {
        $list = WechatResource::find();
        //查询字段
        $list->where(['brand_id' => $this->brand_id]);
        $list->andWhere(['<>', 'status', 2]);

        $total = $list->count();
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        $this->response($data);
    }

    public function actionAdd()
    {
        $this->layout = 'layer';
        return $this->render('add');
    }

    public function actionEdit()
    {
        $this->layout = 'layer';
        $source_id = Yii::$app->request->get('source_id');
        $source = WechatResource::find()->where(['id' => $source_id, 'brand_id' => $this->brand_id])->one();
        return $this->render('edit', ['data' => $source]);
    }

    public function actionDelete()
    {
        $source_id = Yii::$app->request->post('source_id');
        $source = WechatResource::findOne($source_id);
        $source->status = 2;
        $source->save();
        return $this->response();
    }

    public function actionSave()
    {
        $source_id = Yii::$app->request->post('source_id', false);
        $json_data = Yii::$app->request->post('json', false);
        if (!$json_data) {
            return $this->response('', 500, '传入值为空');
        }
        $datas = json_decode($json_data, true);
        //检查关键词重复
        if (!$source_id) {
            $reply = WechatResource::find()->where(['title' => $datas['title'], 'brand_id' => $this->brand_id])->one();
            if ($reply) {
                return $this->response('', 101, '标题已存在');
            }
            $reply = new WechatResource();
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
            $reply->brand_id = $this->brand_id;
        } else {
            $reply = WechatResource::find()->where(['id' => $source_id, 'brand_id' => $this->brand_id])->one();
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->setAttributes($datas);
        if ($reply->save()) {
            return $this->response($reply);
        } else {
            return $this->response($reply->getErrors(), 102, '保存失败');
        }


    }
}