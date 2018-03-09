<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wechat_replys".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $keyword
 * @property string $create_date
 * @property string $update_date
 * @property integer $status
 * @property integer $response_type
 * @property string $response_source_ids
 * @property string $response_text
 * @property integer $match_times
 * @property string $media_id
 * @property string $image
 */
class WechatReplys extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_replys';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'keyword', 'create_date', 'update_date'], 'required'],
            [['brand_id', 'status', 'response_type', 'match_times'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['response_text', 'media_id', 'image'], 'string'],
            [['response_source_ids','keyword'], 'string', 'max' => 100],
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
            'keyword' => 'Keyword',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'status' => 'Status',
            'response_type' => 'Response Type',
            'response_source_ids' => 'Response Source Ids',
            'response_text' => 'Response Text',
            'match_times' => 'Match Times',
        ];
    }
}
