<?php

namespace wechat\models;

use Yii;

/**
 * This is the model class for table "vip_bonus".
 *
 * @property integer $id
 * @property string $vip_code
 * @property double $bonus
 * @property double $exp_bonus
 * @property integer $update_time
 */
class VipBonus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_bonus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_code', 'bonus', 'exp_bonus', 'update_time'], 'required'],
            [['bonus', 'exp_bonus'], 'number'],
            [['update_time'], 'integer'],
            [['vip_code'], 'string', 'max' => 20],
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
            'bonus' => 'Bonus',
            'exp_bonus' => 'Exp Bonus',
            'update_time' => 'Update Time',
        ];
    }
}
