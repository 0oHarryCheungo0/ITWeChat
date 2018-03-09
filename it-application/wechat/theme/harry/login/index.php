<link rel="stylesheet" href="http://wechat.itezhop.com/public/css/swiper-3.4.2.min.css">
<div class="bodyMain" style="background: #fff;">
    <div class="swiper-container">
        <div class="swiper-wrapper">

            <?php
            foreach ($config['slider'] as $s) {
                ?>
                <div class="swiper-slide"><img src="<?= $s['image'] ?>"></div>
                <?php
            }
            ?>
        </div>
        <!-- 如果需要分页器 -->
    </div>
    <div class="mainbg">
        <div class="mainBox signUp">
            <div class="fontPick">
                <a href="javascript:void(0)" class="<?php if ($config['lang'] == 'cn') {
                    echo 'active';
                } ?>">简体中文</a>|
                <a href="javascript:void(0)" class="<?php if ($config['lang'] == 'hk') {
                    echo 'active';
                } ?>">繁体中文</a>
            </div>
            <div class="personalMain noPadd per_e5508b">
                <div class="select">
                    <div class="select-ico"><span class="iconfont icon-select"></span></div>
                    <select class="weui-select" id="area">
                        <option value="中国" selected><?=Yii::t('app','大陆地区')?></option>
                        <option value="香港"><?=Yii::t('app','香港地区')?></option>
                        <option value="澳门"><?=Yii::t('app','澳门地区')?></option>
                        <option value="台湾"><?=Yii::t('app','台湾地区')?></option>
                    </select>
                </div>
                <div class="clear"></div>
            </div>
            <div class="signUp-tel sing_f3c920">
                <div class="telMain">
                    <div class="left">+86</div>
                    <div class="left">
                        <input type="tel" id="tel" placeholder="<?=Yii::t('app','请输入手机号码')?>">
                    </div>
                    <div class="left">
                        <span class="iconfont icon-colse"></span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="singUp-code">
                <div class="left">
                    <input type="number" id="code" placeholder="<?=Yii::t('app','请输入验证码')?>">
                </div>
                <div class="left">
                    <a href="javascript:void(0)" class="codeHtml"><?=Yii::t('app','获取验证码')?></a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ajaxbtn noPadd">
                <a href="javascript:void(0)"><?=Yii::t('app','注册/绑定会员卡')?></a>
            </div>
            <div class="singUp-info">
                <?=Yii::t('app','注册或绑定成功后，代表您已默认订阅I.T品牌资讯和商品信息')?>

            </div>
        </div>
        <div class="singUp-bt">
            <img src="http://wechat.itezhop.com/public/images/bottom.png">
        </div>

    </div>

</div>
<script src="http://wechat.itezhop.com/public/js/swiper-3.4.2.jquery.min.js"></script>
<script src="http://wechat.itezhop.com/public/js/common-p.js"></script>
<script src="http://wechat.itezhop.com/public/js/cookie.js"></script>
<script>
    //注册或绑定会员卡
    var signUpUrl = '<?=$config["login_url"]?>';
    //获取验证码
    var codeUrl = '<?=$config["code_url"]?>';

    var vipHome = '<?=$config["vip_home"]?>';

    var regUrl = '<?=$config["reg_url"]?>';

    var fontUrl = '<?=$config['lang_url']?>';

    var need_area = "<?=Yii::t('app','请选择地区')?>";

    var need_code = "<?=Yii::t('app','请输入验证码')?>";

    var requesting = "<?=Yii::t('app','正在处理，请稍候')?>";

    var getcode = "<?=Yii::t('app','获取验证码')?>";

    var code_ok = "<?=Yii::t('app','验证码已发送到您的手机，请注意查收')?>";

    var right_phone = "<?=Yii::t('app','请输入正确的手机号码')?>";
    //滑动banner
    Pace.on('hide', function () {
        var mySwiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            touchRatio: 0.5,
            initialSlide: 0,
            loop: true,
            autoplay: 2000,
            grabCursor: true,
            paginationClickable: true
        });
    })
</script>
<script src="http://wechat.itezhop.com/public/js/signUp-p.js"></script>