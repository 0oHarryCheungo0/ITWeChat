<?php
namespace backend\components;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public $imageFile;
    public $url;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function upload()
    {
        $name = date("Ymd");
        if ($this->validate()) {
            if (!file_exists('uploads/keyword/')) {
                mkdir("uploads/keyword/", 0777, true);
            }
            $this->imageFile->saveAs('uploads/keyword/' . $name . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->url = 'uploads/keyword/' . $name . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            return true;
        } else {
            return false;
        }
    }
}