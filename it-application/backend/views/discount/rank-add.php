<?php use yii\helpers\Url;
use yii\helpers\Html; ?>
<style type="text/css">
    .layui-form-item .layui-input-inline {
        float: left;
        width: 650px;
        margin-right: 10px;
    }

    .layui-form-select dl {
        max-height: 200px;
    }
</style>
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">会员等级</label>
        <div class="layui-input-inline">
            <select name="member_rank" lay-verify="required">
                <option value=""></option>
                <?php foreach ($brand as $k => $v): ?>
                    <option value="<?= $v['rank']; ?>"
                            <?php if ($v['rank'] == $data->member_rank): ?>selected<?php endif; ?>><?= $v['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-inline">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="type" value="<?= $type ?>">
            <input type="text" name="title" placeholder="请输入标题" value="<?= $data->title ?>" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">標題</label>
        <div class="layui-input-inline">
            <input type="text" name="hk_title" value="<?= $data->hk_title ?>" placeholder="请输入标题" autocomplete="off"
                   class="layui-input">
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-inline">
            <script id="container" name="content" type="text/plain"><?= $data->content ?></script>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-inline">
            <script id="hk_container" name="hk_content" type="text/plain"> 
             <?= $data->hk_content ?>


            </script>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-inline">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
        </div>
    </div>
</form>
<!-- 配置文件 -->
<script type="text/javascript" src="<?= Url::to('@web/backend/utf8-php/ueditor.config.js', true); ?>"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?= Url::to('@web/backend/utf8-php/ueditor.all.js', true); ?>"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var url = '<?=Url::to(["helper/editor"], true)?>';
    var UPDAURL = '<?=Url::toRoute("discount/update-birth-data")?>';
    var ue = UE.getEditor('container', {serverUrl: url, autoFloatEnabled: false});
    var hk = UE.getEditor('hk_container', {serverUrl: url, autoFloatEnabled: false});
    //Demo
    layui.use('form', function () {
        var form = layui.form();
        //监听提交
        form.on('submit(formDemo)', function (data) {
            var post_data = JSON.stringify(data.field);
            console.log(post_data);
            $.post(UPDAURL, {data: post_data}, function (data) {
                if (data.code == 200) {
                    layer.msg('成功', {icon: 1});
                    location.href = "<?=Url::toRoute('discount/rank-list', true)?>";
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
            });
            return false;
        });
    });
    $('.discount').show();
    $('#member_rank').attr('class', 'active');
</script>