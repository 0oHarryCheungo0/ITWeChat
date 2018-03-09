<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/9/7
 * Time: 下午6:32
 */

namespace console\controllers;


use api\models\crm\VPFILE;
use api\models\Profile;
use yii\console\Controller;

class MidController extends Controller
{
    public function actionRun()
    {
        $no_mids = Profile::find()->where(['W_MID' => null])->all();
        foreach ($no_mids as $no_mid) {
            $profile = VPFILE::find()->where(['ALTID' => $no_mid->ALTID])->one();
            if ($profile && $profile->W_MID) {
                echo "准备更新{$no_mid->ALTID}的W_MID => {$profile->W_MID}\n";
                $no_mid->W_MID = $profile->W_MID;
                $no_mid->save();
            } else {
                echo "没有W_MID {$no_mid->ALTID}\n";
            }
        }
    }


    public function reset()
    {
        $profiles = Profile::find()->all();
        foreach ($profiles as $profile) {
            if ($profile->W_MID) {
                $crmVPFILE = VPFILE::find()->where(['ALTID' => $profile->ALTID])->one();
                if ($crmVPFILE) {
                    //有记录
                    if (!$crmVPFILE->W_MID) {
                        //保存数据
                    }
                }
            }
        }
    }

}