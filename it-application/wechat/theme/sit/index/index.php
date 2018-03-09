<script src="http://wechat.itezhop.com/public/sit/js/pace.min.js"></script>
<div class="bodyMain">
    <div class="mainBox">
        <div class="vipMain mainCss">
            <img src="http://wechat.itezhop.com/public/sit/images/vipBg.jpg">
            <div class="vipBox">
                <div class="vipPadd">
                    <div class="vipCode">
                        <img src="http://wechat.itezhop.com/public/sit/images/vip_c_03.png">
                    </div>
                    <div class="vipPass" id="vipPass">
                        <div class="left">
                            <img src="http://wechat.itezhop.com/public/sit/images/vip_c_07.gif" class="" id="vip07">
                            <div class="viptext"><?= Yii::t('app', $vip->vip_type) ?></div>
                        </div>
                        <div class="left">
                            <img src="http://wechat.itezhop.com/public/sit/images/vip_c_10.gif" id="vip02">
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
                            <img src="http://wechat.itezhop.com/public/sit/images/close_03.jpg">
                        </div>
                        <div class="vipInfo-main">
                            <div class="vip-bar">
                                <img src="data:image/png;base64,<?php echo $barcode; ?>">
                                <div class="vip-bar-code">
                                    <hr>
                                </div>
                            </div>
                            <div class="vip-code">
                                <img src="http://wechat.itezhop.com/public/sit/images/code_c_07.jpg">
                            </div>
                            <p class="vip-code-p"><?= Yii::t('app', '入会时间'); ?> <?= date('Y-m-d', strtotime($vip->join_date)) ?></p>
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
                <a href="<?= $urls['profile'] ?>">
                    <div class="vipmate-left vipmate-main">
                        <img src="http://wechat.itezhop.com/public/sit/images/vip_c_18.png">
                        <div class="scrollMain">
                            <h4>PERSONAL</h4>
                            <p><?= Yii::t('app', '个人资料'); ?></p>
                            <div class="right scrollText"><?= $profile_percent ?>%</div>
                            <div class="left scroll-left">
                                <div class="scrollBox">
                                    <div class="scroll-bg"></div>
                                    <div class="scroll" style="width: 50%;"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </a>
                <a href="<?= $urls['news'] ?>">
                    <div class="vipmate-left vipmate-main2">

                        <img src="http://wechat.itezhop.com/public/sit/images/vip_c_23.png" style="width: 22%; margin-left:8%;">
                        <div class="newMain">
                            <h4>NEWS</h4>
                            <p><?= Yii::t('app', '最新资讯'); ?></p>
                            <?= $messages ?>条未读信息
                        </div>
                        <div class="newNum"><?= $messages ?></div>
                        <div class="clear"></div>
                    </div>
                </a>
            </div>
            <div class="left">
                <div class="integralMain">
                    <div class="int-box">
                        <a href="<?= $urls['token'] ?>">
                            <div class="integral-img">
                                <img src="http://wechat.itezhop.com/public/sit/images/vip_c_15.png">
                                <div class="integral-text">
                                    <h4>INTEGRAL</h4>
                                    <p><?= Yii::t('app', '会员积分'); ?></p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="int-text">
                                <h4>I.TOKEN</h4>
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
                <img src="http://wechat.itezhop.com/public/sit/images/vip_c_26.png">
                <h4>RECORD</h4>
                <p><?= Yii::t('app', '消费记录'); ?></p>
            </a>
        </li>
        <li>
            <a href="<?= $urls['discount'] ?>">
                <img src="http://wechat.itezhop.com/public/sit/images/vip_c_28.png">
                <h4>OFFER</h4>
                <p><?= Yii::t('app', '专属优惠'); ?></p>
            </a>
        </li>
        <li>
            <a href="<?= $urls['benefits']; ?>">
                <img src="http://wechat.itezhop.com/public/sit/images/vip_c_30.png">
                <h4>BENEFITS</h4>
                <p><?= Yii::t('app', '会员权益'); ?></p>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="checkIn">
                <img src="http://wechat.itezhop.com/public/sit/images/vip_c_33.png">
                <h4>CHECK IN</h4>
                <p><?= Yii::t('app', '签到打卡'); ?></p>
            </a>
        </li>
    </ul>
    <div class="mainBg"></div>
</div>
<script src="http://wechat.itezhop.com/public/sit/js/common-p.js"></script>
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
            str = str + 2;
            if (str > end || str > 100) {
                clearInterval(lt);
                return false;
            }
            $('.scroll').css('width', str + '%');
            $scrollText.html(str + '%')
        }, 50);
    }, 1000)
</script>