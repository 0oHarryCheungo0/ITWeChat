<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Discount;
use backend\models\Dynamic;
use backend\models\NewsLevelTemp;
use backend\models\NewsQueue;
use backend\service\discount\DiscountForm;
use backend\service\discount\DiscountList;
use backend\service\news\templates\TemForm;
use backend\service\QueueService;
use common\middleware\Queue;
use dosamigos\qrcode\QrCode;
use Yii;

class DiscountController extends AdminBaseController
{
    //会员等级专属优惠模版
    const DISCOUNTMEMBER = 3;
    //会员生日月专属优惠模版
    const DISCOUNTBIRTHDAY = 4;
    //限时优惠类型
    const TIMETYPE = 3;

    /**
     * 优惠列表页
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionList()
    {
        $brand = Brand::getMemberRank();
        $new   = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        $brand_data = json_encode($new);
        $logic      = new DiscountList();
        $request    = Yii::$app->request;
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
            $search = $request->get('search');
            $data   = $logic->getList($limit, $offset, $search);
            return $this->response($data);
        }
        return $this->render('list', ['brand_data' => $brand_data]);
    }

    /**
     * 新增限时优惠
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionRelease()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $type     = self::TIMETYPE;
        $form     = new DiscountForm();
        $brand    = Brand::getMemberRank();
        $request  = Yii::$app->request;
        return $this->render('release', ['brand' => $brand, 'id' => '', 'type' => $type, 'data' => $form]);
    }

    /**
     * 编辑限时优惠
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionReleaseEdit()
    {
        $id       = Yii::$app->request->get('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $brand    = Brand::getMemberRank();
        $type     = self::TIMETYPE;
        $form     = new DiscountForm();
        $data     = Dynamic::find()->where(['brand_id' => $brand_id, 'id' => $id])->one()->toArray();
        $form->load($data, '');
        return $this->render('release', ['id' => $id, 'brand' => $brand, 'type' => $type, 'data' => $form]);
    }

    /**
     * 更新限时优惠数据
     * @return [type] [description]
     */
    public function actionUpdateLimit()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $data = [];
            $ajax = Yii::$app->request->post('data');
            $data = json_decode($ajax, true);

            $id = $data['id'];
            unset($data['id']);
            $form = new DiscountForm();
            if ($form->load($data, '') && $form->validate()) {
                $end_time  = $data['end'];
                $send_time = $data['send_time'];
                if (strtotime($send_time) > strtotime($end_time)) {

                    return $this->response('', 300, '到期时间不能早于发布时间');
                } else {

                }

                if (empty($id)) {
                    if ($form->save()) {
                        return $this->response('', 200);
                    } else {
                        return $this->response('', 300, '失败');
                    }
                } else {
                    if ($form->update($id)) {
                        return $this->response('', 200);
                    } else {
                        return $this->response('', 300, '失败');
                    }
                }

            } else {
                foreach ($form->getErrors() as $k => $v) {
                    return $this->response('', 300, $v['0']);
                }
            }
        }
    }

    /**
     * 删除限时优惠
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('id');
        if (DiscountList::deleteOne($id)) {
            return $this->response('', 200);
        } else {
            return $this->response('', 302);
        }

    }

    /**
     * 查看限时优惠详情
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function actionScan()
    {
        $language = Yii::$app->request->get('language', 'hk');
        $id       = Yii::$app->request->get('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $model    = DiscountList::getDetail($id, $brand_id, $language);
        return $this->renderPartial('scan', ['detail' => $model]);
    }

    /**
     * 发布限时优惠
     * @return [type] [description]
     */
    public function actionSendLimit()
    {
        // $news_id  = Yii::$app->request->post('id');
        // $brand_id = Yii::$app->session->get('brand_id');
        // $vip_no   = $this->getVipNo($news_id);
        // if (!empty($vip_no)) {
        //     foreach ($vip_no as $k => $v) {
        //         $no = $v['vip_no'];
        //         $id = QueueService::saveQueue($news_id, $no, $brand_id, 1);
        //         if ($id != false) {
        //             Queue::sendNewsRank($id);
        //         } else {
        //             return $this->response('', 300);

        //         }
        //     }
        // }
        // NewsLevelTemp::send($news_id);
        // return $this->response('', 200);

        $brand_id = Yii::$app->session->get('brand_id');
        $id       = Yii::$app->request->post('id');
        $model    = Dynamic::find()->where(['id' => $id, 'brand_id' => $brand_id])->one();
        $brand    = Brand::getMemberRank();
        foreach ($brand as $k => $v) {
            if ($v['rank'] == $model->member_rank) {
                $vip_type = $v['name'];
            }
        }
        $queue           = new NewsQueue();
        $queue->news_id  = $id;
        $queue->vip_type = $vip_type;
        $queue->brand_id = $brand_id;
        $queue->save();
        $q_id = $queue->getOldPrimaryKey();
        Queue::sendDiscount($q_id);
        return $this->response('', 200);
    }

    public function actionIsAuto()
    {
        $id = Yii::$app->request->post('id');
        if (empty($id)) {
            return $this->response('', 300, '传送数据不存在');
        } else {
            $result = Dynamic::find()->where(['id' => $id])->one();
            if (!empty($result)) {
                if ($result->is_send == 1) {
                    return $this->response('', 300, '不能定时已发布的数据');
                } else {
                    $result->is_auto = 1;
                    $result->is_send = 1;
                    $ret             = $result->save();
                    if ($ret) {
                        return $this->response('', 200, '传送数据不存在');
                    } else {
                        return $this->response('', 300, '设置失败');
                    }
                }
            } else {
                return $this->response('', 300, '数据为空');
            }
        }
    }
    /**
     * 会员等级模版
     * @author fushl 2017-05-31
     * @return [type] [description]
     */
    public function actionRankAdd()
    {
        //会员等级模版
        $type  = self::DISCOUNTMEMBER;
        $brand = Brand::getMemberRank();
        $form  = new TemForm();
        $brand = Brand::getMemberRank();
        return $this->render('rank-add', ['brand' => $brand, 'type' => $type, 'data' => $form, 'id' => '']);
    }

    /**
     * 会员等级编辑
     * @return [type] [description]
     */
    public function actionRankEdit()
    {

        $type  = self::DISCOUNTMEMBER;
        $brand = Brand::getMemberRank();

        $id   = Yii::$app->request->get('id');
        $data = NewsLevelTemp::find()->where(['id' => $id])->one()->toArray();
        $form = new TemForm();
        $form->load($data, '');

        return $this->render('rank-add', ['brand' => $brand, 'type' => $type, 'data' => $form, 'id' => $id]);
    }

    /**
     * 等级优惠列表
     * @return [type] [description]
     */
    public function actionRankList()
    {
        $request = Yii::$app->request;
        $brand   = Brand::getMemberRank();
        $new     = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        $brand_data = json_encode($new);
        if ($request->isAjax) {
            $model  = new DiscountList();
            $limit  = Yii::$app->request->get('limit');
            $offset = Yii::$app->request->get('offset');
            $search = Yii::$app->request->get('search');
            $data   = $model->getRankList($limit, $offset, $search = '');
            return $this->response($data);
        }
        return $this->render('rank-list', ['brand_data' => $brand_data]);
    }

    /**
     * 发布等级优惠
     * @return [type] [description]
     */
    public function actionRel()
    {
        $news_id  = Yii::$app->request->post('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $vip_no   = $this->getVipNo($news_id);
        if (!empty($vip_no)) {
            foreach ($vip_no as $k => $v) {
                $no = $v['vip_no'];
                Queue::sendRank($news_id, $no, $brand_id);
            }
        }
        NewsLevelTemp::send($news_id);
        return $this->response('', 200);

    }

    /**
     * 删除等级优惠
     * @return [type] [description]
     */
    public function actionRankdel()
    {
        $id    = Yii::$app->request->post('id');
        $model = new DiscountList();
        if ($model->delRank($id)) {
            return $this->response('', 200);
        }

    }

    /**
     * 发布生日月优惠
     * @return [type] [description]
     */
    public function actionPubbirth()
    {
        $news_id  = Yii::$app->request->post('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $vip_no   = $this->getBirthVipNo($news_id);

        if (!empty($vip_no)) {
            foreach ($vip_no as $k => $v) {
                $no = $v['vip_no'];
                Queue::sendBirth($news_id, $no, $brand_id);
            }
        } else {
            NewsLevelTemp::send($news_id);
            return $this->response('', 200, ',未存在此月生日的会员');
        }
        NewsLevelTemp::send($news_id);
        return $this->response('', 200);
    }

    public function actionScannormal()
    {

        $type     = Yii::$app->request->get('type', 1);
        $id       = Yii::$app->request->get('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $language = Yii::$app->request->get('language', 'cn');
        $one      = Brand::find()->where(['id' => $brand_id])->one();
        $p_id     = $one['p_id'];
        $url      = Yii::$app->params['qrcode_url'] . '?type=' . $type . '&brand_id=' . $brand_id . '&language=' . $language . '&id=' . $id . '&p_id=' . $p_id;
        Yii::info('生成二维码网址为' . $url);
        QrCode::png($url);exit;

    }

    /**
     * 新增生日月模版
     * @return string
     */
    public function actionAddBirth()
    {
        $form = new TemForm();
        //会员生日月模版
        $type = self::DISCOUNTBIRTHDAY;
        //月份配置数组
        $month = Yii::$app->params['month'];
        $brand = Brand::getMemberRank();
        return $this->render('add-birth-day', ['month' => $month, 'brand' => $brand, 'type' => $type, 'data' => $form, 'id' => '']);
    }

    /**
     * 编辑生日月模版
     */
    public function actionEditBirth()
    {
        //会员生日月模版
        $type = self::DISCOUNTBIRTHDAY;
        //月份配置数组
        $month = Yii::$app->params['month'];
        $brand = Brand::getMemberRank();

        $id   = Yii::$app->request->get('id');
        $data = NewsLevelTemp::find()->where(['id' => $id])->one()->toArray();

        $form = new TemForm();
        $form->load($data, '');

        return $this->render('add-birth-day', ['month' => $month, 'brand' => $brand, 'type' => $type, 'data' => $form, 'id' => $id]);
    }

    /**
     * 生日月列表页
     * @return [type] [description]
     */
    public function actionBrithList()
    {
        return $this->fetch();
    }

    /**
     * 数据操作
     * @return [type] [description]
     */
    public function actionUpdateBirthData()
    {
        if (Yii::$app->request->isAjax) {
            $ajax = Yii::$app->request->post('data');
            $data = json_decode($ajax, true);
            $id   = $data['id'];
            unset($data['id']);
            $form = new TemForm();
            if ($form->load($data, '') && $form->validate()) {
                if (!empty($id)) {
                    Yii::info('编辑数据');
                    $form->updateData($id);
                    return $this->response('', 200);
                } else {
                    Yii::info('新增数据');
                    $form->save();
                    return $this->response('', 200);
                }
            } else {
                foreach ($form->getFirstErrors() as $k => $v) {
                    $error = $v;
                }
                return $this->response('', 300, $error);

            }

        }
    }

    /**
     * 设置生日月默认模版
     * @return [type] [description]
     */
    public function actionSetSend()
    {
        if (Yii::$app->request->isAjax) {
            $brand_id = Yii::$app->session->get('brand_id');
            $id       = Yii::$app->request->post('id');
            $result   = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();

            if (empty($result)) {
                return $this->response('', 300, '数据不存在或权限不足');
            }
            $type          = $result->type;
            $type_children = $result->type_children;
            $member_rank   = $result->member_rank;

            try {
                // $update = NewsLevelTemp::updateAll(['is_set' => 0], ['brand_id' => $brand_id, 'type' => $type, 'type_children' => $type_children, 'member_rank' => $member_rank]);
                $update = NewsLevelTemp::updateAll(['is_set' => 0], ['brand_id' => $brand_id, 'type' => $type, 'member_rank' => $member_rank]);
                //不这么写好像会产生bug
                if (true) {
                    $result->is_set = 1;
                    $result->save();
                    return $this->response('', 200);
                } else {
                    return $this->response('', 300, '');
                }

            } catch (\Exception $e) {
                return $this->response('', 300, '更新失败');
            }
        }

    }

    /**
     * 设置等级优惠默认模版
     * @return [type] [description]
     */
    public function actionSetRank()
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $brand_id = Yii::$app->session->get('brand_id');
            $id       = $request->post('id');
            $result   = NewsLevelTemp::find()->where(['id' => $brand_id, 'id' => $id])->one();
            if (empty($result)) {
                API('', 300);
            }
            $type           = $result->type;
            $member_rank    = $result->member_rank;
            $ret            = Yii::$app->db->createCommand()->update('news_level_temp', ['is_set' => 0], ['type' => $type, 'member_rank' => $member_rank, 'brand_id' => $brand_id])->execute();
            $result->is_set = 1;
            $result->save();
            return $this->response('', 200, $ret);
        }
    }
    /**
     * 删除生日月优惠
     * @return [type] [description]
     */
    public function actionDelBirth()
    {
        if (Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->get('id');
            $brand_id = Yii::$app->session->get('brand_id');
            Discount::deleteAll(['brand_id' => $brand_id, 'news_id' => $id]);
            NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'id' => $id])->one()->delete();
            return $this->response('', 200);
        }
    }

    /**
     * 生日月优惠列表
     * @return [type] [description]
     */
    public function actionBirthList()
    {
        $request  = Yii::$app->request;
        $brand_id = Yii::$app->session->get('brand_id');
        $month    = json_encode(Yii::$app->params['month']);
        $brand    = Brand::getMemberRank();
        $new      = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        $brand_data = json_encode($new);
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
            $type   = $request->get('type');
            $count  = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => $type])->count();
            $data   = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => $type])->limit($limit)->offset($offset)->orderBy('type_children desc')->all();
            return $this->response(['total' => $count, 'rows' => $data]);
        }
        return $this->render('birth-list', ['brand_data' => $brand_data, 'month' => $month]);
    }

    public function getBirthVipNo($news_id)
    {
        $result = QueueService::getBirthVipNo($news_id);

        if ($result != false) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 会员等级vip_no
     * @param  [type] $news_id [description]
     * @return [type]          [description]
     */
    private function getVipNo($news_id)
    {
        $vip_no = QueueService::getVipNo($news_id);
        if ($vip_no != false) {
            return $vip_no;
        } else {
            return [];
        }
    }

    public function actionTest(){
        phpinfo();
    }

}
