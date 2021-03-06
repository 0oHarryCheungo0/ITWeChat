<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <title>OFFER</title>
    <link rel="stylesheet" href="/public/sit/css/it.style.css"/>
    <link rel="stylesheet" href="/public/sit/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/sit/fonts/iconfont.css"/>
    <link rel="stylesheet" href="/public/sit/css/pageCss.css"/>
    <script src="/public/sit/js/pace.min.js"></script>
</head>
<body>
<div class="bodyMain">
    <div class="offerMBG">
        <div class="offerContent">
            <div class="offerC-main">
                <div class="offerC-html">
                    <img src="/public/sit/images/banner_03.jpg" class="imgh">
                    <div class="offerC-text">
                        <h4 class="viph4"><?=$data['title']?></h4><div class="viphr">&nbsp;</div>
                        <div class="mainContent">
                            <div>
                         <?=$data['content']?>
                     </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="singUp-bt">
            <img src="/public/sit/images/bottom.png">
        </div>
    </div>
</div>
<script src="/public/sit/js/jquery.min.js"></script>
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
    var bh=document.documentElement.clientHeight;
    var viph=Number($('.singUp-bt').height())+80+Number($('.imgh').height())+Number($('.viph4').height())+30;
    var $mainBg=$('.mainContent');
    $mainBg.css({'height':(bh-viph)+'px'});
        myScroll = new IScroll('.mainContent', {
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