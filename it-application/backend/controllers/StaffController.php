<?php

namespace backend\controllers;

use backend\models\Staff;
use backend\models\StaffBack;
use backend\models\Store;
use backend\models\User;
use backend\service\staff\StaffForm;
use backend\service\staff\StaffLogic;
use common\middleware\Queue;
use common\models\MemberRelation;
use common\models\StaffCounts;
use wechat\models\WechatVip;
use Yii;
use yii\db\Query;

class StaffController extends AdminBaseController
{

    public $enableCsrfValidation = false;

    /**
     * 会员列表
     */
    public function actionList()
    {
        $request  = Yii::$app->request;
        $store    = Store::findAllByBrand();
        $store_id = $request->get('store_id');
        if ($request->isAjax) {
            $offset   = $request->get('offset');
            $limit    = $request->get('limit');
            $search   = $request->get('search');
            $store_id = $request->get('store_id');
            $model    = new StaffLogic();
            $data     = $model->getList($limit, $offset, $search, $store_id);
            return $this->response($data);
        }
        return $this->render('list', ['store' => $store, 'store_id' => $store_id]);
    }

    /**
     * 会员报表导出
     * @return file
     */
    public function actionExport()
    {
        $obj    = Yii::$app->request->get('data');
        $data   = json_decode($obj, true);
        $model  = new StaffLogic();
        $export = $model->exportStaff($data);
        // \backend\service\Export::staff($export);
    }

    /**
     * 员工新增
     * @return [type] [description]
     */
    public function actionAdd()
    {
        $store    = Store::findAllByBrand();
        $store_id = Yii::$app->request->get('store_id');
        return $this->render('add', ['store' => $store, 'store_id' => $store_id, 'staff_id' => 0, 'extra' => '', 'staff_name' => '', 'staff_code' => '']);
    }

    public function actionEdit()
    {
        $store      = Store::findAllByBrand();
        $staff_id   = Yii::$app->request->get('staff_id');
        $model      = Staff::findOne($staff_id);
        $staff_name = $model->staff_name;
        $store_id   = $model->store_id;
        $staff_code = $model->staff_code;
        $extra      = $model->extra;
        //$store_id = Yii::$app->request->get('store_id');

        return $this->render('add', ['store' => $store, 'store_id' => $store_id, 'staff_id' => $staff_id, 'extra' => $extra, 'staff_name' => $staff_name, 'staff_code' => $staff_code, 'list' => $model]);
    }

    /**
     * 员工数据更新
     * @return [type] [description]
     */
    public function actionUpdate()
    {
        $staff   = new StaffForm();
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $data = $this->unserializeData($request->post('data'));
            if (empty($data['id'])) {
                $staff->setScenario('insert');
            }
            if ($staff->load($this->unserializeData($request->post('data')), '') && $staff->validate()) {
                $staff->save($data['id']);
                return $this->response('成功', 200);
            } else {
                foreach ($staff->getErrors() as $k => $v) {
                    return $this->response('', 300, $v['0']);
                }
            }
        }
        return $this->response('新增');
    }

    /**
     * 员工删除
     * @return [type] [description]
     */
    public function actionDel()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $id     = $request->get('id');
            $result = Staff::findOne($id);
            $del    = $result->delete();
            if ($del) {
                return $this->response('', 200);
            } else {
                return $this->response('', 300);
            }
        }
        return $this->response('删除');
    }

    /**
     * 员工转移
     * @return [type] [description]
     */
    public function actionTransfer()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $request  = Yii::$app->request;
        //判断是否有转移会员的权限
        $id = Yii::$app->request->get('id');
        //列出可转移的店铺名
        //$store = Store::findAllByBrand();
        $store = Store::find()->where(['brand_id' => $brand_id, 'is_disabled' => 0])->all();
        if ($request->isAjax) {
            $id       = $request->post('id');
            $store_id = $request->post('store_id');
            if (empty($store_id)) {
                return $this->response('', 301, '请选择店铺');
            }
            $sta = MemberRelation::find()->where(['staff_id' => $id, 'brand_id' => $brand_id])->one();
            if (!empty($sta)) {
                return $this->response('', 300, '转移失败,该店员下有绑定会员');
            }
            $extra           = $request->post('extra');
            $staff           = Staff::findOne($id);
            $staff->store_id = $store_id;
            $staff->extra    = $extra;
            if ($staff->save()) {
                return $this->response($store_id, 200);
            } else {
                return $this->response('', 300, '转移失败');
            }

        }
        return $this->renderPartial('transfer', ['store' => $store, 'id' => $id]);
    }

    /**
     * 员工转移
     * @return [type] [description]
     */
    public function actionTransferAll()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $request  = Yii::$app->request;
        //判断是否有转移会员的权限
        if ($request->isGet) {
            $id = Yii::$app->request->get('id');
            //列出可转移的店铺名
            //$store = Store::findAllByBrand();

            $staffs = Staff::findOne($id);

            $store_id = $staffs->store_id;
            $store    = Staff::find()->where(['brand_id' => $brand_id, 'store_id' => $store_id, 'is_disabled' => 0])->all();
        }

        if ($request->isAjax) {
            $id       = $request->post('id');
            $staff_id = $request->post('store_id');
            if (empty($staff_id)) {
                return $this->response('', 301, '请选择店员');
            }
            $sta = MemberRelation::find()->where(['staff_id' => $id, 'brand_id' => $brand_id])->all();
            $i   = 0;
            if (!empty($sta)) {
                try {
                    foreach ($sta as $k => $v) {
                        $i++;
                        $v->staff_id = $staff_id;
                        $v->save();
                    }
                    Yii::$app->db->createCommand()->update('staff_counts', ['vip_count' => new \yii\db\Expression('vip_count-' . $i)], 'staff_id=' . $id)->execute();
                    Yii::$app->db->createCommand()->update('staff_counts', ['vip_count' => new \yii\db\Expression('vip_count+' . $i)], 'staff_id=' . $staff_id)->execute();
                    $ret = true;

                } catch (\Exception $e) {
                    $ret = false;
                }

            } else {
                $ret = false;
            }

            if ($ret) {
                return $this->response($staff_id, 200);
            } else {
                return $this->response('', 300, '转移失败');
            }

        }
        return $this->renderPartial('transfer-all', ['store' => $store, 'id' => $id]);
    }

    /**
     * 查看员工推广
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionPush()
    {
        $request = Yii::$app->request;

        $staff_id = $request->get('staff_id');
        $query    = new Query();
        if ($request->isAjax) {
            $logic    = new StaffLogic();
            $staff_id = $request->get('staff_id');
            $limit    = $request->get('limit');
            $offset   = $request->get('offset');
            Yii::info('员工id为' . $staff_id);
            $data = $logic->getScanList($staff_id, $limit, $offset, '');
            return $this->response($data);
        }

        return $this->render('push', ['staff_id' => $staff_id]);
    }

    /**
     * 查看推广
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionScanpush()
    {
        $id     = Yii::$app->request->get('id');
        $result = User::findOne($id);
        return $this->renderPartial('member', ['detail' => $result]);
    }

    /**
     * 员工二维码
     * @author fushl 2017-05-23
     * @return [type] [description]
     */
    public function actionCodelist()
    {
        $store_id = Yii::$app->request->get('store_id');

        $model = new Staff();
        $list  = $model::find()->where(['store_id' => $store_id])->select(['qrcode', 'staff_name', 'staff_code'])->all();

        return $this->renderPartial('code', ['list' => $list]);
    }

    /**
     * 员工详情
     * @author fushl 2017-05-23
     * @return [type] [description]
     */
    public function actionDetail()
    {
        $staff_id = Yii::$app->request->get('staff_id');
        $detail   = Staff::findOne($staff_id);
        return $this->renderPartial('detail', ['detail' => $detail]);
    }

    public function actionDownStaff()
    {
        $id       = Yii::$app->request->post('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $users    = MemberRelation::find()->where(['brand_id' => $brand_id, 'staff_id' => $id])->all();
        if (!empty($users)) {
            return $this->response('', 300, '请转移所有粉丝后再禁用店员');
        }

        $staff              = Staff::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();
        $staff->is_disabled = 1;
        $staff->save();
        return $this->response('', 200);
    }

    public function actionUpStaff()
    {
        $id                 = Yii::$app->request->post('id');
        $brand_id           = Yii::$app->session->get('brand_id');
        $staff              = Staff::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();
        $staff->is_disabled = 0;
        $ret                = $staff->save();
        if ($ret) {
            return $this->response('', 200);
        }
    }

    /**
     * 店员导入
     * @return [type] [description]
     */
    public function actionImport()
    {
        if (Yii::$app->request->isPost) {
            ini_set("memory_limit", "1024M"); //大文件防止爆内存
            set_time_limit(0);
            $brand_id    = Yii::$app->session->get('brand_id');
            $file        = $_FILES["file"]["tmp_name"];
            $data        = \backend\service\Export::readExcel($file);
            $count       = count($data);
            $insert      = [];
            $data_n      = [];
            $unique_data = [];
            foreach ($data as $k => $v) {
                if ($k > 1) {

                    $staff    = new \backend\service\staff\Form();
                    $store_id = Store::find()->where(['store_code' => $v['A'], 'brand_id' => $brand_id])->one()['id'];
                    Yii::info('店铺编号为' . $store_id);
                    $data_n[$k]['store_id']   = $store_id;
                    $data_n[$k]['staff_name'] = $v['B'];
                    $data_n[$k]['staff_code'] = $v['C'];
                    $unique_data[]            = $v['C'];
                    $data_n[$k]['extra']      = $v['D'];
                    $staff1                   = Staff::find()->where(['staff_code' => $v['C']])->one();

                    if (!empty($staff1)) {
                        $satff_id1 = $staff1->id;
                        $sta       = MemberRelation::find()->where(['staff_id' => $satff_id1])->one();
                        if (!empty($sta)) {
                            return $this->response('', 300, $k . '请确实覆盖店员不包含绑定会员');
                        }

                    }

                    if (empty($sta)) {

                    }
                    $staff->load($data_n[$k], '');
                    if ($staff->validate()) {

                    } else {
                        $error = $staff->getErrors();
                        return $this->response('', 300, '第' . $k . '行数据:' . json_encode($error, JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            
            if (count($unique_data) != count(array_unique($unique_data))) {
                return $this->response('', 300, '存在重复的店员编号');
            }

            foreach ($data_n as $k => $v) {
                Queue::importStaff($brand_id, $v['store_id'], $v['staff_name'], $v['staff_code'], $v['extra']);
            }
            return $this->response('', 200);
        }
        return $this->render('import');

    }

    public function actionUp()
    {
        return $this->render('up');
    }

    public function actionTest()
    {
        $uid      = 1;
        $brand_id = Yii::$app->session->get('brand_id');
        \common\api\DiscountApi::sendExclusive($uid, $brand_id);
    }

    public function actionTest2()
    {
        $uid      = 1;
        $brand_id = Yii::$app->session->get('brand_id');
        \common\api\DiscountApi::sendBirthDay($uid, $brand_id);
    }

    public function actionTest3()
    {
        $uid      = 1;
        $brand_id = Yii::$app->session->get('brand_id');
        $ret      = \common\api\NewsApi::pointPrefect($uid, $brand_id);
        var_dump($ret);exit;
    }

    public function actionTest5()
    {
        $brand_id = 2;
        $result   = \common\api\BrandApi::getBrandById($brand_id);

        $options = [
            'debug'  => true,
            'app_id' => $result['app_id'],
            'secret' => $result['secret'],
            'token'  => $result['token'],
        ];
        $app = new \EasyWeChat\Foundation\Application($options);

        $notice = $app->notice;

        $uid  = Yii::$app->request->get('uid');
        $type = Yii::$app->request->get('type');

        $ret = \common\api\WxPushApi::upgrade($uid, $type);
        //var_dump($ret['openid']);exit;
        $messageId = $notice->to('odVWHwP6CNGgwzKFwwGeL2D9CMqc')
            ->uses($ret['id'])
            ->andUrl($ret['url'])
            ->data($ret['datas'])
            ->send();
        var_dump($ret['datas']);
        var_dump($messageId);exit;
    }

    public function actionTest1()
    {
        $openid = Yii::$app->request->get('openid');
        \backend\service\fans\FansList::bindMember($openid, $is_old = 0);
    }

    public function actionTest7()
    {
        $uid      = Yii::$app->request->get('uid');
        $brand_id = Yii::$app->request->get('brand_id');
        //$ret  = \common\api\NewsApi::sendPoint($uid, $brand_id
        //$ret  = \common\api\NewsApi::sendDiscount($uid, $brand_id);
        // $ret = \common\api\DiscountApi::sendExclusive($uid,$brand_id);
        $ret = \common\api\NewsApi::memberRank($uid, $brand_id);
        var_dump($ret);
    }

    public function actionDown()
    {
        $res = Yii::$app->response;
        $res->sendFile('./backend/public/店员导入模版.xlsx');
    }

    /**
     * 员工恢复表
     * @return [type] [description]
     */
    public function actionRecovery()
    {
        $db          = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $staffs      = Staff::find()->all();
        foreach ($staffs as $k => $v) {
            $ret = \common\models\StaffCounts::find()->where(['staff_id' => $v->id])->one();
            if (empty($ret)) {
                $model              = new \common\models\StaffCounts();
                $model->staff_id    = $v->id;
                $model->store_id    = $v->store_id;
                $model->last_update = date('Y-m-d H:i:s');
                $re                 = $model->save();
                if ($re) {
                    $back           = new \backend\models\StaffBack();
                    $back->staff_id = $v->id;
                    $back->save();
                }
            }
        }
        $transaction->commit();
        echo '成功';
    }

    /**
     * 恢复会员
     * @return [type] [description]
     */
    public function actionRecoveryFans()
    {
        $db          = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $backs       = StaffBack::find()->all();
        foreach ($backs as $k => $v) {
            $staff_id               = $v->staff_id;
            $count                  = \common\models\StaffCounts::find()->where(['staff_id' => $staff_id])->one();
            $staff                  = Staff::findOne($staff_id);
            $scan_key               = $staff->key;
            $scans                  = \common\models\WechatScanLog::find()->where(['scan_key' => $scan_key])->count();
            $fans                   = \common\models\WechatScanLog::find()->where(['scan_key' => $scan_key, 'subscribe' => 1])->count();
            $member                 = \common\models\WechatScanLog::find()->where(['scan_key' => $scan_key, 'is_syn' => 1])->count();
            $count->scan_count      = $scans;
            $count->subscribe_count = $fans;
            $count->vip_count       = $member;
            $count->save();
        }
        $transaction->commit();
        echo '成功';
    }

    public function actionRecoveryMember()
    {
        $db          = Yii::$app->db;
        $transaction = $db->beginTransaction();
        $relation    = MemberRelation::find()->all();
        foreach ($relation as $k => $v) {
            $member_id = $v->member_id;
            $vip       = WechatVip::find()->where(['vip_no' => $member_id])->one();
            if (!empty($vip)) {
                if (empty($vip->store_id)) {
                    $vip->store_id = $v->store_id;
                    $vip->save();
                }
            }
        }
        $transaction->commit();
        echo '成功';

    }

    public function actionReset()
    {
        $vip_no = Yii::$app->request->get('vip_no');
        $post   = Yii::$app->db->createCommand('SELECT * from wechat_vip  WHERE vip_no=' . '"' . $vip_no . '"')
            ->queryOne();
        $vip_no         = $post['vip_no'];
        $wechat_user_id = $post['wechat_user_id'];
        $user           = Yii::$app->db->createCommand('SELECT * from wechat_user  WHERE id=' . $wechat_user_id)
            ->queryOne();
        $openid      = $user['openid'];
        $db          = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            Yii::$app->db->createCommand()->delete('wechat_scan_log', 'openid = ' . '"' . $openid . '"')->execute();
            Yii::$app->db->createCommand()->delete('member_relation', 'openid = ' . '"' . $openid . '"')->execute();
            Yii::$app->db->createCommand()->delete('wechat_user', 'openid = ' . '"' . $openid . '"')->execute();
            Yii::$app->db->createCommand()->delete('wechat_vips_active_logs', 'vip_code = ' . '"' . $vip_no . '"')->execute();
            Yii::$app->db->createCommand()->delete('vip_bonus', 'vip_code = ' . '"' . $vip_no . '"')->execute();
            Yii::$app->db->createCommand()->delete('vip_check_in', 'vip_no = ' . '"' . $vip_no . '"')->execute();
            Yii::$app->db->createCommand()->delete('vip_check_in_logs', 'vip_no = ' . '"' . $vip_no . '"')->execute();
            Yii::$app->db->createCommand()->delete('vip_infomation', 'vip_no = ' . '"' . $vip_no . '"')->execute();
            Yii::$app->db->createCommand()->delete('wechat_vip', 'vip_no = ' . '"' . $vip_no . '"')->execute();

            echo '成功';

            $transaction->commit();

        } catch (\Exception $e) {

            $transaction->rollBack();
            var_dump($e->getMessage());

        }

    }
}
