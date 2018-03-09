<?php

namespace wechat\models;

use Yii;

/**
 * This is the model class for table "vip_check_in".
 *
 * @property integer $id
 * @property string $vip_no
 * @property string $last_check_in
 * @property integer $duration
 * @property integer $history
 */
class VipCheckIn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_check_in';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_no', 'last_check_in', 'duration', 'history'], 'required'],
            [['last_check_in'], 'safe'],
            [['duration', 'history'], 'integer'],
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
            'last_check_in' => 'Last Check In',
            'duration' => 'Duration',
            'history' => 'History',
        ];
    }
}
