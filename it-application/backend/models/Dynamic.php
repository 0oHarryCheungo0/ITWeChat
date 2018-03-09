<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Dynamic extends ActiveRecord
{
    public static function tableName()
    {
        return 'dynamic';
    }

}
