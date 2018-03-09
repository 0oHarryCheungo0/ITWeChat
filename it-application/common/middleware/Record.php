<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/5
 * Time: 下午4:14
 */

namespace common\middleware;

use backend\models\Store;
use common\models\FailureBonus;
use GuzzleHttp\Client;
use Yii;

/**
 * Class Record
 * @package common\middleware
 */
class Record
{
    /**
     * 获取按照月份分类的记录
     * @param $vip_code
     * @return bool|array
     */
    public static function getRecord($vip_code)
    {
        $url = Yii::$app->params['middleware_record_query'];
        $client = new Client();
        $request = $client->request('GET', $url, ['query' => ['vip_code' => $vip_code, 'access_token' => 1]]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        if ($ret['code'] != 0) return [];
        $records = $ret['data'];
        $shop = [];
        foreach ($records as $key => $record) {
            if (!isset($shop[$record['LOCKO']])) {
                $store = Store::find()->where(['store_code' => $record['LOCKO']])->one();
                if ($store) {
                    $shop[$record['LOCKO']] = $store->store_name;
                } else {
                    $shop[$record['LOCKO']] = $record['LOCKO'];
                }

            }
            $records[$key]['STORE'] = $shop[$record['LOCKO']];
        }
//        固定月份key,月份为空的话返回空数组。
        $month_data = Formatter::byMonth($records, 'TXDATE');
        return $month_data;
    }

    /**
     * 获取记录详情
     * @param int $id 记录ID
     * @return mixed
     */
    public static function getRecordById($id)
    {
        $url = Yii::$app->params['middleware_spend_detail'];
        $client = new Client();
        $request = $client->request('GET', $url, ['query' => ['id' => $id, 'access_token' => 1]]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        //获取到店铺ID后，本地组合一下。
        $store = Store::find()->where(['store_code' => $ret['data']['LOCKO']])->one();
        if ($store) {
            $ret['data']['store'] = $store->store_name;
        } else {
            $ret['data']['store'] = $ret['data']['LOCKO'];
        }
        return $ret['data'];
    }


    /**
     * 添加积分
     * @param $data
     * @return bool
     */
    public static function addBonus($data)
    {
        $url = Yii::$app->params['middleware_bonus_add'];
        $client = new Client();
        $request = $client->request('POST', $url, ['form_params' => $data]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);

        if ($ret['code'] != 0) {
            $res = $ret['data'];
            $record = new FailureBonus();
            $record->create_time = date('Y-m-d H:i:s');
            $record->process_time = date('Y-m-d H:i:s');
            $record->reason = $res['reason'];
            $record->vip_code = $res['vip_code'];
            $record->params = json_encode($res['params']);
            $record->request_url = $url;
            $record->status = FailureBonus::WAIT_PROCESS;
            if ($record->save()){
                Yii::error('保存积分添加失败成功');
            } else{
                Yii::error('保存积分添加失败');
                Yii::error(json_encode($record->getErrors()));
            }
        } else {
            return true;
        }
    }
}