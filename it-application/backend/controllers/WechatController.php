<?php
namespace backend\controllers;

use backend\service\wechat\MsgHandler;
use common\api\BrandApi;
use Yii;
use yii\web\Controller;

class WechatController extends Controller
{
    //品牌信息
    public $brand = null;

    public $wechat;

    public $brand_id = null;

    //关闭csrf验证
    public $enableCsrfValidation = false;

    public function init()
    {
        if (!Yii::$app->request->get('brand_id')) {
            exit('error params');
        } else {
            $this->brand_id = Yii::$app->request->get('brand_id');
        }
        $config = BrandApi::getBrandById($this->brand_id);
        if (!$config) {
            Yii::error('获取品牌信息失败', 'wechat');
            exit('error');
        }
        $this->brand = $config;
        $this->wechat = Yii::$app->wechat->app($this->brand);
    }

    public function actionPort()
    {
        $server = $this->wechat->server;
        $server->setMessageHandler(function ($message) {
            return MsgHandler::handler($message, $this->brand_id);
        });
        
        $server->serve()->send();
    }

    public function actionMedia()
    {
        $material = $this->wechat->material;
        $list = $material->lists('news');
        print_r($list);

    }

    public function actionTest()
    {
        $url = \backend\service\wechat\functions\Qrcode::forever($this->brand_id, 'test', 60);
        print_r($url);
    }
}
