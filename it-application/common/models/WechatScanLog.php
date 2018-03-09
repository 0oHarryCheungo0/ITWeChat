<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_scan_log".
 *
 * @property integer $id
 * @property string $openid
 * @property string $scan_key
 * @property string $scan_date
 * @property integer $subscribe
 */
class WechatScanLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_scan_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid', 'scan_date'], 'required'],
            [['scan_date'], 'safe'],
            [['subscribe'], 'integer'],
            [['openid', 'scan_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'scan_key' => 'Scan Key',
            'scan_date' => 'Scan Date',
            'subscribe' => 'Subscribe',
        ];
    }
}
