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
    <div class="layui-form-item">
        <label class="layui-form-label">资讯到期时间</label>
        <div class="layui-input-inline">
            <input type="text" name="end" id='end_time' onclick="laydate({elem:this,format: 'YYYY-MM-DD hh:mm:ss',istime: true})" class="layui-input"
                   value="<?php if (!empty($data->end)){ echo date('Y-m-d', $data->end);} ?>">
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
    var UPDAURL = '<?=Url::toRoute("news/update-point")?>';
    var ue = UE.getEditor('container', {serverUrl: url, autoFloatEnabled: false});
    var hk = UE.getEditor('hk_container', {serverUrl: url, autoFloatEnabled: false});
    layui.use(['form', 'laydate'], function () {
        var form = layui.form(),
            laydate = layui.laydate;
        form.on('submit(formDemo)', function (data) {
            var post_data = JSON.stringify(data.field);
            console.log(post_data);
            $.post(UPDAURL, {data: post_data}, function (data) {
                if (data.code == 200) {
                    layer.msg('成功', {icon: 1});
                    location.href = "<?=Url::toRoute('news/list', true)?>";
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
            });
            return false;
        });
    });
 
        $('.news').show();
        $('#list').attr('class', 'active');
</script>