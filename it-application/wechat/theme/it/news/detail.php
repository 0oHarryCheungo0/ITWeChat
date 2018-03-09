<?php use yii\helpers\Url; ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
    <meta content="telephone=no" name="format-detection"/>
    <title>news</title>
    <link rel="stylesheet" href="/public/css/it.style.css"/>
    <link rel="stylesheet" href="/public/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/fonts/iconfont.css"/>
    <link rel="stylesheet" href="/public/css/pageCss.css"/>
    <script src="/public/js/pace.min.js"></script>
</head>
<body>
<div class="bodyMain">
    <div class="newDeta">
        <div class="newDeta-main mainCss">
            <img src="/public/images/newhtml_03.png" class="newDetaImg">
            <div class="newDeta-html">
                <div class="newDeta-title">
                    <h4 class="viph4"><?= $data->title ?></h4>
                    <div class="viphr"></div>
                </div>
                <div class="newDeta-hide">
                    <div class="newDeta-body">
                        <?= $data->content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="singUp-bt">
        <img src="/public/images/bottom2.png">
    </div>
</div>
<script src="/public/js/jquery.min.js"></script>
<script src="/public/js/iscroll-probe.js"></script>
<script>
    function isPassive() {
        var supportsPassiveOption = false;
        try {
            addEventListener("test", null, Object.defineProperty({}, 'passive', {
                get: function () {
                    supportsPassiveOption = true;
                }
            }));
        } catch(e) {}
        return supportsPassiveOption;
    }
    Pace.on('hide', function(){
        var viph=80+Number($('.newDeta-title').outerHeight());
        var bh=Number(document.body.scrollHeight)*0.84;
        console.log(bh);
        console.log(viph);
        var $mainBg=$('.newDeta-hide');
        $mainBg.css({'height':(bh-viph)+'px'});
        myScroll = new IScroll('.newDeta-hide', {
            probeType:5, mouseWheel: true,click:true,taps:true,
            preventDefault: false,
            preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/}
        });
    });
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, isPassive() ? {
        capture: false,
        passive: false
    } : false);
</script>
</body>
</html>