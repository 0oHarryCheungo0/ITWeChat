<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/26
 * Time: 上午11:11
 */

namespace common\models\logic;


use common\models\StaffCounts;
use yii;

class StaffLogic
{
    /**
     * 创建用户时，调用newStaff方法，增加统计记录
     * @param $store_id
     * @param $staff_id
     * @return bool
     */
    public static function newStaff($store_id, $staff_id)
    {
        $staff = new StaffCounts();
        $staff->staff_id = $staff_id;
        $staff->store_id = $store_id;
        $staff->last_update = date('Y-m-d H:i:s');
        $staff->scan_count = 0;
        $staff->subscribe_count = 0;
        $staff->vip_count = 0;
        return $staff->save();
    }

    /**
     * 添加统计数量
     * @param string $key 扫描二维码的键值
     * @param bool $subscribe 是否是关注事件
     * @return bool
     */
    public static function scan($key, $subscribe = false)
    {
        Yii::error('关注的保存统计事件'.$key,'error');
        $staff_id = self::findByKey($key);
        if (!$staff_id) {
            Yii::error('key:' . $key . '找不到staff_id', 'error');
            return false;
        }
        $staff = StaffCounts::find()->where(['staff_id' => $staff_id])->one();
        if (!$staff) {
            return false;
        }
        $staff->scan_count++;
        if ($subscribe) {
            $staff->subscribe_count++;
        }
        $staff->last_update = date('Y-m-d H:i:s');
        $staff->save();
        Yii::error('保存关注统计事件','error');
        return true;
    }

    public static function vip()
    {

    }

    /**
     * 通过key,找到Staff_id
     * @param $key
     * @return int/bool
     */
    public static function findByKey($key)
    {
        //TODO 通过key,找到staff_id;
        $staff = \backend\service\staff\StaffLogic::getStaffInfo($key);
        if ($staff){
            return $staff['id'];
        } else {
            return false;
        }
    }
}