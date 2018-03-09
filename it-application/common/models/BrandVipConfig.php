<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "brand_vip_config".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $vr_prefix
 * @property integer $reg_point
 * @property integer $exp_time
 * @property integer $finish_profile
 */
class BrandVipConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_vip_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'vr_prefix'], 'required'],
            [['brand_id', 'reg_point', 'exp_time', 'finish_profile'], 'integer'],
            [['vr_prefix'], 'string', 'max' => 20],
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
            'vr_prefix' => 'Vr Prefix',
            'reg_point' => 'Reg Point',
            'exp_time' => 'Exp Time',
            'finish_profile' => 'Finish Profile',
        ];
    }
}
