<?php
namespace backend\service\staff;

use backend\models\Staff;
use Yii;
use yii\base\Model;

class StaffForm extends Model
{
    public $id;
    public $store_id;
    public $staff_name;
    public $staff_code;
    public $extra;
    public $qrcode;
    public $brand_id;
    public $key;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_name', 'staff_code', 'store_id'], 'required'],

            ['extra','string'],
            ['staff_code','unique', 'targetClass' => '\backend\models\Staff','on'=>'insert']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'staff_name'  => '员工姓名',
            'staff_code'  => '员工编号',
            'extra'       => '备注',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'store_id'    => '店铺名',
        ];
    }

    public function save($id)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        if (empty($id)) {
            //新增
            $staff             = new Staff();
            $staff->staff_name = $this->staff_name;
            $staff->staff_code = $this->staff_code;
            $staff->store_id   = $this->store_id;
            $staff->extra = $this->extra;
            $staff->user_id    = Yii::$app->user->id;
            $staff->qrcode     = $this->qrcode($brand_id, $this->staff_code);
            $staff->brand_id   = $brand_id;
            $staff->key        = $this->key;
            $staff->save();
            $staff_id = $staff->getOldPrimaryKey();
            \common\models\logic\StaffLogic::newStaff($this->store_id,$staff_id);
        } else {
            $staff = Staff::findOne($id);
            //编辑
            $staff->staff_name = $this->staff_name;
            $staff->staff_code = $this->staff_code;
            $staff->store_id   = $this->store_id;
            $staff->extra = $this->extra;
            $staff->brand_id   = $brand_id;
            // $staff->qrcode     = $this->qrcode($brand_id, $this->staff_code);
            // $staff->user_id    = Yii::$app->user->id;
            // $staff->key        = $this->key;
            $staff->save();

        }
    }

    /**
     * 生成店员二维码
     */
    public function qrcode($brand_id, $staff_code)
    {
        $time      = uniqid();
        $this->key = md5($this->getGuid().$staff_code.$brand_id);
        return \backend\service\wechat\functions\Qrcode::forever($brand_id, $this->key);
    }

    public function getGuid()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));

        $hyphen = chr(45); 
        $uuid   = substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12);
        return $uuid;
    }

}
