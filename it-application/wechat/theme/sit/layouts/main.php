<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <title><?=Html::encode($this->title)?></title>
    <link rel="stylesheet" href="http://wechat.itezhop.com/public/sit/css/it.style.css"/>
    <link rel="stylesheet" href="http://wechat.itezhop.com/public/sit/fonts/font.style.css"/>
    <link rel="stylesheet" href="http://wechat.itezhop.com/public/sit/fonts/iconfont.css"/>
    <link rel="stylesheet" href="http://wechat.itezhop.com/public/sit/css/pageCss.css"/>
    <script src="http://wechat.itezhop.com/public/js/pace.min.js"></script>
    <script src="http://wechat.itezhop.com/public/sit/js/jquery.min.js"></script>
</head>
<body>
<?=$content?>
</body>
</html>