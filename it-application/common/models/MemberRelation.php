<?php

namespace common\models;

use backend\models\Staff;
use backend\models\Store;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use yii\db\ActiveRecord;

class MemberRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'member_relation';
    }

    public function attributeLabels()
    {
        return [
            'member_id' => '会员编号',
            'store'     => '店铺编号',
            'staff'     => '会员编号',
            'brand'     => "品牌",
        ];
    }

    public function getUser()
    {
        return $this->hasOne(WechatUser::className(), ['openid' => 'openid']);
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    public function getStaff()
    {
        return $this->hasOne(Staff::className(), ['id' => 'staff_id']);
    }

    public function getVip()
    {
        return $this->hasOne(WechatVip::className(), ['wechat_user_id' => 'id'])
            ->viaTable('wechat_user', ['id' => 'member_id']);
    }

    /**
     * 存储数据
     * @param  [type] $uid       用户id
     * @param  [type] $staff_id  店员id
     * @param  [type] $scan_time 扫码时间
     * @param  [type] $type      类型0粉丝 1会员
     * @param  [type] $is_old    是否是老会员
     * @param  [type] $join_time 绑定时间
     * @return [type]
     */
    public static function saveData($brand_id, $uid, $staff_id, $scan_time, $type = 0, $is_old = 0, $join_time = 0)
    {
        $model            = new self();
        $model->brand_id  = $brand_id;
        $model->member_id = $uid;
        $model->staff_id  = $staff_id;
        $model->store_id  = Staff::findOne($staff_id)['store_id'];
        $model->scan_time = $scan_time;
        $model->join_time = $join_time;
        $model->type      = $type;
        $model->is_old    = $is_old;
        $model->join_time = $join_time;
        $model->save();
    }

    /**
     * 保存粉丝
     * @param  [type] $uid       用户id
     * @param  [type] $staff_id  店员id
     * @param  [type] $scan_time 扫码关注时间
     * @return [type]
     */
    public static function saveFans($brand_id, $uid, $staff_id, $scan_time)
    {
        $result = static::find()->where(['member_id' => $uid])->one();
        if (!empty($result)) {
            //已存在粉丝
            return false;
        } else {
            return self::saveData($brand_id, $uid, $staff_id, $scan_time, 0, 0, $scan_time);
        }

    }

    /**
     * 保存老会员
     * @param  [type] $uid       用户id
     * @param  [type] $staff_id  店员id
     * @param  [type] $scan_time 扫码时间
     * @param  [type] $join_time 入会时间
     * @return [type]
     */
    public static function saveOldMember($brand_id, $uid, $staff_id, $scan_time, $join_time)
    {
        $result = static::find()->where(['member_id' => $uid, 'type' => 1])->one();
        if (!empty($result)) {
            return false;
        } else {
            return self::saveData($brand_id, $uid, $staff_id, $scan_time, 1, 1, $join_time);
        }
    }

    /**
     * 粉丝更新绑定会员
     * @param  [type] $brand_id  品牌id
     * @param  [type] $uid       用户id
     * @param  [type] $join_time 入会时间
     * @return [type]
     */
    public static function updateNewMember($brand_id, $uid)
    {
        $ret = static::find()->where(['member_id' => $uid, 'type' => 0, 'brand_id' => $brand_id])->one();
        if (!empty($ret)) {
            $ret->type = 1;
            $ret->save();
            return true;
        } else {
            return false;
        }
    }

}
