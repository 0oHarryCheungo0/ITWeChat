<?php
namespace backend\controllers;

use backend\models\Staff;
use backend\models\Store;
use backend\service\Export;
use wechat\models\WechatVip;
use backend\service\store\StoreForm;
use backend\service\store\StoreList;
use common\models\MemberRelation;
use backend\models\VipNoPhone;
use Yii;
use yii\helpers\Url;

class StoreController extends AdminBaseController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $auth = Yii::$app->session->get('auth');

        if (!empty($auth)) {
            $auth = explode(',', trim($auth, ','));
            foreach ($auth as $k => $v) {
                switch ($v) {
                    case '1':
                        return $this->redirect(Url::toRoute('/store/list', true));
                        break;
                    case '2':
                        return $this->redirect(Url::toRoute('/vips/all', true));
                        break;
                    case '3':
                        return $this->redirect(Url::toRoute('/system-rules/index', true));
                        break;
                    case '4':
                        return $this->redirect(Url::toRoute('/menu/add-menu', true));
                        break;
                    case '5':
                        return $this->redirect(Url::toRoute('/discount/birth-list', true));
                        break;
                    case '6':
                        return $this->redirect(Url::toRoute('/news/list', true));
                        break;
                }
            }
        } else {
            $session = Yii::$app->session;
            $session->set('brand_id', '');
            echo '此帐号没有任何权限，请联系超级管理员';
        }
    }

    /**
     * 新增店铺
     */
    public function actionAdd()
    {
        $session = Yii::$app->session;
        if($session->isActive)
        {
            echo 'session is active';
        }
        $title   = '新增';
        $auth    = Yii::$app->authManager;
        $request = Yii::$app->request;
        $list    = new Store();
        if ($request->isAjax) {
            $form = new StoreForm();
            if ($form->load($this->unserializeData($request->post('data')), '') && $form->validate()) {
                if ($form->save()) {
                    return $this->response('成功', 200);
                } else {
                    return $this->response('失败', 301, $form->getErrors['0']);
                }
            } else {
                return $this->response($form->getErrors());
            }
        }
        return $this->render('add', ['list' => $list, 'title' => $title]);
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $form = new StoreForm();

            $data = $this->unserializeData($request->post('data'));

            if (empty($data['id'])) {
                $form->scenario = 'insert';
            } else {
                $form->id = $data['id'];
                $store    = Store::findOne($data['id']);
                if (!empty($store)) {
                    $code = Store::find()->where(['store_code' => $data['store_code']])->all();
                    if (count($code) > 1) {
                        return $this->response('', 300, 'StoreCode重复');
                    }
                    if (count($code) == 1) {
                        if ($store->store_code != $data['store_code']) {
                            return $this->response('', 300, 'StoreCode重复');
                        }
                    }
                }
            }

            if ($form->load($data, '') && $form->validate()) {
                if ($form->save()) {
                    return $this->response('成功', 200);
                } else {
                    return $this->response('失败', 301);
                }
            } else {

                foreach ($form->getErrors() as $k => $v) {
                    return $this->response('', 302, $v['0']);
                }

            }
        }
    }

    /**
     * 店铺列表
     */
    public function actionList()
    {
        //如果为超级管理员，则首页跳转至用户列表页
        if (Yii::$app->user->id === 1) {
            $this->redirect(Url::toRoute('/user/list', true));
        }
        $form    = new StoreForm();
        $request = Yii::$app->request;
        if ($get = $request->isAjax) {
            $search = $request->get('search');
            $limit  = $request->get('limit');
            $offset = $request->get('offset');

            $data = StoreList::getList($limit, $offset, $search);
            return $this->response($data);
        }
        return $this->render('list', ['head' => 1]);
    }

    /**
     * 删除店铺
     */
    public function actionDel()
    {
        // $id = Yii::$app->request->post('id');

        // if (StoreList::deleteOne($id)) {
        //     return $this->response('成功', 200);
        // } else {
        //     return $this->response('', 302, '权限不足，无法删除');
        // }

    }

    /**
     * 编辑店铺
     */
    public function actionEdit()
    {
        $title = '编辑';
        $id    = Yii::$app->request->get('id');
        if ($list = Store::findById($id)) {

            $form = new StoreForm();
            //编辑
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $form->save();
            }
            return $this->render('add', ['list' => $list, 'title' => $title]);

        } else {
            return $this->response('', 300, '权限不足，无法删除');
        }
    }

    /**
     * 禁用店铺
     * @return [type] [description]
     */
    public function actionDown()
    {
        $request  = Yii::$app->request;
        $brand_id = Yii::$app->session->get('brand_id');
        if ($request->isAjax) {
            $id     = $request->post('id');
            $staffs = Staff::find()->where(['brand_id' => $brand_id, 'store_id' => $id, 'is_disabled' => 0])->all();
            if (!empty($staffs)) {
                return $this->response('', 300, '请将店员转移完再禁用店铺');
            }
            $users = MemberRelation::find()->where(['brand_id' => $brand_id, 'store_id' => $id])->all();
            if (!empty($users)) {
                return $this->response('', 300, '请将粉丝全部转移后再禁用店铺');
            }
            $store              = Store::find()->where(['id' => $id, 'brand_id' => $brand_id])->one();
            $store->is_disabled = 1;
            $ret                = $store->save();
            if ($ret) {
                return $this->response('', 200);
            } else {
                return $this->response('', 300, '已禁用');
            }

        }
    }

    /**
     * 启用店铺
     * @return [type] [description]
     */
    public function actionUpStore()
    {
        $id       = Yii::$app->request->post('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $store    = Store::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();
        if (!empty($store)) {
            $store->is_disabled = 0;
            if ($store->save()) {
                return $this->response('', 200);
            }
        }
        return $this->response('', 300);
    }

    /**
     * 店铺列表导出
     * type  1扫码加粉数  2会员绑定数
     * @return [type] [description]
     */
    public function actionScanexport()
    {

        $request = Yii::$app->request;
        $data    = $request->get('data');
        $type    = $request->get('type', 1);
        $list    = new \backend\service\staff\StaffLogic();
        $list->getScan($data, $type);
    }

    public function actionExportmember()
    {
        $data  = Yii::$app->request->get('data');
        $model = new StoreList();
        $ret   = $model->exportNewOld($data);
    }

    /**
     * 店铺导入
     * @return [type] [description]
     */
    public function actionImport()
    {

        if (Yii::$app->request->isPost) {
            $brand_id = Yii::$app->session->get('brand_id');//获取品牌id
            $file     = $_FILES["file"]["tmp_name"];
            $data     = \backend\service\Export::readExcel($file);
            $count    = count($data);
            // Yii::Info('数组长度为' . $count);
            $insert      = [];
            $phones      = [];
            $store_codes = [];
            foreach ($data as $k => $v) {
                if ($k > 1) {
                    if ($this->validateExcel($v)) {
                        $phones[]                      = $v['C'];
                        $store_codes[]                 = $v['B'];
                        $insert[$k - 2]['store_name']  = $v['A'];
                        $insert[$k - 2]['store_code']  = $v['B'];
                        $insert[$k - 2]['tel']         = $v['C'];
                        $insert[$k - 2]['province']    = $v['D'];
                        $insert[$k - 2]['city']        = $v['E'];
                        $insert[$k - 2]['area']        = $v['F'];
                        $insert[$k - 2]['address']     = $v['G'];
                        $insert[$k - 2]['brand_id']    = $brand_id;
                        $insert[$k - 2]['create_time'] = time();
                        $insert[$k - 2]['update_time'] = time();
                    } else {
                        Yii::Info('数据不能为空');
                        return $this->response('', 300, 'Excel模版错误');
                    }

                }

            }

            Yii::info('插入的数据为' . json_encode($insert));

            $phone_data = Store::find()->where(['in', 'tel', $phones])->asArray()->all();
            $code_data  = Store::find()->where(['in', 'store_code', $store_codes])->asArray()->all();
            //Yii::info('店铺编');
            $msg = [];
            $db  = Yii::$app->db;
            //开启事务
            $transaction = $db->beginTransaction();

            if (!empty($code_data)) {
                foreach ($phone_data as $k => $v) {
                    foreach ($insert as $k1 => $v1) {
                        if ($v1['store_code'] == $v['store_code']) {
                            try {
                                $result = Yii::$app->db->createCommand()->update('store', $v1, 'store_code=' . '"' . $v['store_code'] . '"')->execute();
                                $msg[]  = $v1['store_code'];
                                unset($insert[$k1]);
                            } catch (\Exception $e) {
                                $transaction->rollBack();
                                return $this->response('', 300, '更新数据店铺编号' . $v['store_code'] . '异常' . $e->getMessage());
                            }

                        }
                    }
                }
            }
            $int = Yii::$app->db->createCommand()->batchInsert('store', ['store_name', 'store_code', 'tel', 'province', 'city', 'area', 'address', 'brand_id', 'create_time', 'update_time'], $insert)->execute();
            $transaction->commit();
            Yii::info('插入结果为' . $int);
            return $this->response('', 200, json_encode($msg));

        }

        return $this->render('import');
    }

    private function validateExcel($data)
    {
        $key = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        foreach ($key as $k => $v) {
            if (empty($data[$v])) {
                return false;
            }
        }
        return true;
    }

    public function actionTest()
    {
        $list = Store::findById(42);
        return $this->render('test', ['list' => $list]);
    }

    public function actionDownStore()
    {
        $res = Yii::$app->response;
        $res->sendFile('./backend/public/店铺导入模版.xlsx');
    }

    public function actionImportMember()
    {
        $file  = $_FILES["file"]["tmp_name"];
        $data  = \backend\service\Export::readExcel($file);
        $data1 = [];
        foreach ($data as $k => $v) {
            if ($k > 1) {
                $data2   = [];
                $data2[] = $v['A'];
                $data2[] = $v['B'];
                $data2[] = $v['G'];
                $data1[] = $data2;
            }

        }
       // var_dump($data1);exit;

        $ret  =Yii::$app->db->createCommand()->batchInsert('vip_no_phone', ['phone', 'member_id_old', 'member_id_new'], $data1)->execute();
        var_dump($ret);exit;
        
    }

    public function actionImportPoint()
    {
        return $this->render('import-member');
    }

    public function actionRecoveryData(){

        $relation = MemberRelation::find()->all();
        $i = 0;
        $j = 0;
        $data = [];
        foreach ($relation as $k=>$v){
           $ret = WechatVip::find()->where(['vip_no'=>$v->member_id])->one();
           if (empty($ret)){
                $phone = VipNoPhone::find()->where(['member_id_old'=>$v->member_id])->one();
                if (!empty($phone)){
                    $i++;
                }else{
                    $data[] = $v->member_id;
                }
                $j++;
           }
        }
        echo '有的数据:'.$i;
        echo '所有数据'.$j;
        echo "<br>";
        var_dump($data);exit;
    }

    public function actionReData(){
        $relation = MemberRelation::find()->all();
        $i = 0;
        $j = 0;
        $data = [];
        foreach ($relation as $k=>$v){
           $ret = WechatVip::find()->where(['vip_no'=>$v->member_id])->one();
           if (empty($ret)){
                $phone = VipNoPhone::find()->where(['member_id_old'=>$v->member_id])->one();
                if (!empty($phone)){
                    $v->member_id = $phone->member_id_new;
                    $v->save();
                    $i++;
                }else{
                    $data[] = $v->member_id;
                }
                $j++;
           }
        }

    }

    public function actionScanMember(){
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $all = MemberRelation::find()->where(['staff_id'=>7839,'store_id'=>170])->all();
        foreach ($all as $k=>$v){
            $v->staff_id  = 9253;
            $v->save();
            $count = \common\models\StaffCounts::find()->where(['staff_id'=>7839])->one();
            $vip_count = $count->vip_count;
            $count->vip_count = $vip_count-1;
            $count->save();

            $count = \common\models\StaffCounts::find()->where(['staff_id'=>9253])->one();
            $vip_count = $count->vip_count;
            $count->vip_count = $vip_count+1;
            $count->save();
        }
        $transaction->commit();
        var_dump($all);exit;
    }

    public function actionDelMember(){
        $id = '186,323,717,719,744,757,839,1200,1467,1540,1541,1650,1709,1711,2301,2302,2541,2829,2890,2893,2900,2908,3052,3101,3199';
        $arr = explode(',',$id);
        // foreach ($arr as $k=>$v){
        //     $result = WechatVip::find()->where(['id'=>$v])->one();
        //     var_dump($result);exit;
        // }
        $result = WechatVip::deleteAll(['in','id',$arr]);

    }

    public function actionLookMember(){
         $id = '186,323,717,719,744,757,839,1200,1467,1540,1541,1650,1709,1711,2301,2302,2541,2829,2890,2893,2900,2908,3052,3101,3199';
        $arr = explode(',',$id);
        $i = 0;
        foreach ($arr as $k=>$v){
            $result = WechatVip::find()->where(['id'=>$v])->one();
            if (!empty($result->store_id)){
                $i =1;
            }
        }
        echo $i;

    }

    public function actionBindTest(){
        $relation = MemberRelation::find()->all();
        $other =  [];
        if (!empty($relation)){
            foreach ($relation as $k=>$v){
                $vip_no = $v->member_id;
                $vip = WechatVip::find()->where(['vip_no'=>$vip_no])->one();
                if (!empty($vip)){
                    $phone = $vip->phone;
                    $v->phone = $phone;
                    $v->save();
                }else{
                    $other[] = $vip_no;
                }
            }
        }
        var_dump($other);
    }

}
