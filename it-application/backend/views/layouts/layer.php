<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>" media="all">
    <script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
    <script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>

</head>
<body>
<style>
    .layui-form-label {
        width: 150px
    }

    legend {
        width: auto;
        border-bottom: none;
        margin-bottom: 0px;
    }

    .layui-layer-title {
        background: #09c;
        color: #FFF;
        border: none;
    }
</style>
<?= $content ?>
</body>
</html>