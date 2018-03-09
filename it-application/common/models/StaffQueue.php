<?php

namespace common\models;


use yii\db\ActiveRecord;

class StaffQueue extends ActiveRecord
{
    public static function tableName()
    {
        return 'staff_queue';
    }


}
