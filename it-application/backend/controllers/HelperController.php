<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/26
 * Time: 下午5:19
 */

namespace backend\controllers;


use yii\helpers\FileHelper;
use yii;

class HelperController extends AdminBaseController
{
    public function actionImage()
    {
        $targetFolder = Yii::$app->basePath . '/web/uploads/' . date('Y/md');
        $file = new FileHelper();
        $file->createDirectory($targetFolder);
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $fileParts = pathinfo($_FILES['file']['name']);
            $extension = $fileParts['extension'];
            $random = time() . rand(1000, 9999);
            $randName = $random . "." . $extension;
            $targetFile = rtrim($targetFolder, '/') . '/' . $randName;
            $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
            $uploadfile_path = 'uploads/' . date('Y/md') . '/' . $randName;
            $callback['url'] = $uploadfile_path;
            $callback['filename'] = $fileParts['filename'];
            $callback['randName'] = $random;
            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
                $file = Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . "/" . $uploadfile_path;
                return $this->response($file);
            } else {
                return $this->response('', 500, '不能上传后缀为' . $fileParts['extension'] . '文件');
            }
        } else {
            return $this->response('', 501, '没有上传文件');
        }
    }

    public function actionExcel()
    {
        $targetFolder = Yii::$app->basePath . '/../excel/' . date('Y/md');
        $file = new FileHelper();
        $file->createDirectory($targetFolder);
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $fileParts = pathinfo($_FILES['file']['name']);
            $extension = $fileParts['extension'];
            $random = time() . rand(1000, 9999);
            $randName = $random . "." . $extension;
            $targetFile = rtrim($targetFolder, '/') . '/' . $randName;
            $fileTypes = array('xls', 'xlsx');
            $file_path = date('Y/md') . "/" . $randName;
            if (in_array($fileParts['extension'], $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
                return $this->response($file_path);
            } else {
                return $this->response('', 500, '不能上传后缀为' . $fileParts['extension'] . '文件');
            }
        } else {
            return $this->response('', 501, '没有上传文件');
        }
    }

    public function actionEditor()
    {
        $action = Yii::$app->request->get('action');
        switch ($action) {
            case 'config':
                $params = [
                    "imageActionName" => "uploadimage", /* 执行上传图片的action名称 */
                    "imageFieldName" => "file", /* 提交的图片表单名称 */
                    "imageMaxSize" => 2048000, /* 上传大小限制，单位B */
                    "imageAllowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
                    "imageCompressEnable" => true, /* 是否压缩图片,默认是true */
                    "imageCompressBorder" => 1600, /* 图片压缩最长边限制 */
                    "imageInsertAlign" => "none", /* 插入的图片浮动方式 */
                    "imageUrlPrefix" => "", /* 图片访问路径前缀 */
                ];
                echo json_encode($params);
                break;
            case 'uploadimage':
                $targetFolder = Yii::$app->basePath . '/web/uploads/images/' . date('Y/md');
                $file = new FileHelper();
                $file->createDirectory($targetFolder);
                if (!empty($_FILES)) {
                    $tempFile = $_FILES['file']['tmp_name'];
                    $fileParts = pathinfo($_FILES['file']['name']);
                    $extension = $fileParts['extension'];
                    $random = time() . rand(1000, 9999);
                    $randName = $random . "." . $extension;
                    $targetFile = rtrim($targetFolder, '/') . '/' . $randName;
                    $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
                    $uploadfile_path = 'uploads/images/' . date('Y/md') . '/' . $randName;
                    if (in_array($fileParts['extension'], $fileTypes)) {
                        move_uploaded_file($tempFile, $targetFile);
                        $file = Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . "/" . $uploadfile_path;
                        $params = array(
                            "state" => 'SUCCESS',
                            "url" => $file,
                            "title" => $_FILES['file']['name'],
                            "original" => $randName,
                            "type" => $extension,
                        );
                        echo json_encode($params);
                    } else {
                        return $this->response('', 500, '不能上传后缀为' . $fileParts['extension'] . '文件');
                    }
                } else {
                    return $this->response('', 501, '没有上传文件');
                };

                break;
        }
    }

}