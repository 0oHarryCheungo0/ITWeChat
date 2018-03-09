<div class="bodyMain">
    <div class="mainBox">
        <div class="vipMain mainCss">
            <img src="/public/images/vipBg.jpg">
            <div class="vipBox">
                <div class="vipPadd">
                    <div class="vipCode">
                        <img src="/public/images/vip_c_03.png">
                    </div>
                    <div class="vipPass">
                        <div class="left">
                            <img src="/public/images/vip_c_07.gif" class="" id="vip07">
                            <div class="viptext"><?= $vip->vip_type ?></div>
                        </div>
                        <div class="left">
                            <img src="/public/images/vip_c_100.gif" id="vip02">
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
                            <img src="/public/images/close_03.jpg">
                        </div>
                        <div class="vipInfo-main">
                            <div class="vip-bar">
                                <img src="data:image/png;base64,<?php echo $barcode; ?>">
                                <div class="vip-bar-code">
                                    <hr>
                                    <div class="bar-html"><a>2998 9435841 323</a></div>
                                </div>
                            </div>
                            <div class="vip-code">
                                <img src="/public/images/code_c_07.jpg">
                            </div>
                            <p class="vip-code-p">入会时间 <?= date('Y-m-d', strtotime($vip->join_date)) ?></p>
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
                        <img src="/public/images/vip_c_18.png">
                        <div class="scrollMain">
                            <p>个人资料</p>
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
                        <img src="/public/images/vip_c_23.png">
                        <div class="newMain">
                            <p>最新资讯</p>
                            <?= $messages ?>条未读信息
                        </div>
                        <div class="newNum"><img src="/public/images/ico_span.png"><span><?= $messages ?></span></div>
                    </a>
                </div>
            </div>
            <div class="left">
                <div class="integralMain">
                    <div class="int-box">
                        <a href="<?= $urls['token'] ?>">
                            <img src="/public/images/vip_c_15.png">
                            <div class="int-text">
                                <p class="int1">会员积分</p>
                                <p class="int2">I.TOKEN</p>
                                <p class="int3"><?= $points['bp'] ?></p>
                                <?php
                                if ($points['exp_in_3month'] != 0) {
                                    ?>
                                    3月内到期积分<span><?= $points['exp_in_3month'] ?></span>积分
                                    <?php
                                }
                                ?>
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
                <img src="/public/images/vip_c_26.png">
                <p>消费记录</p>
            </a>
        </li>
        <li>
            <a href="<?= $urls['discount'] ?>">
                <img src="/public/images/vip_c_28.png">
                <p>专属优惠</p>
            </a>
        </li>
        <li>
            <a href="<?= $urls['benefits']; ?>">
                <img src="/public/images/vip_c_33.png">
                <p>会员权益</p>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="checkIn">
                <img src="/public/images/vip_c_30.png">
                <p>签到打卡</p>
            </a>
        </li>
    </ul>
    <div class="mainBg"></div>
</div>
<script src="/public/js/jquery.min.js"></script>
<script src="/public/js/common-p.js"></script>
<script>
    //计算高度
    var bh = document.body.scrollHeight;
    var viph = Number($('.vipMain').height()) + 15;
    var $mainBg = $('.mainBg');
    $mainBg.css({'height': (bh - viph) + 'px', 'top': viph + 'px'});
    //关闭code信息
    $(document).on('click', '#vipInfoMain .vipColse img', function () {
        $mainBg.hide();
        var $vipInfoMain = $('#vipInfoMain');
        $vipInfoMain.addClass('vipInfoMain');
        setTimeout(function () {
            $vipInfoMain.hide();
            $vipInfoMain.removeClass('vipInfoMain');
        }, 1000);
    });
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
                    alertHtml('签到成功!');
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
        console.log(end);
        var lt = setInterval(function () {
            str = str + 1;
            if (str > end || str > 100) {
                clearInterval(lt);
                return false;
            }
            $('.scroll').css('width', str + '%');
            $scrollText.html(str + '%')
        }, 50);
    }, 1000)
</script>