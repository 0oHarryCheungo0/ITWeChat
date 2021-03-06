<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <title>NEWS</title>
    <link rel="stylesheet" href="/public/sit/css/it.style.css"/>
    <link rel="stylesheet" href="/public/sit/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/sit/fonts/iconfont.css"/>
    <link rel="stylesheet" href="/public/sit/css/pageCss.css"/>
    <script src="/public/sit/js/pace.min.js"></script>
</head>
<body>
<link rel="stylesheet" href="/public/sit/css/newCss3.css"/>
<div class="bodyMain">
<div class="newDeta">
    <div class="newDeta-main mainCss">
        <img src="/public/sit/images/newhtml3_03.png" class="newDetaImg">
        <div class="newDeta-html">
            <div class="newDeta-title">
                <h4 class="viph4"><?=$data['title']?></h4>
                <div class="viphr"></div>
            </div>
            <div class="newDeta-hide">
                <div class="newDeta-body">
                 <?=$data['content']?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="singUp-bt">
    <img src="/public/sit/images/bottom.png">
</div>
</div>
<script src="/public/sit/js/jquery.min.js"></script>
<script>
    Pace.on('hide', function(){
        var viph=80+Number($('.newDeta-title').outerHeight());
        var bh=Number(document.body.scrollHeight)*0.8;
        console.log(bh);
        console.log(viph);
        var $mainBg=$('.newDeta-hide');
        $mainBg.css({'height':(bh-viph)+'px'});
    });
</script>
</body>
</html>