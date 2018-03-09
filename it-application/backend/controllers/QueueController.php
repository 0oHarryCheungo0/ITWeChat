<?php

namespace backend\controllers;

use app\models\WechatGroupMessage;
use app\models\WechatGroupVips;
use backend\service\news\NewsLogic;
use common\api\BrandApi;
use common\middleware\Queue;
use common\middleware\Record;
use backend\service\discount\DiscountForm;
use common\models\WechatGroups;
use common\models\WechatResource;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use Yii;
use yii\web\Controller;

class QueueController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionHandle()
    {
        $id = Yii::$app->request->get('id');
        $models = new NewsLogic();
        $models->send($id);
    }

    public function actionBonus()
    {
        $data = Yii::$app->request->post();
        if ($data) {
            $rs = Record::addBonus($data);
            if ($rs) {
                Yii::error('处理积分写入成功');
                sleep(3);
                //插入成功3秒后请求同步积分记录表;
                Queue::syncBonus();
            } else {
                Yii::error('处理积分失败');
            }
        }
    }

    /**
     * 发布专属优惠
     * @return [type] [description]
     */
    public function actionDiscount()
    {
        $id = Yii::$app->request->get('id');
        Yii::info('执行发布限时优惠');
        $model = new DiscountForm();
        $model->send($id);
        Yii::info('发布限时优惠成功');
    }
}
