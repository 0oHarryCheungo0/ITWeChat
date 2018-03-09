<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "vip".
 *
 * @property integer $ID
 * @property string $VIPKO
 * @property string $AltID
 * @property string $VIPNature
 * @property string $VIPType
 * @property string $VRID
 * @property string $JoinDate
 * @property string $ExpDate
 * @property string $StartDate
 * @property string $updateTime
 */
class Vip extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VIPKO', 'AltID', 'VIPNature', 'VIPType', 'JoinDate', 'ExpDate', 'updateTime'], 'required'],
            [['JoinDate', 'ExpDate', 'StartDate', 'updateTime'], 'safe'],
            [['VIPKO', 'AltID', 'VIPNature', 'VIPType', 'VRID'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'VIPKO' => 'Vipko',
            'AltID' => 'Alt ID',
            'VIPNature' => 'Vipnature',
            'VIPType' => 'Viptype',
            'VRID' => 'Vrid',
            'JoinDate' => 'Join Date',
            'ExpDate' => 'Exp Date',
            'StartDate' => 'Start Date',
            'updateTime' => 'Update Time',
        ];
    }
}
