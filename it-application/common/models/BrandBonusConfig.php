<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "brand_bonus_config".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $type
 * @property string $vb_prefix
 * @property string $vbgroup
 * @property string $update_time
 * @property integer $exp
 */
class BrandBonusConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_bonus_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'type', 'vb_prefix', 'vbgroup', 'update_time'], 'required'],
            [['brand_id', 'type', 'exp'], 'integer'],
            [['update_time'], 'safe'],
            [['vb_prefix'], 'string', 'max' => 20],
            [['vbgroup'], 'string', 'max' => 20],
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
            'type' => 'Type',
            'vb_prefix' => 'Vb Prefix',
            'vbgroup' => 'Vbgroup',
            'update_time' => 'Update Time',
            'exp' => 'Exp',
        ];
    }
}
