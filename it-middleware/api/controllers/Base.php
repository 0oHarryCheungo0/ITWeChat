<?php
namespace api\controllers;

use Yii;
use yii\web\Controller;

class Base extends Controller
{
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        if (Yii::$app->request->isGet) {
            Yii::error('get访问' . Yii::$app->controller->id . ' -> ' . Yii::$app->controller->action->id);
        } else if (Yii::$app->request->isPost) {
            Yii::error('post访问');
        }
        return true;
    }

    public function afterAction($action, $result)
    {
        if (!is_null($result)) {
            $out_put = is_array($result) ? $result : $this->respons($result);
            header('Content-Type:application/json; charset=utf-8');
            $json_str = json_encode($out_put, JSON_PRETTY_PRINT);
            Yii::error('返回结果' . $json_str);
            echo $json_str;
        }
    }

    protected function response($data = null, $code = 0, $msg = 'SUCCESS')
    {
        $type = Yii::$app->request->get('format', false);
        if (!$type) {
            $headers      = Yii::$app->request->headers;
            $content_type = $headers->get('format', 'json');
            $type         = $content_type == 'json' ? 'json' : 'xml';
        } else {
            if ($type !== 'json' && $type != 'xml') {
                $type = 'json';
            }
        }
        $ret      = ['code' => $code, 'msg' => $msg, 'data' => $data];
        $response = Yii::$app->response;
        $response->format = $type;
        $response->data   = $ret;
        Yii::trace($ret);
        exit($response->send($ret));
    }
}
