<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $VIPKO
 * @property string $ALTID
 * @property string $VIPType
 * @property string $VIPName
 * @property string $Addr1
 * @property string $Addr2
 * @property string $Telno
 * @property string $Sex
 * @property string $Emailaddr
 * @property string $DOB
 * @property integer $MOB
 * @property string $Joindate
 * @property string $Startdate
 * @property string $Expdate
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MEMBER';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VIPKO', 'ALTID', 'VIPType', 'VIPName'], 'required'],
            [['MOB'], 'integer'],
            [['DOB', 'Joindate', 'Startdate', 'Expdate'], 'safe'],
            [['VIPKO', 'ALTID', 'Telno'], 'string', 'max' => 20],
            [['VIPType'], 'string', 'max' => 10],
            [['VIPName'], 'string', 'max' => 30],
            [['Addr1', 'Addr2'], 'string', 'max' => 60],
            [['Sex'], 'string', 'max' => 1],
            [['Emailaddr'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'VIPKO' => 'Vipko',
            'ALTID' => 'Altid',
            'VIPType' => 'Viptype',
            'VIPName' => 'Vipname',
            'Addr1' => 'Addr1',
            'Addr2' => 'Addr2',
            'Telno' => 'Telno',
            'Sex' => 'Sex',
            'Emailaddr' => 'Emailaddr',
            'DOB' => 'Dob',
            'MOB' => 'Mob',
            'Joindate' => 'Joindate',
            'Startdate' => 'Startdate',
            'Expdate' => 'Expdate',
        ];
    }
}
