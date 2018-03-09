<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "vip_indexs".
 *
 * @property integer $id
 * @property integer $value
 * @property string $remark
 */
class VipIndexs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_indexs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'value'], 'required'],
            [['id', 'value'], 'integer'],
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
            'value' => 'Value',
            'remark' => 'Remark',
        ];
    }
}
