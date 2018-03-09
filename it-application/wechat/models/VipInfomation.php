<?php

namespace wechat\models;

use common\models\WechatVipsActiveLogs;
use Yii;

/**
 * This is the model class for table "vip_infomation".
 *
 * @property integer $id
 * @property string $altid
 * @property string $addr1
 * @property string $addr2
 * @property integer $marriage
 * @property integer $income
 * @property integer $education
 * @property string $interest
 * @property string $career
 * @property string $area
 * @property string $province
 * @property string $city
 * @property string $town
 */
class VipInfomation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_infomation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['altid'], 'required'],
            [['marriage', 'income', 'education'], 'integer'],
            [['altid'], 'string', 'max' => 30],
            [['addr1', 'addr2', 'interest', 'career', 'area', 'province', 'city', 'town'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'altid' => 'Altid',
            'addr1' => 'Addr1',
            'addr2' => 'Addr2',
            'marriage' => 'Marriage',
            'income' => 'Income',
            'education' => 'Education',
            'interest' => 'Interest',
            'career' => 'Career',
            'area' => 'Area',
            'province' => 'Province',
            'city' => 'City',
            'town' => 'Town',
        ];
    }

    public static function getPercentage(WechatVip $vip, VipInfomation $record)
    {
        $percentage = 0;
        if ($vip->name_first != '' && $vip->name_last != '') {
            $percentage += 10;
        }
        if ($vip->sex !== '') {
            $percentage += 10;
        }
        if ($vip->birthday != '') {
            $percentage += 10;
        }
        if ($vip->email != '') {
            $percentage += 10;
        }

        if ($record->addr1 != '') {
            $percentage += 10;
        }
        if ($record->marriage !== null) {
            $percentage += 10;
        }
        if ($record->income !== null) {
            $percentage += 10;
        }
        if ($record->career != null) {
            $percentage += 10;
        }
        if ($record->education !== null) {
            $percentage += 10;
        }
        if ($record->interest != '') {
            $percentage += 10;
        }
        return $percentage;
    }

    public static function canGetBonus(WechatVip $vip, VipInfomation $record)
    {
        $percentage = self::getPercentage($vip, $record);
        if ($percentage == 100) {
            $is_log = WechatVipsActiveLogs::find()->where(['types' => 2, 'vip_code' => $vip->vip_no])->one();
            if (!$is_log) {
                $log = new WechatVipsActiveLogs();
                $log->vip_code = $vip->vip_no;
                $log->types = 2;
                $log->create_time = date('Y-m-d H:i:s');
                $log->remark = '完成资料填写赠送积分';
                $log->save();
                return true;
            } else {
                return false;
            }
        } else {
            Yii::error('完成度不够');
            return false;
        }

    }
}
