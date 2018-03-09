<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "replymenu".
 *
 * @property integer $id
 * @property string $keyword
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $image
 * @property integer $type
 * @property integer $sort
 * @property integer $brand_id
 */
class Replymenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'replymenu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'sort', 'brand_id'], 'integer'],
            [['brand_id'], 'required'],
            [['keyword', 'title'], 'string', 'max' => 25],
            [['description', 'url', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword' => 'Keyword',
            'title' => 'Title',
            'description' => 'Description',
            'url' => 'Url',
            'image' => 'Image',
            'type' => 'Type',
            'sort' => 'Sort',
            'brand_id' => 'Brand ID',
        ];
    }
}
