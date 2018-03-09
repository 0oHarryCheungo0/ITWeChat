<?php

namespace backend\controllers;

use backend\models\Menu;
use common\api\BrandApi;
use EasyWeChat\Foundation\Application;
use yii;

class MenuController extends AdminBaseController
{
    public function actionIndex()
    {
        $model  = Menu::findOne(['brand_id' => $this->brand_id]);
        $config = $model->menu;
        return $this->render('index', ['menu' => $config]);
    }

    public function actionAddMenu()
    {
        $model = Menu::findOne(['brand_id' => $this->brand_id]);
        if (!empty($model)) {
            $config = $model->menu;
        } else {
            $config = '';
        }

        return $this->render('add-menu', ['menu' => $config]);
    }

    public function actionUpdate()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $data     = Yii::$app->request->post('data');
         $type    = Yii::$app->request->post('type');
        $menu     = Menu::find()->where(['brand_id' => $brand_id])->one();

        if (empty($menu)) {
            Yii::info('菜单为空，进行新增');
            $model           = new Menu();
            $model->menu     = $data;
            $model->brand_id = $brand_id;
            $model->save();
            try {
                if ($type == 1){
                    $this->createMenu($brand_id);
                }
                
                return $this->response('新增成功', 200);
            }catch(\Exception $e){
                return $this->response('新增成功', 300,$e->getMessage());
            }
           
        } else {
            $menu->menu = $data;
            $menu->save();
            Yii::info("菜单存在，进行修改");
              try {
                if ($type == 1){
                    $this->createMenu($brand_id);
                }
                return $this->response('编辑成功', 200);
            }catch(\Exception $e){
                return $this->response('新增成功', 300,$e->getMessage());
            }
        }

    }

    private function createMenu($brand_id)
    {

        $result  = \common\api\BrandApi::getBrandById($brand_id);
      
        $options = [
            'debug'  => true,
            'app_id' => $result['app_id'],
            'secret' => $result['secret'],
            'token'  => $result['token'],
        ];
        $one     = Menu::find()->where(['brand_id' => $brand_id])->one();

        $app     = new Application($options);
        $menu    = $app->menu;
        $buttons = json_decode($one->menu, true);
        // var_dump($buttons);exit;
        foreach ($buttons as $k=>$v){
            if (isset($v['sub_button'])){
                  if (!empty($v['sub_button'])){
                foreach ($v['sub_button'] as $k1 => $v1) {
                    if ($v1['type'] == 2){
                        unset($buttons[$k]['sub_button'][$k1]);
                    }
                }
            }
            }
          
        }

        foreach ($buttons as $k=>$v){
            $sub = [];
            $i=0;
            if (isset($v['sub_button'])){
            foreach ($v['sub_button'] as $k1=>$v1){

                $sub[$i] =$v1;
                $i++;
            }
        
            $buttons[$k]['sub_button']=$sub;
        }
        }
      
       
         foreach ($buttons as $k=>$v){
            if (isset($v['sub_button'])){
            if (empty($v['sub_button'])){
                unset($buttons[$k]);
            }
        }
         }

         $news = [];

        foreach ($buttons as $k=>$v){
            if (isset($v['sub_button'])){
            if (!empty($v['sub_button'])){
                krsort($buttons[$k]['sub_button']);
            }
        }
        }

        foreach ($buttons as $k=>$v){
            if (isset($v['sub_button'])){
            $sub = [];
            $i=0;
            foreach ($v['sub_button'] as $k1=>$v1){

                $sub[$i] =$v1;
                $i++;
            }
        
            $buttons[$k]['sub_button']=$sub;
        }
        }
        foreach ($buttons as $k=>$v){
            $news[]=$v;
        }
      
        $re      = $menu->add($news);
        if ($re->errmsg =='ok'){
            return true;
        }else{
            return false;
        }
    }

    public function actionGetMenu()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $result   = \common\api\BrandApi::getBrandById($brand_id);
        $options  = [
            'debug'  => true,
            'app_id' => $result['app_id'],
            'secret' => $result['secret'],
            'token'  => $result['token'],
        ];
        $one  = Menu::find()->where(['brand_id' => $brand_id])->one();
        $app  = new Application($options);
        $menu = $app->menu;
        $re   = $menu->all();
        var_dump($re);
        exit;
    }

    public function actionConfig()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $model    = Menu::findOne(['brand_id' => $brand_id]);
//        var_dump($model);exit;
        if (\yii::$app->request->isPost) {
            $brand_config = BrandApi::getBrandById($brand_id);
            $wechat       = Yii::$app->wechat->app($brand_config);
            $menu         = $wechat->menu;
            $config       = array();
            foreach ($_POST as $k => $v) {
                $data = $_POST;
                print_r($_POST);
                exit;
                $rev = array();
                if ($v['name'] == "") {
                    continue;
                } else {
                    $rev['name'] = $v['name'];
                    foreach ($v['sub'] as $k => $v) {
                        if ($v['name'] == '') {
                            continue;
                        } else {
                            if ($v['value'] == "") {
                                continue;
                            }
                            if ($v['type'] == 0) {
                                $sub = ["type" => "view", "name" => $v['name'], "url" => $v['value']];
                            }
                            if ($v['type'] == 1) {
                                $sub = ["type" => "click", "name" => $v['name'], "key" => $v['value']];
                            }
                            if ($v['type'] == 2) {
                                continue;
                            }
                            $rev['sub_button'][] = $sub;
                        }
                    }
                }
                if (isset($rev['sub_button'])) {
                    $config[] = $rev;
                }

            
            //发送请求创建菜单;
            $result = $menu->add($config);
            if ($result->errorcode == 0) {
                $menus = json_encode($config);
                if ($model) {
                    $model->brand_id = $brand_id;
                    $model->menu     = $menus;
                } else {
                    $model           = new Menu();
                    $model->brand_id = $brand_id;
                    $model->menu     = $menus;
                }
                if (!$model->save()) {
                    print_r($model->getErrors());
                    exit("保存失败");
                }
            } else {
                exit("创建失败");

            }

        }
        if ($model) {
            $data = json_decode($model['menu'], true);
        } else {
            $data = array();
        }

        return $this->render("menu", ['data' => $data]);
    }

}
