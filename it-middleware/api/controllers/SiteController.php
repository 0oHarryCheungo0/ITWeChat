<?php

namespace api\controllers;

use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        $client = new \SoapClient('http://api.it.com/ws/soap');
        $types = $client->__getTypes();
//        print_r($types);
        $params = ['2','2','of0000001'];
        $rs = $client->__call('getProfile',$params);
        print_r($rs);

    }

    public function actionError()
    {
        echo json_encode(['code' => 500, 'msg' => 'system error', 'data' => null]);
    }
}
