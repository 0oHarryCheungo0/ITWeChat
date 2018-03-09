<?php

namespace api\models\crm;

use Yii;

/**
 * This is the model class for table "VVIP".
 *
 * @property string $VIPKO
 * @property string $AltID
 * @property string $VIPNature
 * @property string $VIPType
 * @property string $VRID
 * @property string $JoinDate
 * @property string $StartDate
 * @property string $ExpDate
 * @property string $DiscRate
 * @property integer $EmailSub
 */
class VVIP extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VVIP';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('mssql');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VIPKO', 'AltID', 'VIPNature', 'DiscRate', 'EmailSub'], 'required'],
            [['VIPKO', 'AltID', 'VIPNature', 'VIPType', 'VRID'], 'string'],
            [['JoinDate', 'StartDate', 'ExpDate'], 'safe'],
            [['DiscRate'], 'number'],
            [['EmailSub'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VIPKO'     => 'Vipko',
            'AltID'     => 'Alt ID',
            'VIPNature' => 'Vipnature',
            'VIPType'   => 'Viptype',
            'VRID'      => 'Vrid',
            'JoinDate'  => 'Join Date',
            'StartDate' => 'Start Date',
            'ExpDate'   => 'Exp Date',
            'DiscRate'  => 'Disc Rate',
            'EmailSub'  => 'Email Sub',
        ];
    }
}
