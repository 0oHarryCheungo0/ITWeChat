<?php 
namespace backend\models;

use yii\db\ActiveRecord;

class VipNophone extends ActiveRecord
{
    public static function tableName()
    {
        return 'vip_no_phone';
    }

}