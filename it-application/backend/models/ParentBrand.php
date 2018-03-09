<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ParentBrand extends ActiveRecord
{
    public static function tableName()
    {
        return 'parent_brand';
    }

    public static function getAll()
    {
        return static::find()
            ->where(true)
            ->all();
    }
}
