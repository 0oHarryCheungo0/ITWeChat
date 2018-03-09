<?php

namespace api\controllers;

use Yii;

class APIBase extends Base
{
    public $partner_id = null;

    public function beforeAction($action)
    {

        parent::beforeAction($action);
        $headers = Yii::$app->request->headers;
        // $token   = $headers->get('access_token', 2);
        $token = Yii::$app->request->get('access_token', 1);
        if (!$token) {
            $this->response('', 998, 'need access_token');
        }
        if ($this->authToken($token) === false) {
            $this->response('', 1000, 'token error');
        }
        return true;
    }

    protected function authToken($token)
    {
        if (1 == $token) {
            $this->partner_id = 1;
            return true;
        } else {
            return false;
        }
    }


    protected function logError($level = 'api', $content = '', $params = [])
    {
        $root = Yii::getAlias("@runtime");
        $str = "[" . date('Y-m-d H:i:s') . "]";
        $str .= "[" . $level . "]";
        $str .= $content . "\n";
        $str .= "[PARAMS]" . var_export($params, true) . "\n";
        $str .= "[JSON]" . json_encode($params) . "\n\n";
        error_log($str, 3, $root . '/apierror.log');
    }
}
