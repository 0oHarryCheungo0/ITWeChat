<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>" media="all">
<script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
<script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>
<br>
<form class="layui-form">
    <div class="layui-form-item">
        <!-- <label class="layui-form-label">发布</label> -->
        <input type="hidden" name="str" id='str' value="<?= $str; ?>">
        <div class="layui-input-inline" style='margin-left:4px '>
            <select name="group" id='group'>
                <option value=""></option>
                <?php foreach ($group as $k => $v): ?>
                    <option value="<?= $v['name']; ?>"><?= $v['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <p class="layui-btn" lay-submit lay-filter="formDemo" onclick="sub()">立即提交</p>
            </div>
        </div>
</form>
<script>
    //Demo
    layui.use('form', function () {
        var form = layui.form();
    });
    var __SUBURL__ = '<?= Url::toRoute('news/release')?>';
    function sub() {
        var str = $('#str').val();
        var group = $('#group').val();
        $.post(__SUBURL__,
            {str: str, group: group},
            function (data) {
                if (data.code == 200) {
                    layer.msg('发布成功');
                    setTimeout('parent.layer.closeAll()', 1000);
                }
            })
    }
</script>

