<script>
    Pace.on('hide', function () {
        function loadImage(obj, url, callback) {
            var img = new Image();
            img.src = url;
            // 判断图片是否在缓存中
            if (img.complete) {
                callback.call(img, obj);
                return;
            }
            // 图片加载到浏览器的缓存中回调函数
            img.onload = function () {
                callback.call(img, obj);
            }
        }

        function showImage(obj) {
            obj.src = this.src;
        }

        var imgs = document.getElementById("vip02").getElementsByTagName("img");
        for (var i = 0; i < imgs.length; i++) {
            var url = imgs[i].dataset.src;
            loadImage(imgs[i], url, showImage);
        }
        chImg();
    });
</script>
<div class="bodyMain">
    <div style="display: none" id="vipImgPng">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00000.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00001.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00002.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00003.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00004.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00005.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00006.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00007.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00008.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00009.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00010.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00011.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00012.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00013.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00014.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00015.png">
        <img src="http://wechat.itezhop.com/public/semi-circle/semi-circle_00016.png">
    </div>
    <div class="mainBox">
        <div class="vipMain mainCss">
            <img src="http://wechat.itezhop.com/public/images/vipBg.jpg">
            <div class="vipBox">
                <div class="vipPadd">
                    <div class="vipCode">
                        <img src="http://wechat.itezhop.com/public/images/vip_c_03.png">
                    </div>
                    <div class="vipPass" id="vipPass">
                        <div class="left">
                            <img src="http://wechat.itezhop.com/public/images/vip_c_07.png" data-src='http://wechat.itezhop.com/public/images/vip_c_07.gif' id="vip07">
                            <div class="viptext"><?= Yii::t('app', $vip->vip_type) ?></div>
                            <div class="vipCimg"><img src="http://wechat.itezhop.com/public/images/vip_c_star.png"></div>
                        </div>
                        <div class="left" id="vip02">
                            <img src="http://wechat.itezhop.com/public/images/vip_c_10.png" data-src='http://wechat.itezhop.com/public/images/vip_c_100.gif'>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="vipNum">
                        <div class="vipNumMain">
                            <div class="left"><?= $vip->phone ?></div>
                            <div class="left"><?= $vip->vip_no ?></div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="vipInfoMain" style="display: none;">
                <div class="vipInfo">
                    <div class="vipPadd">
                        <div class="vipColse">
                            <img src="http://wechat.itezhop.com/public/images/close_03.jpg">
                        </div>
                        <div class="vipInfo-main">
                            <div class="vip-bar">
                                <img src="data:image/png;base64,<?=$barcode?>">
                                <div class="vip-bar-code">
                                    <hr>
<!--                                    <div class="bar-html"><a>2998 9435841 323</a></div>-->
                                </div>
                            </div>
                            <div class="vip-code">
                                <img src="http://wechat.itezhop.com/public/images/code_c_07.jpg">
                            </div>
                            <p class="vip-code-p">
                                <?= Yii::t('app', '入会时间') ?> <?= date('Y-m-d', strtotime($vip->join_date)) ?></p>
                        </div>
                    </div>
                    <div class="vipNum">
                        <div class="vipNumMain">
                            <div class="left"><?= $vip->phone ?></div>
                            <div class="left"><?= $vip->vip_no ?></div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vipmate">
            <div class="left">
                <div class="vipmate-left vipmate-main">
                    <a href="<?= $urls['profile'] ?>">
                        <img src="http://wechat.itezhop.com/public/images/vip_c_18.png">
                        <div class="scrollMain">
                            <p><?= Yii::t('app', '个人资料') ?></p>
                            <div class="right scrollText"><?= $profile_percent ?>%</div>
                            <div class="left scroll-left">
                                <div class="scrollBox">
                                    <div class="scroll-bg"></div>
                                    <div class="scroll" style="width:0%;"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </a>
                </div>
                <div class="vipmate-left">
                    <a href="<?= $urls['news'] ?>">
                        <img src="http://wechat.itezhop.com/public/images/vip_c_23.png">
                        <div class="newMain">
                            <p><?= Yii::t('app', '最新资讯') ?></p>
                            <?= $messages ?><?= Yii::t('app', '条未读消息') ?>
                        </div>
                        <div class="newNum"><img src="http://wechat.itezhop.com/public/images/ico_span.png"><span><?= $messages ?></span></div>
                    </a>
                </div>
            </div>
            <div class="left">
                <div class="integralMain">
                    <div class="int-box">
                        <a href="<?= $urls['token'] ?>">
                            <img src="http://wechat.itezhop.com/public/images/vip_c_15.png">
                            <div class="int-text">
                                <p class="int1"><?= Yii::t('app', '会员积分') ?></p>
                                <p class="int2">I.TOKEN</p>
                                <p class="int3"><?= intval($points['bonus']) ?></p>
                                <?= Yii::t('app', '30天内将要到期') ?>
                                <span><?= intval($points['exp_bonus']) ?></span><?= Yii::t('app', '积分') ?>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <ul class="main-nav">
        <li>
            <a href="<?= $urls['record'] ?>">
                <img src="http://wechat.itezhop.com/public/images/vip_c_26.png">
                <p><?= Yii::t('app', '消费记录'); ?></p>
            </a>
        </li>
        <li>
            <a href="<?= $urls['discount'] ?>">
                <img src="http://wechat.itezhop.com/public/images/vip_c_28.png">
                <p><?= Yii::t('app', '专属优惠'); ?></p>
            </a>
        </li>
        <li>
            <a href="<?= $urls['benefits']; ?>">
                <img src="http://wechat.itezhop.com/public/images/vip_c_33.png">
                <p><?= Yii::t('app', '会员权益'); ?></p>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="checkIn">
                <img src="http://wechat.itezhop.com/public/images/vip_c_30.png">
                <p><?= Yii::t('app', '签到打卡'); ?></p>
            </a>
        </li>
    </ul>
    <div class="mainBg"></div>
</div>
<script src="http://wechat.itezhop.com/public/js/common-p.js"></script>
<script>
    //计算高度
    var bh = document.body.scrollHeight;
    var viph = Number($('.vipMain').height()) + 15;
    var $mainBg = $('.mainBg');
    $mainBg.css({'height': (bh - viph) + 'px', 'top': viph + 'px'});
    //关闭code信息
    $(document).on('click', '#vipInfoMain .vipColse img', function () {
        vipMainCo()
    });
    $(document).on('click', '.mainBg', function () {
        vipMainCo()
    });
    function vipMainCo() {
        $mainBg.hide();
        var $vipInfoMain = $('#vipInfoMain');
        $vipInfoMain.addClass('vipInfoMain');
        setTimeout(function () {
            $vipInfoMain.hide();
            $vipInfoMain.removeClass('vipInfoMain');
        }, 1000);
    }
    //展开code信息
    $(document).on('click', '.vipMain .vipCode img', function () {
        $mainBg.show();
        var $vipInfoMain = $('#vipInfoMain');
        $vipInfoMain.addClass('vipInfoMain2');
        $vipInfoMain.show();
        setTimeout(function () {
            $vipInfoMain.removeClass('vipInfoMain2');
        }, 1000);
    });
    //签到
    $(document).on('click', '.checkIn', function () {
        $.ajax({
            url: "<?=$urls['check']?>",
            dataType: 'JSON',
            type: "POST",
            success: function (ret) {
                if (ret.code == 0) {
                    var integral = Number(ret.data.point);
                    var $int3 = $('.int3');
                    var int3 = (Number($int3.html()) + integral).toFixed(0);
                    $int3.html(int3);
                    alertHtml(ret.data.str);
                } else {
                    alertHtml(ret.msg);
                }
            }
        })

    });
    setTimeout(function () {
        var str = 0;
        var $scrollText = $('.scrollText');
        var end = parseFloat($scrollText.html().toString());
        var lt = setInterval(function () {
            str = str + 1;
            if (str > end || str > 100) {
                clearInterval(lt);
                return false;
            }
            $('.scroll').css('width', str + '%');
            $scrollText.html(str + '%')
        }, 50);
    }, 1000);
    var listImg = document.getElementById("vipImgPng").getElementsByTagName("img");
    var di = 0;
    var size = listImg.length;
    function chImg() {
        if (di < size) {
            $('#vip07').attr('src', listImg[di].src);
            di++;
            setTimeout(function () {
                chImg()
            }, 50);
        }
    }
</script>