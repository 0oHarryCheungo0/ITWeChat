<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/8
 * Time: 下午5:58
 */

namespace common\middleware;

use GuzzleHttp\Client;
use Yii;

class Spend
{
    public static function query($vip_code)
    {
        $url = Yii::$app->params['middleware_spend_query'];
        $client = new Client();
        $request = $client->request('GET', $url, ['query' => ['vip_code' => $vip_code, 'access_token' => 1]]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        if ($ret['code'] == 0) {
            return Formatter::byMonth($ret['data'],'TXDATE');
        } else {
            return ['bp' => 0, 'exp_in_3month' => 0];
        }
    }


}