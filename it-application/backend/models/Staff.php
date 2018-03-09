<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\WechatScanLog;
use wechat\models\WechatUser;
use \common\models\StaffCounts;
use common\models\MemberRelation;

/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property string $staff_name
 * @property string $staff_code
 * @property string $extra
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $store_id
 */
class Staff extends ActiveRecord
{
    public $brand_name;

    public $store_name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * 附加行为
     */
    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],

            ],
        ];
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    public function getScan()
    {
        return $this->hasMany(WechatScanLog::className(), ['scan_key' => 'key']);
    }

    public function getFans(){
        return $this->hasMany(MemberRelation::className(),['staff_id'=>'id']);
    }

    public function getUser()
    {
        return $this->hasOne(WechatUser::className(), ['openid' => 'openid'])
            ->viaTable('wechat_scan_log', ['scan_key' => 'key']);
    }

    public function deleteById($id)
    {
        static::find()->where(['id' => $id])->one();
    }

    public function findById($id)
    {
        $uid = Yii::$app->user->id;
        return static::find()->where(['id' => $id, 'user_id' => $uid])->one();
    }

    public function afterFind()
    {
        $this->create_time = date('Y-m-d H:i:s', $this->create_time);
        $this->update_time = date('Y-m-d H:i:s', $this->update_time);
    }

    /**
     * 关联品牌表
     */
    public function getBrandOne()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * 关联店铺
     */
    public function getStoreOne()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * 关联统计表
     * @return [type] [description]
     */
    public function getCounts()
    {
        return $this->hasOne(StaffCounts::className(), ['staff_id' => 'id']);
    }

    public function beforeDelete()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        if ($this->brand_id != $brand_id) {
            return false;
        } else {
            return true;
        }
    }

    public static function getStoreIdById($id)
    {
        $result = static::findOne($id);
        if (!empty($result)) {
            return $result['store_id'];
        } else {
            throw new \Exception('店员不存在');
        }
    }

    public function attributeLabels()
    {
        return [
            'staff_name'  => '员工姓名',
            'staff_code'  => '员工编码',
            'create_time' => '入职时间',
            'update_time' => '更新时间',
        ];
    }

}
