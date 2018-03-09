<?php 
namespace backend\service\store;

use yii\base\Model;
use yii\web\UploadedFile;

class ImportForm extends Model{
	public $file;

	public function rules(){
		return [
			[['file'],'file']
		];
	}
}