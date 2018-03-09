<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "VPFILE_W".
 *
 * @property string $ALTID
 * @property string $VIPNAME
 * @property string $CHINNAME
 * @property string $ADDR1
 * @property string $ADDR2
 * @property string $TELNO
 * @property string $FAXNO
 * @property string $SEX
 * @property string $DOB
 * @property string $EMAILADDR
 * @property integer $MOB
 * @property string $WECHATID
 */
class VPFILEW extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VPFILE_W';
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
            [['ALTID', 'VIPNAME', 'SEX'], 'required'],
            [['ALTID', 'VIPNAME', 'CHINNAME', 'ADDR1', 'ADDR2', 'TELNO', 'FAXNO', 'SEX', 'EMAILADDR', 'WECHATID'], 'string'],
            [['DOB'], 'safe'],
            [['MOB'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ALTID' => 'Altid',
            'VIPNAME' => 'Vipname',
            'CHINNAME' => 'Chinname',
            'ADDR1' => 'Addr1',
            'ADDR2' => 'Addr2',
            'TELNO' => 'Telno',
            'FAXNO' => 'Faxno',
            'SEX' => 'Sex',
            'DOB' => 'Dob',
            'EMAILADDR' => 'Emailaddr',
            'MOB' => 'Mob',
            'WECHATID' => 'Wechatid',
        ];
    }
}
