<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "wechat_group_message".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $create_date
 * @property string $finish_date
 * @property integer $success_num
 * @property integer $fail_num
 * @property integer $msg_type
 * @property string $resource_ids
 * @property string $msg_text
 * @property integer $status
 */
class WechatGroupMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_group_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'create_date', 'finish_date'], 'required'],
            [['group_id', 'success_num', 'fail_num', 'msg_type', 'status'], 'integer'],
            [['create_date', 'finish_date'], 'safe'],
            [['msg_text'], 'string'],
            [['resource_ids'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'create_date' => 'Create Date',
            'finish_date' => 'Finish Date',
            'success_num' => 'Success Num',
            'fail_num' => 'Fail Num',
            'msg_type' => 'Msg Type',
            'resource_ids' => 'Resource Ids',
            'msg_text' => 'Msg Text',
            'status' => 'Status',
        ];
    }
}
