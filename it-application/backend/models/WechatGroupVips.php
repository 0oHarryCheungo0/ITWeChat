<?php

namespace backend\models;

use wechat\models\WechatVip;
use Yii;

/**
 * This is the model class for table "wechat_group_vips".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $brand_id
 * @property string $openid
 * @property string $vip_code
 * @property string $join_date
 */
class WechatGroupVips extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_group_vips';
    }

    public function getVips()
    {
        return $this->hasOne(WechatVip::className(),['vip_no'=>'vip_code']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'brand_id', 'openid', 'vip_code', 'join_date'], 'required'],
            [['group_id', 'brand_id'], 'integer'],
            [['join_date'], 'safe'],
            [['openid', 'vip_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'brand_id' => 'Brand ID',
            'openid' => 'Openid',
            'vip_code' => 'Vip Code',
            'join_date' => 'Join Date',
        ];
    }
}
