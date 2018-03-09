<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/30
 * Time: 下午5:05
 */

namespace console\controllers;


use common\middleware\Queue;
use wechat\models\WechatVip;
use yii\console\Controller;

class RunnerController extends Controller
{
    public function actionIndex($param = null)
    {

        if ($param == null) {
            echo 'param参数不能为空';
        } else {
            echo 'hi' . $param;
        }
    }

    public function actionMargin()
    {
        $vips = WechatVip::find()->all();
        foreach ($vips as $vip) {
            Queue::renewInfo($vip->vip_no);
        }
    }

    public function actionUpdate()
    {
        $phones_str = '13078650870
13304316859
13401012976
13408280038
13475767386
13516289100
13545915963
13602600820
13606482037
13631178213
13632788788
13662196391
13670005620
13671530632
13701875356
13705556286
13728630656
13751195264
13760772328
13802849220
13810405252
13811323104
13811734950
13821790901
13869880550
13903756678
13920162740
13940025529
13951015629
13998146009
15013433140
15014103766
15017578520
15022054052
15022625320
15105146882
15201856794
15231592323
15543777566
15602147286
15810559666
15810678288
15811461636
15820319234
15861631641
15900311920
15950564601
15986809658
17053330305
18102088575
18123957766
18202689290
18222043789
18222582729
18266616523
18344286699
18369652706
18512822821
18520137893
18522200157
18602751155
18611871833
18622611365
18622856636
18625202299
18669798080
18681822730
18705323697
18722222660
18800156837
18801445460
18847071771
60244897';

        $phones = explode("\n",$phones_str);

        foreach ($phones as $phone) {
            $vips = WechatVip::find()->where(['phone'=>$phone])->one();
            if ($vips){
                Queue::updateVip($vips->vip_no);
            }

        }

    }

}