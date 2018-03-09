<?php

namespace api\models\crm;

use Yii;

/**
 * This is the model class for table "VPFILE".
 *
 * @property string $ALTID
 * @property string $VIPNAME
 * @property string $ADDR1
 * @property string $ADDR2
 * @property string $TELNO
 * @property string $SEX
 * @property string $DOB
 * @property string $EMAILADDR
 * @property string $ROWID
 * @property integer $MOB
 * @property string $WECHATID
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
 * @property string $W_ECOM_SUB
 * @property string $W_M_STATUS
 * @property string $W_SALARY
 * @property string $W_OCCUPATION
 * @property string $W_EDUCATION
 * @property string $W_INTERESTS
 * @property string $W_MID
 */
class VPFILE extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VPFILE';
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
            [['ALTID', 'VIPNAME'], 'required'],
            [['ALTID', 'VIPNAME', 'ADDR1', 'ADDR2', 'TELNO', 'SEX', 'EMAILADDR', 'WECHATID', 'W_COUNTRYKO', 'W_FNAME', 'W_LNAME', 'W_COUNTRY', 'W_PROVINCE', 'W_CITY', 'W_DISTRICT', 'W_DTL_ADDR', 'W_ECOM_SUB', 'W_M_STATUS', 'W_SALARY', 'W_OCCUPATION', 'W_EDUCATION', 'W_INTERESTS', 'W_MID'], 'string'],
            [['DOB', 'ROWID'], 'safe'],
            [['MOB', 'W_DOB', 'W_YOB'], 'integer'],
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
            'ADDR1' => 'Addr1',
            'ADDR2' => 'Addr2',
            'TELNO' => 'Telno',
            'SEX' => 'Sex',
            'DOB' => 'Dob',
            'EMAILADDR' => 'Emailaddr',
            'ROWID' => 'Rowid',
            'MOB' => 'Mob',
            'WECHATID' => 'Wechatid',
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
            'W_ECOM_SUB' => 'W  Ecom  Sub',
            'W_M_STATUS' => 'W  M  Status',
            'W_SALARY' => 'W  Salary',
            'W_OCCUPATION' => 'W  Occupation',
            'W_EDUCATION' => 'W  Education',
            'W_INTERESTS' => 'W  Interests',
            'W_MID' => 'W  Mid',
        ];
    }

    public static function getByPhone($phone, $type)
    {
        $vpfiles = self::find()->where(['TELNO' => $phone])->asArray()->all();
        //如果找不到数据，说明这个手机号码没有注册成为会员
        if (!$vpfiles) {
            return false;
        }
        $rs = [];
        //找到所有的telno=$phone的ALTID,然后用ALTID在VVIP里面所有的ALTID,VIP_TYPE in (type,VPASS,VIPASS) 的VIPKO，并组装起来
        foreach ($vpfiles as $vpfile) {
            $result = VVIP::find()
                ->where(['ALTID' => trim($vpfile['ALTID']), 'VIPType' => $type])
                ->asArray()
                ->all();
            if ($result) {
                $rs[] = $result;
            }
            $result = null;
        }
        $final_result = [];
        foreach ($rs as $_result) {
            foreach ($_result as $key => $value) {
                $final_result[] = $value;
            }
        }
        return $final_result;
    }
}
