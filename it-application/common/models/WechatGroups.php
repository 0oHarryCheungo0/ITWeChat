<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_groups".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $group_name
 * @property string $create_date
 * @property string $update_date
 * @property integer $status
 */
class WechatGroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'group_name', 'create_date', 'update_date'], 'required'],
            [['brand_id', 'status'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['group_name'], 'string', 'max' => 20],
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
            'group_name' => 'Group Name',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'status' => 'Status',
        ];
    }
}
