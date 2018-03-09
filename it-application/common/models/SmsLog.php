<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_log".
 *
 * @property integer $id
 * @property string $phone
 * @property string $msg
 * @property integer $flag
 * @property string $send_time
 * @property string $remark
 */
class SmsLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', 'msg', 'send_time'], 'required'],
            [['flag'], 'integer'],
            [['send_time'], 'safe'],
            [['phone'], 'string', 'max' => 20],
            [['msg'], 'string', 'max' => 255],
            [['remark'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'msg' => 'Msg',
            'flag' => 'Flag',
            'send_time' => 'Send Time',
            'remark' => 'Remark',
        ];
    }
}
