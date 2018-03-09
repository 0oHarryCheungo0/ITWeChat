<?php

namespace wechat\controllers;

use backend\service\discount\DiscountList;
use backend\service\news\NewsLogic;
use Yii;
use yii\web\Controller;

class ScanController extends Controller
{
    /**
     * 查看积分资讯
     * @return [type] [description]
     */
    public function actionNews()
    {
        $it_type  = Yii::$app->request->get('p_id',1);
        $type     = Yii::$app->request->get('type');
        $id       = Yii::$app->request->get('id');
        $language = Yii::$app->request->get('language');
        $brand_id = Yii::$app->request->get('brand_id');
        switch ($type) {
            //积分资讯
            case 1:
                $detail = NewsLogic::detail($id, $brand_id, $language);
                break;
            //常规等级优惠
            case 2:
                $detail = DiscountList::scanNormal($id, $brand_id, $language);
                break;
            //限时优惠
            case 3:
                $detail = DiscountList::getDetail($id, $brand_id, $language);
                break;
        }
        if ($it_type == 1){
            \Yii::info('大it模版');
            return \Yii::$app->view->renderFile('@app/theme/it/news/scan.php', ['data' => $detail]);
        }else{
            \Yii::info('小it模版');
            return \Yii::$app->view->renderFile('@app/theme/sit/news/scan.php', ['data' => $detail]);
        }
        
    }

}
