<?php
/**
 * User: wangzq
 * Date: 2017/6/9
 * Time: 下午6:05
 */

namespace backend\controllers;


use backend\models\SystemConfig;
use common\middleware\Bonus;
use common\middleware\Record;
use common\models\FailureBonus;
use common\models\WechatSlider;
use yii;

class SystemRulesController extends AdminBaseController
{
    /**
     * 品牌设置总览
     * @return string
     */
    public function actionIndex()
    {
        $config['vip_config'] = SystemConfig::getVipConfig($this->brand_id);
        $config['check_in_rule'] = SystemConfig::getCheckInConfig($this->brand_id);
        return $this->render('index', $config);
    }

    public function actionSaveVipConfig()
    {
        $config = Yii::$app->request->post('config');
        try {
            SystemConfig::saveVipConfig($this->brand_id, json_decode($config, true));//json_decode true return array
        } catch (\Exception $e) {
            $this->response('', 500, $e->getMessage());
        }
        $this->response();
    }

    public function actionSaveCheckInConfig()
    {
        $config = Yii::$app->request->post('config');
        $base = Yii::$app->request->post('base', 1);
        try {
            SystemConfig::saveCheckInConfig($this->brand_id, $base, json_decode($config, true));
        } catch (\Exception $e) {
            $this->response('', 500, $e->getMessage());
        }
        $this->response();
    }

    public function actionGetBonusConfig()
    {
        $type = Yii::$app->request->get('type');
        $config = SystemConfig::getBonusConfig($this->brand_id, $type);
        $response = ['vbid' => $config->vb_prefix, 'vbgroup' => $config->vbgroup, 'exp' => $config->exp];
        $this->response($response);
    }

    public function actionSaveBonusConfig()
    {
        $type = Yii::$app->request->post('type');
        $vb_prefix = Yii::$app->request->post('vbid');
        $vbgroup = Yii::$app->request->post('vbgroup');
        $exp = Yii::$app->request->post('exp');
        if ($exp < 0) {
            $this->response('', 502, '过期时间不能小于0');
        }
        try {
            SystemConfig::saveBonusConfig($this->brand_id, $type, $vb_prefix, $vbgroup, $exp);
        } catch (\Exception $e) {
            $this->response('', 501, $e->getMessage());
        }
        $this->response();
    }

    /**
     * VIP过期时间，VRID等设置
     * @return mixed
     */
    public function actionVip()
    {
        return $this->render('vip');
    }

    /**
     * 签到积分，签到规则，签到积分归入规则
     * @return string
     */
    public function actionSign()
    {
        return $this->render('sign');
    }

    /**
     * 完成个人资料积分归入规则
     * @return string
     */
    public function actionProfile()
    {
        return $this->render('finish');
    }

    public function actionSlider()
    {
        return $this->render('slider');
    }

    public function actionSliderData()
    {
        $list = WechatSlider::find();
        //查询字段
        $list->where(['brand_id' => $this->brand_id]);
        $list->andWhere(['<>', 'status', 2]);
        $total = $list->count();
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('indexs desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        $this->response($data);
    }

    public function actionDelete()
    {
        $slider_id = Yii::$app->request->post('slider_id');
        $slider = WechatSlider::findOne($slider_id);
        $slider->status = 2;
        $slider->save();
        return $this->response();
    }

    public function actionSliderAdd()
    {
        $this->layout = 'layer';
        return $this->render('slideradd');
    }

    public function actionSliderEdit()
    {
        $this->layout = 'layer';
        $slider_id = Yii::$app->request->get('slider_id');
        $data = WechatSlider::findOne($slider_id);
        return $this->render('slideredit', ['data' => $data]);
    }

    public function actionSave()
    {
        $slider_id = Yii::$app->request->post('slider_id', false);
        $json_data = Yii::$app->request->post('json', false);
        if (!$json_data) {
            return $this->response('', 500, '传入值为空');
        }
        $datas = json_decode($json_data, true);
        //检查关键词重复
        if (!$slider_id) {
            $reply = new WechatSlider();
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
            $reply->brand_id = $this->brand_id;
        } else {
            $reply = WechatSlider::find()->where(['id' => $slider_id, 'brand_id' => $this->brand_id])->one();
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->setAttributes($datas);
        if ($reply->save()) {
            return $this->response($reply);
        } else {
            return $this->response('', 102, '保存失败');
        }


    }


    public function actionFailureBonus()
    {
        return $this->render('failure-bonus');
    }

    public function actionFailures()
    {
        $list = FailureBonus::find();
        $total = $list->count();
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        $this->response($data);
    }

    public function actionResend()
    {
        $id = Yii::$app->request->post('id');
        /** @var FailureBonus $failure */
        $failure = FailureBonus::find()->where(['id' => $id])->one();
        if (!$failure || $failure->status != FailureBonus::WAIT_PROCESS) {
            return $this->response('', 500, '记录状态错误');
        }
        $failure->process_time = date('Y-m-d H:i:s');

        if (Record::addBonus(json_decode($failure->params, true))) {
            $failure->status = FailureBonus::PROCESS_SUCCESS;
            $failure->save();
            return $this->response();
        } else {
            $failure->status = FailureBonus::PROCESS_FAIL;
            $failure->save();
            return $this->response('', 500, '请求失败,此条记录作废，将生成新的错误记录');
        }
    }


}