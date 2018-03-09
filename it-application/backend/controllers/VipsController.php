<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/20
 * Time: 下午3:06
 */

namespace backend\controllers;


use backend\models\Store;
use backend\models\WechatGroupMessage;
use backend\models\WechatGroupVips;
use backend\service\Export;
use backend\service\vips\GroupHelper;
use common\middleware\Queue;
use common\models\WechatGroups;
use common\models\WechatResource;
use wechat\models\WechatVip;
use yii;

class VipsController extends AdminBaseController
{
    /**
     * 所有会员
     * @return string
     */
    public function actionAll()
    {
        $stores = Store::find()->where(['brand_id' => $this->brand_id, 'is_disabled' => 0])->asArray()->all();
        return $this->render('all', ['stores' => $stores]);
    }

    /**
     * VIP会员表格记录
     */
    public function actionTableData()
    {
        $list = WechatVip::find();
        //查询字段
        $list->where(['wechat_vip.brand' => Yii::$app->session->get('brand_id')]);
        $list->andWhere(['<>', 'wechat_user_id', 0]);
        //根据VIP_CODE查询
        $vip_code = Yii::$app->request->get('vip_code');
        if ($vip_code) {
            $list->andWhere(['like', 'vip_no', $vip_code]);
        }
        //根据VIP_TYPE查询
        $vip_type = Yii::$app->request->get('vip_type');
        if ($vip_type) {
            $list->andWhere(['like', 'vip_type', $vip_type]);
        }
        //根据手机号码查询
        $phone = Yii::$app->request->get('phone');
        if ($phone) {
            $list->andWhere(['like', 'phone', $phone]);
        }
        //根据会员姓名查询
        $name = Yii::$app->request->get('name');
        if ($name) {
            $list->andWhere(['like', 'name', $name]);
        }
        //根据邮箱地址查询
        $email = Yii::$app->request->get('email');
        if ($email) {
            $list->andWhere(['like', 'email', $email]);
        }
        //根据性别查询
        $sex = Yii::$app->request->get('sex', null);
        if ($sex != null) {
            $list->andWhere(['sex' => $sex]);
        }
        //根据当前积分查询
        $itoken = Yii::$app->request->get('itoken', 0);
        if ($itoken > 0) {
            $list->join('INNER JOIN', 'vip_bonus', 'vip_bonus.vip_code=wechat_vip.vip_no and vip_bonus.bonus>=' . $itoken);
        }

        $store = Yii::$app->request->get('store', 0);
        if ($store != 0) {
            if ($store == -1) {
                $list->andWhere(['store_id' => null]);
            } else {
                $list->andWhere(['store_id' => $store]);
            }
        }
        $mob = Yii::$app->request->get('mob');
        if ($mob) {
            $list->andWhere("$mob=DATE_FORMAT(birthday,'%m')");
        }
        //根据绑定时间
        $bind_start = Yii::$app->request->get('bindstart');
        $bind_end = Yii::$app->request->get('bindend');
        if ($bind_start && $bind_end) {
            $list->andWhere(['between', 'bind_time', $bind_start . " 00:00:00", $bind_end . ' 23:59:59']);
        }
        $province = Yii::$app->request->get('province');
        $city = Yii::$app->request->get('city');
        if ($province && $city) {
            $list->with(['wechat', 'bonus', 'store']);
            $list->joinWith(['info' => function ($query) use ($city) {
                $query->where(['vip_infomation.city' => $city]);
            }]);
        } else {
            $list->with(['wechat', 'bonus', 'info', 'store']);
        }
        $total = $list->count();
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        $this->response($data);
    }

    /**
     * 导出搜索条件的会员
     */
    public function actionExport()
    {
        $list = WechatVip::find();
        //查询字段
        $list->where(['brand' => Yii::$app->session->get('brand_id')]);
        $list->andWhere(['<>', 'wechat_user_id', 0]);
        //根据VIP_CODE查询
        $vip_code = Yii::$app->request->get('vip_code');
        if ($vip_code) {
            $list->andWhere(['like', 'vip_no', $vip_code]);
        }
        //根据VIP_TYPE查询
        $vip_type = Yii::$app->request->get('vip_type');
        if ($vip_type) {
            $list->andWhere(['like', 'vip_type', $vip_type]);
        }
        //根据手机号码查询
        $phone = Yii::$app->request->get('phone');
        if ($phone) {
            $list->andWhere(['like', 'phone', $phone]);
        }
        //根据会员姓名查询
        $name = Yii::$app->request->get('name');
        if ($name) {
            $list->andWhere(['like', 'name', $name]);
        }
        //根据邮箱地址查询
        $email = Yii::$app->request->get('email');
        if ($email) {
            $list->andWhere(['like', 'email', $email]);
        }
        //根据性别查询
        $sex = Yii::$app->request->get('sex', null);
        if ($sex != null) {
            $list->andWhere(['sex' => $sex]);
        }
        //根据当前积分查询
        $itoken = Yii::$app->request->get('itoken', 0);
        if ($itoken > 0) {
            $list->join('INNER JOIN', 'vip_bonus', 'vip_bonus.vip_code=wechat_vip.vip_no and vip_bonus.bonus>=' . $itoken);
        }
        $store = Yii::$app->request->get('store', 0);
        if ($store != 0) {
            if ($store == -1) {
                $list->andWhere(['store_id' => null]);
            } else {
                $list->andWhere(['store_id' => $store]);
            }
        }
        //根据生日查询
        $birthday_start = Yii::$app->request->get('bstart');
        $birthday_end = Yii::$app->request->get('bend');
        if ($birthday_start && $birthday_end) {
            $list->andWhere(['between', 'birthday', $birthday_start, $birthday_end]);
        }

        $mob = Yii::$app->request->get('mob');
        if ($mob) {
            $list->andWhere("$mob=DATE_FORMAT(birthday,'%m')");
        }

        //根据绑定时间
        $bind_start = Yii::$app->request->get('bindstart');
        $bind_end = Yii::$app->request->get('bindend');
        if ($bind_start && $bind_end) {
            $list->andWhere(['between', 'bind_time', $bind_start . " 00:00:00", $bind_end . ' 23:59:59']);
        }
        $province = Yii::$app->request->get('province');
        $city = Yii::$app->request->get('city');
        if ($province && $city) {
            $list->with(['wechat', 'bonus', 'store']);
            $list->joinWith(['info' => function ($query) use ($city) {
                $query->where(['vip_infomation.city' => $city]);
            }]);
        } else {
            $list->with(['wechat', 'bonus', 'info', 'store']);
        }
        $total = $list->count();
        $all = $list->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        Export::vips($all);
    }

    /**
     * 查看分组
     * @return string
     */
    public function actionGroup()
    {
        return $this->render('group');
    }

    /**
     * 分组表格记录
     */
    public function actionGroupData()
    {
        $list = WechatGroups::find();
        $list->where(['brand_id' => $this->brand_id, 'status' => 1]);
        $total = $list->count();
        $all = $list
            ->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        $this->response($data);
    }

    public function actionDeleteGroup()
    {
        $group_id = Yii::$app->request->post('group_id');
        $group = WechatGroups::findOne($group_id);
        $group->status = 0;
        $group->save();
        return $this->response();
    }

    public function actionDeleteMessage()
    {
        $message_id = Yii::$app->request->post('message_id');
        $message = WechatGroupMessage::findOne($message_id);
        $message->status = 3;
        $message->save();
        return $this->response();
    }

    /**
     * 分组消息
     * @return string
     */
    public function actionGroupMessage()
    {
        return $this->render('groupmessage');
    }

    public function actionGroupView()
    {
        $this->layout = 'layer';
        return $this->render('groupview');
    }


    public function actionGroupViewData()
    {
        $group_id = Yii::$app->request->get('group_id');
        $list = WechatGroupVips::find();
        $list->where(['group_id' => $group_id]);
        $total = $list->count();
        $all = $list
            ->with('vips')
            ->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        return $this->response($data);
    }

    public function actionDeleteVips()
    {
        $vip_id = Yii::$app->request->post('vips_id');
        $vip = WechatGroupVips::findOne($vip_id);
        $vip->delete();
        return $this->response();
    }

    /**
     * 分组消息列表表格记录
     */
    public function actionGroupMessageData()
    {
        $group_id = Yii::$app->request->get('group_id');
        $list = WechatGroupMessage::find();
        $list->where(['group_id' => $group_id]);
        $list->andWhere(['<>', 'status', 3]);
        $total = $list->count();
        $all = $list
            ->limit(Yii::$app->request->get('limit'))
            ->offset(Yii::$app->request->get('offset'))
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $data = ['total' => $total, 'rows' => $all];
        return $this->response($data);
    }

    /**
     * 添加分组展示
     * @return string
     */
    public function actionAddGroup()
    {
        $this->layout = 'layer';
        return $this->render('addgroup');
    }

    /**
     * 编辑分组信息展示
     * @return string
     */
    public function actionEdit()
    {
        $this->layout = 'layer';
        $gorup_id = Yii::$app->request->get('group_id');
        $group = WechatGroups::findOne($gorup_id);
        return $this->render('edit', ['group' => $group]);
    }

    /**
     * 保存分组信息
     */
    public function actionSave()
    {
        $group_id = Yii::$app->request->post('group_id', false);
        $group_name = Yii::$app->request->post('group_name', false);
        if (!$group_name) {
            return $this->response('', 500, '传入值为空');
        }
        //检查关键词重复
        if (!$group_id) {
            $reply = WechatGroups::find()->where(['group_name' => $group_name, 'brand_id' => $this->brand_id])->one();
            if ($reply) {
                return $this->response('', 101, '标题已存在');
            }
            $reply = new WechatGroups();
            $reply->create_date = date('Y-m-d H:i:s');
            $reply->update_date = date('Y-m-d H:i:s');
            $reply->brand_id = $this->brand_id;
            $reply->status = 1;
        } else {
            $reply = WechatGroups::find()->where(['id' => $group_id, 'brand_id' => $this->brand_id])->one();
            $reply->update_date = date('Y-m-d H:i:s');
        }
        $reply->group_name = $group_name;
        if ($reply->save()) {
            return $this->response($reply);
        } else {
            return $this->response('', 102, '保存失败');
        }
    }

    /**
     * 添加成员，通过EXCEL方式上传
     * @return string
     */
    public function actionAddIn()
    {
        $this->layout = 'layer';
        return $this->render('addin');
    }

    /**
     * 添加推送信息展示
     * @return string
     */
    public function actionAddTask()
    {
        $this->layout = 'layer';
        return $this->render('addtask');
    }

    /**
     * 编辑推送信息展示
     * @return string
     */
    public function actionEditTask()
    {
        $this->layout = 'layer';
        $message_id = Yii::$app->request->get('message_id');
        $message = WechatGroupMessage::findOne($message_id);
        if ($message->status != 0) {
            exit('不允许编辑');
        }
        if ($message->msg_type == 1) {
            $source = WechatResource::find()
                ->where(['in', 'id', json_decode($message->resource_ids, true)])
                ->andWhere(['status' => 1])
                ->all();
        } else {
            $source = [];
        }
        return $this->render('edittask', ['message' => $message, 'source' => $source]);
    }

    /**
     * 编辑推送信息
     */
    public function actionSaveTaskEdit()
    {
        $message_id = Yii::$app->request->post('message_id');
        $json_data = Yii::$app->request->post('json');
        $datas = json_decode($json_data, true);
        $message = WechatGroupMessage::findOne($message_id);
        $message->create_date = date('Y-m-d H:i:s');
        $message->status = 0;
        $message->finish_date = date('Y-m-d H:i:s');
        $message->setAttributes($datas);
        if ($message->save()) {
            return $this->response($message);
        } else {
            return $this->response('', 501, '编辑任务失败');
        }
    }

    /**
     * 保存推送信息
     */
    public function actionSaveTask()
    {
        $group_id = Yii::$app->request->post('group_id');
        $json_data = Yii::$app->request->post('json');
        $datas = json_decode($json_data, true);
        $message = new WechatGroupMessage();
        $message->create_date = date('Y-m-d H:i:s');
        $message->status = 0;
        $message->group_id = $group_id;
        $message->finish_date = date('Y-m-d H:i:s');
        $message->setAttributes($datas);
        if ($message->save()) {
            return $this->response($message);
        } else {
            return $this->response('', 501, '添加任务失败');
        }
    }

    /**
     * 确认发送推送任务到队列
     */
    public function actionSureToSend()
    {
        $message_id = Yii::$app->request->post('message_id');
        $message = WechatGroupMessage::findOne($message_id);
        if ($message->status == 0) {
            if (Queue::sendWechatMessage($message_id)) {
                $message->status = 1;
                $message->save();
                return $this->response($message);
            } else {
                return $this->response('', 1001, '发布任务失败');
            }

        } else {
            return $this->response('', 1002, '任务状态错误');
        }
    }

    /**
     * 通过EXCEL来添加分组成员
     */
    public function actionExcelAdd()
    {
        $group_id = Yii::$app->request->post('group_id');
        $path = Yii::$app->request->post('path');
        $file_path = Yii::$app->basePath . '/../excel/' . $path;
        $excel_data = Export::readExcel($file_path);
        $insert = GroupHelper::addGroup($group_id, $this->brand_id, $excel_data);
        if ($insert) {
            return $this->response($insert);
        } else {
            return $this->response('', 500, '插入失败');
        }
    }

    public function actionUnbind()
    {
        $user_id = Yii::$app->request->post('user_id');
        $vip = WechatVip::findOne($user_id);
        $vip->wechat_user_id = 0;
        $vip->save();
        return $this->response();

    }

}