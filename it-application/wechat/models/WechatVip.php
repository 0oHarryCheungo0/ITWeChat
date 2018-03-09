<?php

namespace wechat\models;

use backend\models\Store;
use Yii;

/**
 * This is the model class for table "wechat_vip".
 *
 * @property integer $id
 * @property integer $wechat_user_id
 * @property integer $brand
 * @property string $member_id
 * @property string $vip_no
 * @property string $vip_type
 * @property string $join_date
 * @property string $exp_date
 * @property string $phone
 * @property string $name
 * @property string $email
 * @property integer $sex
 * @property string $birthday
 * @property string $name_first
 * @property string $name_last
 * @property string $update_time
 * @property string $bind_time
 * @property integer $store_id
 */
class WechatVip extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_vip';
    }

    public function getInfo()
    {
        return $this->hasOne(VipInfomation::className(), ['altid' => 'member_id']);
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(),['id'=>'store_id']);
    }

    public function getWechat()
    {
        return $this->hasOne(WechatUser::className(), ['id' => 'wechat_user_id']);
    }

    public function getBonus()
    {
        return $this->hasOne(VipBonus::className(), ['vip_code' => 'vip_no']);
    }

    public function getTheBonus($value = 0)
    {
        return $this->hasOne(VipBonus::className(), ['vip_code' => 'vip_no'])->where(['>=', 'bonus', $value]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wechat_user_id', 'brand', 'member_id', 'vip_no', 'vip_type', 'join_date', 'exp_date', 'phone', 'name'], 'required'],
            [['wechat_user_id', 'brand', 'sex', 'store_id'], 'integer'],
            [['join_date', 'exp_date', 'birthday', 'update_time', 'bind_time'], 'safe'],
            [['member_id', 'vip_type'], 'string', 'max' => 20],
            [['vip_no'], 'string', 'max' => 30],
            [['phone'], 'string', 'max' => 15],
            [['name', 'name_last'], 'string', 'max' => 50],
            [['email', 'name_first'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wechat_user_id' => 'Wechat User ID',
            'brand' => 'Brand',
            'member_id' => 'Member ID',
            'vip_no' => 'Vip No',
            'vip_type' => 'Vip Type',
            'join_date' => 'Join Date',
            'exp_date' => 'Exp Date',
            'phone' => 'Phone',
            'name' => 'Name',
            'email' => 'Email',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'name_first' => 'Name First',
            'name_last' => 'Name Last',
            'update_time' => 'Update Time',
            'bind_time' => 'Bind Time',
            'store_id' => 'Store ID',
        ];
    }
}
