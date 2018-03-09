<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\ParentBrand;
use backend\service\brand\BrandForm;
use backend\service\brand\BrandList;
use common\api\BrandApi;
use Yii;

class BrandController extends AdminBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 权限验证
     */
    public function init()
    {
        parent::init();
        if (!\Yii::$app->user->id == 1) {
            return $this->redirect('');
        }
    }

    /**
     * 新增品牌
     */
    public function actionAdd()
    {
        $title = '新增';
        $request = Yii::$app->request;
        $parent  = ParentBrand::getAll();
        $form    = new BrandForm();
        if ($request->isAjax && $form->loadAjax($request->post()['data'])) {
            $array = $request->post('array');
            Yii::info('提交数据');
            if ($form->parse($array)) {
                Yii::info('解析成功');
                BrandApi::resetBrand(1);
                BrandApi::resetBrand(2);
                return $this->response('', 200);
            } else {
                return $this->response('', 402, $form->getOneError());
            }

        }
        return $this->render('add1', ['parent' => $parent, 'data' => $form,'title'=>$title]);
    }

    /**
     * 品牌列表
     */
    public function actionList()
    {
        $request     = Yii::$app->request;
        $model       = new Brand();
        $return_data = [];
        if ($request->isAjax) {
            $receive = $request->get();
            $data    = BrandList::getList($receive);
            return $this->response($data);
        }

        return $this->render('list');
    }

    /**
     * 删除品牌
     */
    public function actionDel()
    {
        $id    = Yii::$app->request->get('id');
        $brand = Brand::find($id);
        $brand->delete();
        return $this->response('', 200);
    }

    /**
     * 编辑品牌
     *
     * @author fushl 2017-05-03
     * @return [type]
     */
    public function actionEdit()
    {

        $request = Yii::$app->request;
        $title = '编辑';
        $id     = $request->get('id');
        $parent = ParentBrand::getAll();
        if ($request->isPost && $form->load($request->post(), '') && $form->validate()) {
            $form->save();
            BrandApi::resetBrand($id);
        }
        $data = Brand::findOne($id);

        return $this->render('add1', ['data' => $data, 'parent' => $parent,'title'=>$title]);
    }

    public function actionScan()
    {
        $id     = Yii::$app->request->get('id');
        $result = Brand::findOne($id);
        return $this->renderPartial('scan', ['detail' => $result]);
    }

    public function actionTest()
    {
        $title = '新增';
        return $this->renderPartial('add1', ['title' => $title]);
    }

}
