<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Auth extends ActiveRecord
{
    public static function tableName()
    {
        return 'auth';
    }

    public static function getAll()
    {
        return static::find()->where(['>', 'id', '1'])->all();
    }

}
