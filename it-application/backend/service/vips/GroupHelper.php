<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/27
 * Time: 下午12:00
 */

namespace backend\service\vips;


use backend\models\WechatGroupVips;
use yii;

class GroupHelper
{
    public static function addGroup($group_id, $brand_id, $datas)
    {
        unset($datas[1]);
        $insert_data = [];
        foreach ($datas as $data) {
            $insert_data[] = [
                'group_id' => $group_id,
                'brand_id' => $brand_id,
                'openid' => $data['A'],
                'vip_code' => $data['B'],
                'join_date' => date('Y-m-d H:i:s'),

            ];
        }
        $rows = ['group_id', 'brand_id', 'openid', 'vip_code', 'join_date'];
        $insert = Yii::$app->db->createCommand()->batchInsert(WechatGroupVips::tableName(), $rows, $insert_data)->execute();
        return $insert;
    }

}