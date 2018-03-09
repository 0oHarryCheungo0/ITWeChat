<?php
namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use yii\db\ActiveRecord;

class Store extends ActiveRecord
{
    /**
     * 表名
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * 附加行为
     */
    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],

            ],
        ];
    }

    public function getStaffs()
    {
        return $this->hasMany(Staff::className(), ['store_id' => 'id']);
    }

    public function getRelationMember(){
        return $this->hasMany(\common\models\MemberRelation::className(),['store_id'=>'id']);
    }

      public function getMember()
    {
        return $this->hasMany(WechatVip::className(), ['wechat_user_id' => 'member_id'])
            ->viaTable('member_relation', ['store_id' => 'id']);
    }


    /**
     * 通过主键删除
     */
    public static function deleteById($id)
    {
        return static::findOne($id)->delete();
    }

    /**
     * 通过主键查找是否存在
     */
    public static function findById($id)
    {
        $uid = Yii::$app->session->get('brand_id');

        return static::findOne(['id' => $id, 'brand_id' => $uid]);
    }

    /**
     * 插入前事件，在StorForm附加
     */
    public function beforeInsert()
    {
        $this->user_id  = Yii::$app->user->id;
        $this->brand_id = Yii::$app->session->get('brand_id');
    }

    /**
     * 查找当前品牌(brand_id)下的所有店铺
     */
    public static function findAllByBrand()
    {
        return static::find()->where(['brand_id' => Yii::$app->session->get('brand_id')])->all();
    }

    public function afterFind()
    {
        $this->create_time = date('Y-m-d', $this->create_time);
    }

}
