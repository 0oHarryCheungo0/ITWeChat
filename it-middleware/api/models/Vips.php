<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "vips".
 *
 * @property integer $id
 * @property string $vip_code
 * @property string $alt_id
 * @property string $vip_type
 * @property string $group
 * @property string $update_time
 * @property integer $state
 * @property integer $reflect
 * @property string $join_date
 * @property string $exp_date
 */
class Vips extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vips';
    }

    public function getInfo()
    {
        return $this->hasOne(VipInfos::className(),['alt_id'=>'alt_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_code', 'alt_id', 'vip_type', 'group', 'update_time', 'state', 'join_date', 'exp_date'], 'required'],
            [['update_time', 'join_date', 'exp_date'], 'safe'],
            [['state', 'reflect'], 'integer'],
            [['vip_code', 'alt_id'], 'string', 'max' => 255],
            [['vip_type', 'group'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_code' => 'Vip Code',
            'alt_id' => 'Alt ID',
            'vip_type' => 'Vip Type',
            'group' => 'Group',
            'update_time' => 'Update Time',
            'state' => 'State',
            'reflect' => 'Reflect',
            'join_date' => 'Join Date',
            'exp_date' => 'Exp Date',
        ];
    }
}
