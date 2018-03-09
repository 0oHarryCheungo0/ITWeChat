<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property string $title
 * @property string $orderid
 * @property integer $orderstatus
 * @property string $content
 * @property integer $brand_id
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderstatus', 'brand_id'], 'integer'],
            [['title', 'orderid'], 'string', 'max' => 25],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'orderid' => 'Orderid',
            'orderstatus' => 'Orderstatus',
            'content' => 'Content',
            'brand_id' => 'Brand ID',
        ];
    }
}
