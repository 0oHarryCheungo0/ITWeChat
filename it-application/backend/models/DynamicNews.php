<?php

namespace backend\models;

use yii\db\ActiveRecord;

class DynamicNews extends ActiveRecord
{
    public static function tableName()
    {
        return 'dynamic_news';
    }

    /**
     * 关联动态资讯表
     * @author fushl 2017-05-26
     * @return [type] [description]
     */
    public function getNews()
    {
        return $this->hasOne(Dynamic::className(), ['id' => 'news_id']);
    }
}
