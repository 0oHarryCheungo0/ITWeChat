<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <title>OFFER</title>
    <link rel="stylesheet" href="/public/css/it.style.css"/>
    <link rel="stylesheet" href="/public/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/fonts/iconfont.css"/>
    <link rel="stylesheet" href="/public/css/pageCss.css"/>
    <script src="/public/js/pace.min.js"></script>
</head>
<body>
<div class="bodyMain">
    <div class="offerContent">
        <div class="offerC-main">
            <div class="offerC-html">
                <img src="/public/images/banner_03.jpg">
                <div class="offerC-text">
                  
                    <h4 class="viph4"><?=$data['title']?></h4><div class="viphr">&nbsp;</div>
                    <div class="offercMain">
                    <div>
                    <p class="htmlTb" ></p>
                    <?=$data['content']?>
                    </div>
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
    var bh=document.documentElement.clientHeight;
    var viph=Number($('.singUp-bt').height())+80;
    var $mainBg=$('.offerContent');
    $mainBg.css({'height':(bh-viph)+'px'});
    $('.offercMain').css({'height':(bh-viph)*0.6+'px'});
      myScroll = new IScroll('.offercMain', {
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