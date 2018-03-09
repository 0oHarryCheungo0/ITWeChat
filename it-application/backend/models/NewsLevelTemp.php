<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class NewsLevelTemp extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],

            ],
        ];
    }

    public static function tableName()
    {
        return 'news_level_temp';
    }

    public static function isSend($id){
        $result = static::find()->where(['id'=>$id,'status'=>1])->one();
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public static function send($id){
        $model = static::findOne($id);
        $model->status = 1;
        $ret = $model->save();
        if ($ret){
            return true;
        }else{
            return false;
        }
    }
}
