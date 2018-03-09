<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <title>Thank</title>
    <link rel="stylesheet" href="/public/css/it.style.css"/>
    <link rel="stylesheet" href="/public/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/fonts/iconfont.css"/>
</head>
<body>
<div class="bodyMain">
    <div class="thank_tou">
        <img src="/public/images/vip_c_10.png">
    </div>
    <?php
    if ($flag == false) {
        ?>
        <div class="thank_text">非常感谢, 您已完成此次服务评价.<br>现在注册/绑定会员还有额外积分奖励哦〜</div>
        <div class="thank_btn">
            <a href="<?=$url?>">注册/绑定会员卡</a>
        </div>
        <?php
    } else {
        ?>
        <div class="thank_text">非常感谢, 您已完成此次服务评价.</div>
        <?php
    }
    ?>

    <div class="singUp-bt">
        <img src="/public/images/bottom2.png">
    </div>
</div>
<script>
    flag = "<?=$flag == true ? 1 : 0?>";
    url = "<?=$url?>"
    if (flag == '1'){
        setTimeout("location.href='" + url + "'", 3000);
    }
</script>
</body>
</html>