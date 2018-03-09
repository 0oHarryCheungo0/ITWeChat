<?php

namespace wechat\models;

use Yii;

/**
 * This is the model class for table "wechat_user".
 *
 * @property integer $id
 * @property string $openid
 * @property string $nickname
 * @property integer $sex
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $headimgurl
 * @property string $create_time
 */
class WechatUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openid', 'nickname', 'sex', 'create_time'], 'required'],
            [['sex'], 'integer'],
            [['create_time'], 'safe'],
            [['openid'], 'string', 'max' => 30],
            [['nickname'], 'string', 'max' => 100],
            [['city', 'province', 'country'], 'string', 'max' => 50],
            [['headimgurl'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
            'city' => 'City',
            'province' => 'Province',
            'country' => 'Country',
            'headimgurl' => 'Headimgurl',
            'create_time' => 'Create Time',
        ];
    }
}
