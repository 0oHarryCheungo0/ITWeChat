<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property string $staff_name
 * @property string $staff_code
 * @property string $extra
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $store_id
 */
class StaffBack extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff_back';
    }


}
