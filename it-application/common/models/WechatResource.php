<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_resource".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $title
 * @property string $image
 * @property string $url
 * @property string $description
 * @property string $create_date
 * @property string $update_date
 * @property integer $status
 */
class WechatResource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_resource';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'status'], 'integer'],
            [['title', 'image', 'url', 'description', 'create_date', 'update_date'], 'required'],
            [['create_date', 'update_date', 'url'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'title' => 'Title',
            'image' => 'Image',
            'url' => 'Url',
            'description' => 'Description',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'status' => 'Status',
        ];
    }
}
