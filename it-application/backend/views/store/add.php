<?php use yii\helpers\Url; ?>
<style type="text/css">
    input, select, textarea {
    height: 38px;
    line-height: 38px;
    line-height: 36px\9;
    border: 1px solid #e6e6e6;
    background-color: #fff;
    border-radius: 2px;
        outline: 0;
    font-size:14px!important;
}
.am-form-label {
    float: left;
    display: block;
    padding: 9px 15px;
    width: 100px;
    font-weight: 400;
    text-align: right;
}
</style>

<fieldset class="layui-elem-field">
  <legend><?=$title?>店铺</legend>
  <div class="layui-field-box">
    <a class='layui-btn ' href='<?=Url::toRoute('store/import')?>' style="float: right;text-decoration: none">店铺导入</a>
                <form class="am-form am-form-horizontal">
                    <fieldset>
                        <input type="hidden" name="id" value="<?php if (isset($list)) {
                            echo $list->id;
                        } ?>">
                        <div class="am-form-group am-form-group-sm">
                            <!-- <label for="doc-ipt-email-1">店铺名</label> -->
                            <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">店铺名称</label>
                            <div class="am-u-sm-4 am-u-end">
                                <input type="text" name="store_name" placeholder="输入店铺名"
                                       value="<?php if (isset($list)) {
                                           echo $list->store_name;
                                       } ?>">
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">店铺编号</label>
                            <div class="am-u-sm-4 am-u-end">
                                <input type="text" class="" name="store_code" placeholder="输入店铺编号"
                                       value="<?php if (isset($list)) {
                                           echo $list->store_code;
                                       } ?>">
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">手机号码</label>
                            <div class="am-u-sm-4 am-u-end">
                                <input type="text" class="" name="tel" placeholder="输入手机号码"
                                       value="<?php if (isset($list)) {
                                           echo $list->tel;
                                       } ?>">
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">省市区</label>
                            <script src="<?= Url::to('@web/backend/city/src/city.js') ?>"></script>
                            <div class="am-u">
                                <div class="am-u-sm-2">
                                    <select name="province" id="province"></select>
                                </div>
                                <div class="am-u-sm-2">
                                    <select name="city" id="city"></select>
                                </div>
                                <div class="am-u-sm-2 am-u-end">
                                    <select name="area" id="area"></select>
                                </div>
                            </div>
                            <span class="am-form-caret"></span>
                        </div>
                        <div class="am-form-group">
                            <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">详细地址</label>
                            <div class="am-u-sm-6 am-u-end">
                                <input type="text" class="" name="address" placeholder="输入详细地址"
                                       value="<?php if (isset($list)) {
                                           echo $list->address;
                                       } ?>">
                            </div>
                        </div>

                        <div class="am-form-group">
                            <div class="am-u-sm-10 am-u-sm-offset-2">
                                <p><input type="button" class="am-btn am-btn-default layui-btn" value='提交' onclick="sub()"></p>
                            </div>
                        </div>
                    </fieldset>
                </form>
  </div>
</fieldset>
    
<script>
    var p_val = '<?=$list->province?>';
    var c_val = '<?=$list->city?>';
    var a_val = '<?=$list->area?>';
    function sub() {
        $.post('<?= Url::toRoute('store/update'); ?>',
            {data: decodeURIComponent($("form").serialize(), true)},
            function (data) {
                if (data.code == 200) {
                    layer.msg('成功');
                    location.href = "<?= Url::toRoute('store/list'); ?>"
                } else {
                    layer.msg(data.msg);
                }
                return false;
            });
    }
    $(function () {
        city_selector();
        if (p_val != '') {
            $('#province').val(p_val).change();

        }
        if (c_val != '') {
            $('#city').val(c_val).change();
        }
        if (a_val != '') {
            $('#area').val(a_val).change();
        }
    });
</script>