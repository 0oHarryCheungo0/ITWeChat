<?php

namespace common\models;

use yii\db\ActiveRecord;

class NewsMember extends ActiveRecord
{

    public static function tableName()
    {
        return 'news_member';
    }

    public function getTemplate(){
    	return $this->hasOne(\backend\models\NewsLevelTemp::className(),['id'=>'news_id']);
    }
}
