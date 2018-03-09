<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "failure_bonus".
 *
 * @property integer $id
 * @property string $vip_code
 * @property string $create_time
 * @property string $process_time
 * @property string $reason
 * @property string $params
 * @property integer $status
 * @property string $request_url
 */
class FailureBonus extends ActiveRecord
{
    CONST WAIT_PROCESS = 0;

    CONST PROCESS_SUCCESS = 1;

    CONST PROCESS_FAIL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'failure_bonus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vip_code', 'create_time', 'process_time', 'reason'], 'required'],
            [['create_time','reason'], 'safe'],
            [['params', 'request_url'], 'string'],
            [['status'], 'integer'],
            [['vip_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vip_code' => 'Vip Code',
            'create_time' => 'Create Time',
            'process_time' => 'Process Time',
            'reason' => 'Reason',
            'params' => 'Params',
            'status' => 'Status',
        ];
    }
}
