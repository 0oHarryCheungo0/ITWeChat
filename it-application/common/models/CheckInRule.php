<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "check_in_rule".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $base
 * @property string $config
 * @property string $update_time
 */
class CheckInRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'check_in_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'config', 'update_time'], 'required'],
            [['brand_id', 'base'], 'integer'],
            [['config'], 'string'],
            [['update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'base' => 'Base',
            'config' => 'Config',
            'update_time' => 'Update Time',
        ];
    }
}
