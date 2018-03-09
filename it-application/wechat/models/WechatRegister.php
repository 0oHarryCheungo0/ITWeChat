<?php
/**
 * Created by PhpStorm.
 * User: xiaomu
 * Date: 17/11/27
 * Time: 上午9:48
 */
namespace wechat\models;

use yii\db\ActiveRecord;

class WechatRegister extends ActiveRecord{
    public static function tableName(){
        return 'wechat_register';
    }
}