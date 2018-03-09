<?php

namespace common\models;

use backend\models\Dynamic;
use yii\db\ActiveRecord;

class NewsPoint extends ActiveRecord
{
    public static function tableName()
    {
        return 'news_point';
    }

    public function getNews()
    {
        return $this->hasOne(Dynamic::className(), ['id' => 'news_id']);
        
    }
}
