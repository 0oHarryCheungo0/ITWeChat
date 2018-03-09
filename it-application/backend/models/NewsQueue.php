<?php

namespace backend\models;

use yii\db\ActiveRecord;

class NewsQueue extends ActiveRecord
{
    public static function tableName()
    {
        return 'news_queue';
    }

    /**
     * 存储队列
     * @param  [type]  $news_id  [description]
     * @param  [type]  $brand_id [description]
     * @param  string  $vip_type [description]
     * @param  integer $type     1关联模板表 关联dynamic表
     * @param  integer $is_send  [description]
     * @return [type]            [description]
     */
    public static function saveQueue($news_id, $brand_id, $vip_type = '', $type = 1, $is_send = 0)
    {
        $model           = new self();
        $model->news_id  = $news_id;
        $model->brand_id = $brand_id;
        $model->vip_type = $vip_type;
        $model->type     = $type;
        $model->is_send  = $is_send;
        $model->save();
    }

    /**
     * 显示为已读
     * @param  [type] $id       [description]
     * @param  [type] $brand_id [description]
     * @return [type]           [description]
     */
    public static function updateSend($id,$brand_id){
    	$model = static::find()->where(['id'=>$id,'brand_id'=>$brand_id])->one();
    	$model->is_send = 1;
    	$model->save();
    }
}
