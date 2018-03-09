<?php

use yii\helpers\Url;

$base = Url::home();
?>
<div style="background-color: #000">
    <div class="mainBox mainbg">
        <div class="mainCss">
            <img src="/public/images/top_03.jpg">
        </div>

        <div class="personal">
            <div class="personalMain personalInput">
                <div class="left" style="width:44%;padding-right:12%;">
                    <p><?= Yii::t('app', '名字') ?><span>必填</span></p>
                    <input class="weui-input" type="text" id="name" placeholder="<?= Yii::t('app', '请输入名字'); ?>"
                           value="<?= $vip['name_first'] ?>">
                </div>
                <div class="left" style="width:44%;">
                    <p><?= Yii::t('app', '姓氏') ?><span>必填</span></p>
                    <input class="weui-input" type="text" id="nameTwo" placeholder="<?= Yii::t('app', '请输入姓氏'); ?>"
                           value="<?= $vip['name_last'] ?>">
                </div>
                <div class="clear"></div>
            </div>
            <div class="personalMain">
                <p><?= Yii::t('app', '性别') ?><span>必填</span></p>
                <div class="select">
                    <div class="select-ico"><span class="iconfont icon-select"></span></div>
                    <select class="weui-select" id="sex">
                        <option value="0" <?php if ($vip['sex'] == 0) {
                            echo 'selected=""';
                        }; ?>>女
                        </option>
                        <option value="1" <?php if ($vip['sex'] == 1) {
                            echo 'selected=""';
                        }; ?>>男
                        </option>
                    </select>
                </div>
            </div>
            <div class="personalMain">
                <p><?= Yii::t('app', '会员级别') ?></p>
                <input class="weui-input" id="level" type="text" placeholder="" disabled=""
                       value="<?= $vip['vip_type'] ?>,有效期至<?= date('Y/m/d', strtotime($vip['exp_date'])); ?>">
            </div>
            <div class="personalMain">
                <p><?= Yii::t('app', '出生日期') ?><span><?= Yii::t('app', '生日月可获惊喜礼遇，一旦提交，不可修改'); ?></span></p>

                <?php
                if (strtotime($vip['birthday']) == strtotime('2017-01-01')) {
                    ?>
                    <div class="select">
                        <div class="select-ico"><span class="iconfont icon-select"></span></div>
                        <input type="date" class="inputMain" id="birthday" placeholder="请选择出生日期"
                               value="<?= date('Y-m-d', strtotime($vip['birthday'])) ?>">
                    </div>
                    <?php
                } else {
                    ?>
                    <input class="weui-input" id="birthday" value="<?= date('Y-m-d', strtotime($vip['birthday'])) ?>"
                           disabled="">
                    <?php
                }
                ?>


            </div>
            <div class="personalMain">
                <p><?= Yii::t('app', '手机号码') ?></p>
                <input class="weui-input" type="tel" id="tel" placeholder="<?= Yii::t('app', '请输入手机号码') ?>"
                       value='<?= $vip['phone'] ?>' disabled>
            </div>
            <div class="personalMain">
                <p><?= Yii::t('app', '电子邮箱') ?><span>必填</span></p>
                <input class="weui-input" type="email" id="email" placeholder="<?= Yii::t('app', '请输入邮箱地址') ?>"
                       value='<?= $vip['email'] ?>'>
            </div>
            <div class="p_t"
                 style="padding-left: 10px"><?= Yii::t('app', '邀请您填写更多个人信息，以便我们为您提供个性化服务，更可获赠I.T会员积分。') ?></div>
            <div class="personalBox personalMain">
                <div class="distpicker per_e5508b">
                    <p><?= Yii::t('app', '地址') ?></p>
                    <div class="left" style="width: 20%;">
                        <div class="select">
                            <div class="select-ico"><span class="iconfont icon-select"></span></div>
                            <select id="area">
                                <option value="">区域</option>
                                <option value="大陆" <?php if ($vip->info->area == '大陆') echo 'selected' ?>>大陆</option>
                                <option value="香港" <?php if ($vip->info->area == '香港') echo 'selected' ?>>香港</option>
                                <option value="澳门" <?php if ($vip->info->area == '澳门') echo 'selected' ?>>澳门</option>
                                <option value="台湾" <?php if ($vip->info->area == '台湾') echo 'selected' ?>>台湾</option>
                            </select>
                        </div>
                    </div>
                    <div id="distpicker" class="left" style="width:80%;">
                        <div class="select">
                            <div class="select-ico"><span class="iconfont icon-select"></span></div>
                            <select id="province">
                            </select>
                        </div>
                        <div class="select">
                            <div class="select-ico"><span class="iconfont icon-select"></span></div>
                            <select id="city"></select>
                        </div>
                        <div class="select">
                            <div class="select-ico"><span class="iconfont icon-select"></span></div>
                            <select id="town"></select>
                        </div>
                    </div>
                    <input class="weui-input" type="text" id="address" placeholder="详细地址"
                           value="<?= $vip['info']['addr1'] ?>">
                    <div class="clear"></div>
                </div>
                <p><?= Yii::t('app', '婚姻状况') ?></p>
                <div class="wedding">
                    <div class="left">
                        <input type="radio" name="wedding" value="1" <?php if ($vip['info']['marriage'] == 1) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '已婚') ?>
                    </div>
                    <div class="left">
                        <input type="radio" name="wedding" value="2" <?php if ($vip['info']['marriage'] == 2) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '未婚') ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="personalMain noPadd">
                    <p><?= Yii::t('app', '年收入') ?><span></span></p>
                    <div class="select">
                        <div class="select-ico"><span class="iconfont icon-select"></span></div>
                        <select class="weui-select" id="income">
                            <option value="" selected><?= Yii::t('app', '请选择收入范围') ?></option>
                            <option value="0" <?php if ($vip['info']['income'] === 0) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '10万以下') ?></option>
                            <option value="1" <?php if ($vip['info']['income'] == 1) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '10-20万') ?></option>
                            <option value="2" <?php if ($vip['info']['income'] == 2) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '20-50万') ?></option>
                            <option value="3" <?php if ($vip['info']['income'] == 3) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '50-100万') ?></option>
                            <option value="4" <?php if ($vip['info']['income'] == 4) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '100万以上') ?></option>
                        </select>
                    </div>
                </div>
                <div class="personalMain noPadd">
                    <p><?= Yii::t('app', '职业') ?><span></span></p>
                    <div class="select">
                        <div class="select-ico"><span class="iconfont icon-select"></span></div>
                        <select class="weui-select" id="career">
                            <option value="" selected><?= Yii::t('app', '请选择职业') ?></option>
                            <option value="0" <?php if ($vip['info']['career'] === 0) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '企业高管/私营企业主') ?></option>
                            <option value="1" <?php if ($vip['info']['career'] == 1) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '公司职员') ?></option>
                            <option value="2" <?php if ($vip['info']['career'] == 2) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '自雇人士') ?></option>
                            <option value="3" <?php if ($vip['info']['career'] == 3) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '家庭主妇') ?></option>
                            <option value="4" <?php if ($vip['info']['career'] == 4) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '学生') ?></option>
                            <option value="5" <?php if ($vip['info']['career'] == 5) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '退休人士') ?></option>
                            <option value="6" <?php if ($vip['info']['career'] == 6) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '其他') ?></option>
                        </select>
                    </div>
                </div>
                <div class="personalMain noPadd">
                    <p><?= Yii::t('app', '教育程度') ?><span></span></p>
                    <div class="select">
                        <div class="select-ico"><span class="iconfont icon-select"></span></div>
                        <select class="weui-select" id="education">
                            <option value="" selected><?= Yii::t('app', '请选择教育程度') ?></option>
                            <option value="0" <?php if ($vip['info']['education'] === 0) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '初中及以下') ?></option>
                            <option value="1" <?php if ($vip['info']['education'] == 1) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '高中/中技/中专') ?></option>
                            <option value="2" <?php if ($vip['info']['education'] == 2) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '大专') ?></option>
                            <option value="3" <?php if ($vip['info']['education'] == 3) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '本科') ?></option>
                            <option value="4" <?php if ($vip['info']['education'] == 4) {
                                echo 'selected';
                            } ?>><?= Yii::t('app', '硕士及以上') ?></option>
                        </select>
                    </div>
                </div>
                <p><?= Yii::t('app', '兴趣爱好') ?><span><?= Yii::t('app', '可多选') ?></span></p>
                <div class="wedding interest">
                    <?php
                    $interest = explode(',', $vip['info']['interest']);
                    ?>
                    <div class="left">
                        <input type="checkbox"
                               value="0" <?php if (in_array(0, $interest) && $vip['info']['interest'] !== '' && $vip['info']['interest'] != null) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '旅行/摄影') ?>
                    </div>
                    <div class="left">
                        <input type="checkbox" value="1" <?php if (in_array(1, $interest)) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '电影/音乐') ?>
                    </div>
                    <div class="left">
                        <input type="checkbox" value="2" <?php if (in_array(2, $interest)) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '健身/户外') ?>
                    </div>
                    <div class="left">
                        <input type="checkbox" value="3" <?php if (in_array(3, $interest)) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '美食/家居') ?>
                    </div>
                    <div class="left">
                        <input type="checkbox" value="4" <?php if (in_array(4, $interest)) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '时尚/美容') ?>
                    </div>
                    <div class="left">
                        <input type="checkbox" value="5" <?php if (in_array(5, $interest)) {
                            echo 'checked';
                        } ?>><?= Yii::t('app', '其他') ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="personalMain clause">
                <input type="checkbox" id="declare" value="6" checked><?= Yii::t('app', '我接受') ?><a
                        href="<?= Url::toRoute('discount/policy') ?>
"><?= Yii::t('app', '《I.T个人资料（私隐）政策声明》') ?></a>
            </div>
            <div class="ajaxbtn">
                <a href="javascript:void(0)"><?= Yii::t('app', '提交/修改') ?></a>
            </div>
        </div>
    </div>
</div>

<script src="/public/js/jquery.min.js"></script>
<script src="/public/js/paperfold.min.js"></script>
<script src="/public/js/distpicker.data.js"></script>
<script src="/public/js/distpicker.js"></script>
<script src="/public/js/common-p.js"></script>
<script>
    //提交或修改的链接
    var ajaxUrl = '<?=$update_url?>';
    var webroot = '<?=Url::home()?>';
    var home = '<?=$home_page?>';
    var error_mail = "<?=Yii::t('app', '请输入正确的邮箱地址！')?>";
    var allow_rule = '<?=$allow_rule?>';

    var update_ok = "<?=Yii::t('app', '个人资料已更新成功！')?>";
    var need_fname = "<?=Yii::t('app', '姓名需由大小写英文字母，或者简繁体中文组成哦~')?>";
    var need_lname = "<?=Yii::t('app', '姓名需由大小写英文字母，或者简繁体中文组成哦~')?>";


</script>
<script src="/public/js/personal-i.js"></script>

<script>
    $(function () {
        var paperfold = $('.hidden').paperfold();
        var ck = 0;
        var $ptMain = $('.ptMain');
        $('.paperfold-toggle').click(paperfold.toggle).click(function () {
            if (ck == 0) {
                $ptMain.html('-');
                ck = 1;
            } else {
                $ptMain.html('+');
                ck = 0;
            }

        });
        var $distpicker = $("#distpicker");
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "<?= $vip->info->province ?>",
            city: "<?= $vip->info->city ?>",
            district: "<?= $vip->info->town ?>",
            autoSelect: false
        });

        var area = '<?=$vip->info->area?>';

        if (area != '大陆') {
            var $province = $('#province');
            $province.attr('disabled', true);
        }
    });


</script>
