<?php 
namespace common\models;

use yii\db\ActiveRecord;

class Sms extends ActiveRecord{
	public static function tableName(){
		return 'sms';
	}
}