<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property integer $ID
 * @property string $ALTID
 * @property string $VIPNAME
 * @property string $ADDR1
 * @property string $ADDR2
 * @property string $TELNO
 * @property string $SEX
 * @property string $DOB
 * @property string $EMAILADDR
 * @property integer $MOB
 * @property string $W_COUNTRYKO
 * @property string $W_FNAME
 * @property string $W_LNAME
 * @property integer $W_DOB
 * @property integer $W_YOB
 * @property string $W_COUNTRY
 * @property string $W_PROVINCE
 * @property string $W_CITY
 * @property string $W_DISTRICT
 * @property string $W_DTL_ADDR
 * @property string $W_SALARY
 * @property string $W_OCCUPATION
 * @property string $W_EDUCATION
 * @property string $W_INTERESTS
 * @property string $W_MID
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ALTID', 'VIPNAME', 'TELNO', 'SEX'], 'required'],
            [['DOB'], 'safe'],
            [['MOB', 'W_DOB', 'W_YOB'], 'integer'],
            [['W_SALARY'], 'number'],
            [['ALTID', 'VIPNAME', 'ADDR1', 'ADDR2', 'EMAILADDR', 'W_COUNTRYKO', 'W_PROVINCE', 'W_CITY', 'W_DISTRICT', 'W_DTL_ADDR', 'W_OCCUPATION', 'W_EDUCATION', 'W_INTERESTS', 'W_MID'], 'string', 'max' => 255],
            [['TELNO'], 'string', 'max' => 20],
            [['SEX'], 'string', 'max' => 2],
            [['W_FNAME', 'W_LNAME', 'W_COUNTRY'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'ALTID' => 'Altid',
            'VIPNAME' => 'Vipname',
            'ADDR1' => 'Addr1',
            'ADDR2' => 'Addr2',
            'TELNO' => 'Telno',
            'SEX' => 'Sex',
            'DOB' => 'Dob',
            'EMAILADDR' => 'Emailaddr',
            'MOB' => 'Mob',
            'W_COUNTRYKO' => 'W  Countryko',
            'W_FNAME' => 'W  Fname',
            'W_LNAME' => 'W  Lname',
            'W_DOB' => 'W  Dob',
            'W_YOB' => 'W  Yob',
            'W_COUNTRY' => 'W  Country',
            'W_PROVINCE' => 'W  Province',
            'W_CITY' => 'W  City',
            'W_DISTRICT' => 'W  District',
            'W_DTL_ADDR' => 'W  Dtl  Addr',
            'W_SALARY' => 'W  Salary',
            'W_OCCUPATION' => 'W  Occupation',
            'W_EDUCATION' => 'W  Education',
            'W_INTERESTS' => 'W  Interests',
            'W_MID' => 'W  Mid',
        ];
    }
}
