<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $wechat_id
 * @property string $create_time
 * @property string $finish_time
 * @property integer $status
 * @property integer $type
 * @property string $template_id
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wechat_id', 'status', 'type'], 'integer'],
            [['create_time', 'finish_time'], 'safe'],
            [['template_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wechat_id' => 'Wechat ID',
            'create_time' => 'Create Time',
            'finish_time' => 'Finish Time',
            'status' => 'Status',
            'type' => 'Type',
            'template_id' => 'Template ID',
        ];
    }
}
