<?php
namespace wechat\controllers;

use common\middleware\Record;
use yii\web\Controller;

/**
 * 系统全局错误
 */
class ErrorController extends Controller
{
    public function actionBrand()
    {
        $this->renderPartial('brand');
    }

    public function actionBrandError()
    {
        echo '品牌信息错误';
    }

    public function actionError()
    {
        echo 'error';
    }

}
