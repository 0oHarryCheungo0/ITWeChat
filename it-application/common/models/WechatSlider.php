<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_slider".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $image
 * @property integer $status
 * @property string $url
 * @property integer $indexs
 * @property string $create_date
 * @property string $update_date
 */
class WechatSlider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'image', 'url', 'create_date', 'update_date'], 'required'],
            [['brand_id', 'status', 'indexs'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['image', 'url'], 'string', 'max' => 255],
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
            'image' => 'Image',
            'status' => 'Status',
            'url' => 'Url',
            'indexs' => 'Indexs',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
        ];
    }
}
