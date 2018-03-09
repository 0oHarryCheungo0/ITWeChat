<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_vips_active_logs".
 *
 * @property integer $id
 * @property string $vip_code
 * @property integer $types
 * @property string $create_time
 * @property string $remark
 */
class WechatVipsActiveLogs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_vips_active_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_code', 'create_time'], 'required'],
            [['types'], 'integer'],
            [['create_time'], 'safe'],
            [['vip_code'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
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
            'types' => 'Types',
            'create_time' => 'Create Time',
            'remark' => 'Remark',
        ];
    }
}
