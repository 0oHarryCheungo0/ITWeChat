<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/22
 * Time: 下午2:18
 */

namespace api\controllers;

use api\service\SoapService;
use yii;

class ServiceController extends yii\web\Controller
{
    public $ws_url = 'http://api.it.com/service/ws?wsdl';

    public function init()
    {
        Yii::$app->getRequest()->enableCsrfValidation = false;
    }

    /**
     * 服务地址注册  ／service/ws
     */
    public function actionWs()
    {
        //设置Yii响应格式为XML
        \Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        $server = new \SoapServer(dirname(__FILE__) . '/\api\service\SoapService.wsdl', array('soap_version' => SOAP_1_2));
        $server->setClass('\api\service\SoapService'); //注册Service类的所有方法
        $server->handle(); //处理请求
    }

    /**
     * 测试调用
     */
    public function actionTest()
    {
        $client = new \SoapClient($this->ws_url, array(
            'soap_version' => SOAP_1_2,
            'cache_wsdl' => WSDL_CACHE_NONE

        ));

        echo '<pre>';
        echo 'SOAP types';
        print_r($client->__getTypes());
        echo 'SOAP Functions';
        print_r($client->__getFunctions());

        $result = $client->__soapCall('Add', [1, 2]);
        var_dump($result);
    }

}