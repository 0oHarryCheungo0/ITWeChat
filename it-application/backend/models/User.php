<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $level
 * @property string $indate
 * @property integer $sex
 * @property string $birthday
 * @property string $phone
 * @property string $email
 * @property string $adress
 * @property string $marry
 * @property string $income
 * @property string $job
 * @property string $education
 * @property string $interest
 * @property integer $wechat_id
 * @property integer $ituser_id
 * @property integer $store_id
 * @property integer $staff_id
 * @property integer $brand_id
 * @property string $group_id
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['indate', 'birthday'], 'safe'],
            [['sex', 'wechat_id', 'ituser_id', 'store_id', 'staff_id', 'brand_id'], 'integer'],
            [['name', 'level', 'phone', 'email', 'income', 'job', 'education'], 'string', 'max' => 25],
            [['adress', 'interest'], 'string', 'max' => 255],
            [['marry'], 'string', 'max' => 10],
            [['group_id'], 'string', 'max' => 50],
            [['wechat_id'], 'unique'],
            [['ituser_id'], 'unique'],
            [['store_id'], 'unique'],
            [['staff_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'level' => 'Level',
            'indate' => 'Indate',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'email' => 'Email',
            'adress' => 'Adress',
            'marry' => 'Marry',
            'income' => 'Income',
            'job' => 'Job',
            'education' => 'Education',
            'interest' => 'Interest',
            'wechat_id' => 'Wechat ID',
            'ituser_id' => 'Ituser ID',
            'store_id' => 'Store ID',
            'staff_id' => 'Staff ID',
            'brand_id' => 'Brand ID',
            'group_id' => 'Group ID',
        ];
    }

    /**
     * 获取用户id
     * @author fushl 2017-05-26
     * @return array 用户id
     */
    public static function getUserByBrand()
    {
        return static::find()->select(['id'])
            ->where(['brand_id' => Yii::$app->session->get('brand_id')])
            ->asArray()
            ->all();
    }
}
