<?php
namespace backend\models;

use backend\models\Dynamic;
use yii\db\ActiveRecord;

class Discount extends ActiveRecord
{
    /**
     * [tableName description]
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * 关联用户表
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function getMuser()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    public function getDynamic()
    {
        return $this->hasOne(Dynamic::className(), ['id' => 'news_id']);
    }

    public function getTemplate()
    {
        return $this->hasOne(NewsLevelTemp::className(), ['id' => 'news_id']);
    }

}
