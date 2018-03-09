<?php

namespace wechat\models;

use Yii;

/**
 * This is the model class for table "vip_check_in_logs".
 *
 * @property integer $id
 * @property string $vip_no
 * @property string $check_time
 * @property integer $point
 */
class VipCheckInLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_check_in_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_no', 'check_time', 'point'], 'required'],
            [['check_time'], 'safe'],
            [['point'], 'integer'],
            [['vip_no'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_no' => 'Vip No',
            'check_time' => 'Check Time',
            'point' => 'Point',
        ];
    }
}
