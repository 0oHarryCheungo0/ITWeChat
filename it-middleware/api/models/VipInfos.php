<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "vip_infos".
 *
 * @property integer $id
 * @property string $alt_id
 * @property string $name
 * @property string $DOB
 * @property string $telno
 * @property string $sex
 * @property string $addr1
 * @property string $addr2
 * @property string $email
 * @property integer $sub_email
 * @property string $W_FNAME
 * @property string $W_LNAME
 * @property string $W_DOB
 * @property string $W_YOB
 * @property string $W_COUNTRY
 * @property string $W_PROVINCE
 * @property string $W_CITY
 * @property string $W_DISTRICT
 * @property string $W_DTL_ADDR
 * @property string $W_SALARY
 * @property string $W_OCCUPATION
 * @property string $W_EDUCATION
 * @property string $W_INTERESTS
 * @property string $W_COUNTRYKO
 */
class VipInfos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_infos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alt_id', 'name', 'telno', 'sex'], 'required'],
            [['DOB'], 'safe'],
            [['sub_email'], 'integer'],
            [['W_SALARY'], 'number'],
            [['alt_id', 'name', 'addr1', 'addr2', 'email', 'W_DOB', 'W_YOB', 'W_PROVINCE', 'W_CITY', 'W_DISTRICT', 'W_DTL_ADDR', 'W_OCCUPATION', 'W_EDUCATION', 'W_INTERESTS'], 'string', 'max' => 255],
            [['telno'], 'string', 'max' => 20],
            [['sex'], 'string', 'max' => 2],
            [['W_FNAME', 'W_LNAME', 'W_COUNTRY','W_COUNTRYKO'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alt_id' => 'Alt ID',
            'name' => 'Name',
            'DOB' => 'Dob',
            'telno' => 'Telno',
            'sex' => 'Sex',
            'addr1' => 'Addr1',
            'addr2' => 'Addr2',
            'email' => 'Email',
            'sub_email' => 'Sub Email',
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
            'W_COUNTRYKO'=>'W COUNTRYKO',
        ];
    }

    public static function getByPhone($phone, $type)
    {
        $vpfiles = self::find()->where(['telno' => $phone])->asArray()->all();
        //如果找不到数据，说明这个手机号码没有注册成为会员
        if (!$vpfiles) {
            return false;
        }
        $rs = false;
        foreach ($vpfiles as $vpfile) {
            $result = Vips::find()
                ->where(['alt_id' => trim($vpfile['alt_id']), 'vip_type' => $type, 'state' => 1])
                ->asArray()
                ->one();
            if ($result) {
                $rs = $result;
            }
        }
        return $rs;
    }
}
