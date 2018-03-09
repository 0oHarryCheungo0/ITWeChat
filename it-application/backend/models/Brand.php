<?php
namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

use \yii\db\ActiveRecord;


class Brand extends ActiveRecord
{

    /**
     * 附加行为
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],

            ],
        ];
    }

    public static function tableName()
    {
        return 'brand';
    }

    public function getAdminUsers()
    {
        return $this->hasMany(AdminUser::className(), ['brand_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(ParentBrand::className(), ['id' => 'p_id']);
    }

    public static function findWxById($brand_id)
    {
        return static::findOne($brand_id);//更具brand id查找一条数据
    }

    public static function getAll()
    {
        return static::find()->select(['id', 'brand_name'])->all();
    }

    public static function getMemberRank($brand_id = 0)
    {
        if (empty($brand_id)) {
            $brand_id = Yii::$app->session->get('brand_id');
        }

        $one = static::find()->select(['rank'])->where(['id' => $brand_id])->one();
        if (!empty($one['rank'])) {
            $data = json_decode($one['rank'], true);
            return $data;
        } else {
            return false;
        }

    }

}
