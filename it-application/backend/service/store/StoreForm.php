<?php

namespace backend\service\store;

use backend\models\Store;
use backend\service\BaseModel;

class StoreForm extends BaseModel
{
    public $id;
    public $store_name;
    public $address;
    public $store_code;
    public $tel;
    public $province;
    public $city;
    public $area;
    public $extra;
    public $search = '';
    public $page   = 0;
    public $s_time;
    public $e_time;

    public function rules()
    {
        return [
            [['store_name', 'store_code', 'tel', 'province', 'city', 'area', 'address'], 'required'],
            ['store_name', 'string', 'min' => 3, 'max' => 55],
            ['store_code', 'string', 'max' => 20],
            ['store_code', 'unique', 'targetClass' => 'backend\models\Store','on'=>'insert'],
            ['tel', 'string'],
            ['city', 'string', 'min' => 1, 'max' => 20],
            ['address', 'string', 'max' => 255],
           
        ];
    }

    public function save()
    {
        if (!empty($this->id)) {
            $store = Store::findOne($this->id);
        } else {
            $store = new Store();
            //注册事件
            \yii\base\Component::on(\yii\db\ActiveRecord::EVENT_BEFORE_INSERT, [$store, 'beforeInsert']);
            \yii\base\Component::trigger(\yii\db\ActiveRecord::EVENT_BEFORE_INSERT);
        }

        $store->store_name = $this->store_name;
        $store->store_code = $this->store_code;
        $store->tel        = $this->tel;
        $store->province   = $this->province;
        \Yii::info('省级为'.$this->province);
        $store->create_time = time();
        $store->city       = $this->city;
        $store->area       = $this->area;
        $store->address    = $this->address;
        $store->extra      = $this->extra;

        if ($store->save()) {
            return true;
        } else {
            return false;
        }

    }

    public function delete($id)
    {

    }

    public function attributeLabels()
    {
        return [
            'store_name' => '店铺名',
            'address'    => '店铺地址',
            'tel'        => '电话号码',
            'city'       => '城市',
            'address'    => '详细地址',
            'extra'      => '备注',
        ];
    }

}
