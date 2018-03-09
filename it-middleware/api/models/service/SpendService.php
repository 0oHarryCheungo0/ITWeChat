<?php

namespace api\models\service;

use api\models\VIPSPEND;

class SpendService
{
    public static function getSpends($vip_code)
    {
        return VIPSPEND::find()
            ->where(['VIPKO' => $vip_code])
            ->orderBy('TXDATE desc')
            ->asArray()
            ->all();
    }

    public static function getSpendById($id)
    {
        return $spends = VIPSPEND::find()->where(['id' => $id])->asArray()->one();
    }

    public static function getSpendByDay($day)
    {
        $vip_types = [
            'CN_SITT0',
            'CN_SIT',
            'CN_IPASS',
            'CN_BITT0',
            'CN_BIT',
            'CN_VPASS',
            'CN_VIPASS',
        ];
        return $spends = VIPSPEND::find()
            ->select(['VIPKO', 'TXDATE', 'LOCKO', 'MEMTYPE', 'DTRAMT','MEMONO'])
            ->where(['TXDATE' => $day . " 00:00:00"])
            ->andWhere(['>', 'DTRAMT', 0])
            ->andWhere(['in', 'VIPTYPE', $vip_types])
            ->asArray()
            ->all();
    }
}
