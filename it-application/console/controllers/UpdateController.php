<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/30
 * Time: 下午5:12
 */

namespace console\controllers;


use common\models\BrandBonusConfig;
use yii\console\Controller;
use yii\helpers\Console;
use yii;

class UpdateController extends Controller
{
    public function actionVip()
    {
        $rs = BrandBonusConfig::findOne(1);
        echo $rs->vb_prefix;
        return 0;
    }

    public function actionBonus()
    {
        $i = 0;
        while (true) {
            sleep(1);
            echo "hi\r\n";
            $i++;
            if ($i == 2) {
                break;
            }
        }
        $this->stdout("Hello?\n", Console::FG_GREEN);
        return 0;
    }

}