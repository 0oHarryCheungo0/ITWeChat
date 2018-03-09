<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "staff_counts".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $staff_id
 * @property integer $scan_count
 * @property integer $subscribe_count
 * @property integer $vip_count
 * @property string $last_update
 */
class StaffCounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_counts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'staff_id', 'last_update'], 'required'],
            [['store_id', 'staff_id', 'scan_count', 'subscribe_count', 'vip_count'], 'integer'],
            [['last_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'staff_id' => 'Staff ID',
            'scan_count' => 'Scan Count',
            'subscribe_count' => 'Subscribe Count',
            'vip_count' => 'Vip Count',
            'last_update' => 'Last Update',
        ];
    }


}
