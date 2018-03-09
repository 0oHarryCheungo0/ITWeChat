<?php 
namespace common\models;

use yii\db\ActiveRecord;

class UserCache extends ActiveRecord{
	public static function tableName(){
		return 'user_cache';
	}
}