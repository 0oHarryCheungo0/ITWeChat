<?php

namespace wechat\models\logic;

use common\models\Sms;
use common\api\SMS as MessageSMS;
use Yii;

class SmsLogic
{
    public function save($phone, $code, $brand = 1, $exp = 300, $type = \common\api\SMS::CHINA)
    {
        $phone_data = Sms::find()->where(['phone' => $phone])->one();
        if (!empty($phone_data)) {
            $is_use = $phone_data->is_use;
            if ($is_use == 1) {
                Yii::Info('验证码已使用');
                return 300;
            } else {
                $phone_data->code = $code;
                $phone_data->exp_time = time() + $exp;
                $ret_update = $phone_data->save();
                if ($ret_update) {
                    if ($this->send($phone, $code, $brand, $type)){
                        return 200;
                    } else {
                        return 100;
                    }
                }
            }
        } else {
            $ret = $this->savePhone($phone, $code, $exp);
            if ($ret) {
                if ($this->send($phone, $code, $brand, $type)){
                    return 200;
                } else {
                    return 100;
                }
            }

        }
    }

    public function send($phone, $code, $brand = 1, $type = \common\api\SMS::CHINA)
    {
        Yii::info('发送短信' . $phone . '验证码为' . $code);
        if ($brand == 1) {
            $tag = "【I.T】";
        } else {
            $tag = '【i.t】';
        }
        return MessageSMS::send($phone, '您的验证码为:' . $code, $tag, $type);
    }

    public function savePhone($phone, $code, $exp)
    {
        $model = new Sms();
        $model->phone = $phone;
        $model->code = $code;
        $model->create_time = time();
        $model->exp_time = time() + $exp;
        $model->is_use = 0;
        $model->save();
        return true;
    }

    public function usePhone($phone)
    {
        $model = Sms::find()->where(['phone' => $phone])->one();
        $model->is_use = 1;
        $model->save();
        Yii::info('电话号码' . $phone . '已使用');
    }

    /**
     * 验证码是否正确
     * @param  [type] $phone [description]
     * @param  [type] $code  [description]
     * @return [type]        [description]
     */
    public static function verifyPhone($phone, $code)
    {
        $result = Sms::find()->where(['phone' => $phone, 'code' => $code])->one();
        if (!$result) {
            $result = Sms::find()->where(['phone'=>'852'.$phone,'code'=>$code])->one();
            if (!$result){
                $result = Sms::find()->where(['phone'=>'853'.$phone,'code'=>$code])->one();
                if (!$result){
                    $result = Sms::find()->where(['phone'=>'886'.$phone,'code'=>$code])->one();
                    if (!$result){
                        Yii::Info('手机号码不存在或验证码错误');
                        return 300;
                    }
                }
            }
        }
        if ($result->is_use == 1) {
            Yii::info('验证码已使用');
            return 301;
        } else {
            if ($result->exp_time < time()) {
                Yii::info('验证码已过期');
                return 302;
            } else {
                Yii::info('验证码正确');
                return 200;
            }
        }
    }


}