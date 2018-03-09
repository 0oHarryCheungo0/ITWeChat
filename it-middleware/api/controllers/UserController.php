<?php

namespace api\controllers;

use api\models\crm\VPFILE;
use api\models\logic\FindVip;
use api\models\logic\VipRules;
use api\models\Profile;
use api\models\service\VipService;
use api\models\VipInfos;
use api\models\Vips;
use Yii;

class UserController extends APIBase
{
    public function actionPhone()
    {
        $phone = Yii::$app->request->get('phone');
        $type = Yii::$app->request->get('type', 'BIT');
        $name = Yii::$app->request->get('name', 'WECHAT_USER');
        $sex = Yii::$app->request->get('sex', 'M');
        $area = Yii::$app->request->get('area', '中国');
        $wechat_id = Yii::$app->request->get('wechat_id', 1);
        $find = new FindVip($phone, $type, $name, $sex, $area, $wechat_id);
        $find->search();
        $res = $find->getResult();
        if ($res !== false) {
            return $this->response($res);
        } else {
            return $this->response('', 404, '没有对应会员');
        }

    }

    public function actionUpdate()
    {
        $email = Yii::$app->request->post('email');
        $name = Yii::$app->request->post('name');
        $sex = Yii::$app->request->post('sex');
        $addr1 = Yii::$app->request->post('addr1');
        $alt_id = Yii::$app->request->post('alt_id');
        $birthday = Yii::$app->request->post('birthday');
        $wechat_id = Yii::$app->request->post('WECHAT_ID');
        /** @var Profile $profile */
        $profile = Profile::find()->where(['ALTID' => $alt_id])->one();
        if (!$profile) {
            $this->response('', 500, 'alt_id不存在');
        }
        $profile->VIPNAME = $name;
        $profile->SEX = $sex;
        $profile->ADDR1 = $addr1;
        $profile->EMAILADDR = $email;
        $profile->DOB = $birthday;
        $profile->setAttributes(Yii::$app->request->post());
        $profile->save();
        //每次更新，都是插入新数据到表里面
        $insert_data = [
            'ALTID' => $alt_id,
            'ADDR1' => $profile['ADDR1'],
            'VIPNAME' => $name,
            'SEX' => $sex,
            'EMAILADDR' => $email,
            'TELNO' => $profile->TELNO,
            'DOB' => $profile->DOB,
            'MOB' => date('m', strtotime($profile->DOB)),
            'W_FNAME' => Yii::$app->request->post('W_FNAME', ''),
            'W_LNAME' => Yii::$app->request->post('W_LNAME', ''),
            'W_DOB' => date('d', strtotime($profile->DOB)),
            'W_YOB' => date('Y', strtotime($profile->DOB)),
            'W_COUNTRY' => Yii::$app->request->post('W_COUNTRY', ''),
            'W_PROVINCE' => Yii::$app->request->post('W_PROVINCE', ''),
            'W_CITY' => Yii::$app->request->post('W_CITY', ''),
            'W_DISTRICT' => Yii::$app->request->post('W_DISTRICT', ''),
            'W_DTL_ADDR' => Yii::$app->request->post('W_DTL_ADDR', ''),
            'W_SALARY' => Yii::$app->request->post('W_SALARY', ''),
            'W_OCCUPATION' => Yii::$app->request->post('W_OCCUPATION', ''),
            'W_EDUCATION' => Yii::$app->request->post('W_EDUCATION', ''),
            'W_INTERESTS' => Yii::$app->request->post('W_INTERESTS', ''),
            'ROWID' => date('Y-m-d H:i:s'),
            'W_MID' => $profile->W_MID,
            'W_COUNTRYKO' => $profile->W_COUNTRYKO ? $profile->W_COUNTRYKO : "+86",
            'WECHATID' => $wechat_id,
            'W_M_STATUS' => Yii::$app->request->post('W_M_STATUS', 2),
        ];
        Yii::error($insert_data);
        Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
        Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
        Yii::$app->mssql->createCommand()->insert('VPFILE_W', $insert_data)->execute();

        return $this->response();

    }

}
