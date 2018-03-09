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
<fieldset class="layui-elem-field">
    <legend>专属优惠 - 生日月优惠模版</legend>
    <div class="layui-field-box">

        <form class="layui-form layui-box" action="" style="height:1800px">
            <input type="hidden" name="id" id="id" value=''>
            <input type="hidden" name="type" id='type' value='<?= $type ?>'>
            <div class="layui-form-item">
                <label class="layui-form-label">选择月份</label>
                <div class="layui-input-inline">
                    <select name="type_children" id='type_children' lay-filter="type_children">
                        <?php foreach ($month as $k => $v): ?>
                            <option value="<?= $k; ?>"><?= $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">会员等级</label>
                <div class="layui-input-inline">
                    <select name="p_id" id='p_id' lay-filter="aihao">
                        <?php foreach ($brand as $k => $v): ?>
                            <option value="<?= $v['rank']; ?>"><?= $v['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <?= Html::tag('label', '标题', ['class' => 'layui-form-label']) ?>
                <div class="layui-input-inline">
                    <input type="text" name="title" id='title' lay-verify="title" autocomplete="off" placeholder=""
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <?= Html::tag('label', '標題', ['class' => 'layui-form-label']) ?>
                <div class="layui-input-inline">
                    <input type="text" name="hk_title" id='hk_title' lay-verify="title" autocomplete="off"
                           placeholder="" class="layui-input">
                </div>
            </div>
            <br>
            <br>
            <div class="layui-form-item">
                <label class="layui-form-label">内容</label>
                <div class="layui-input-inline">
                    <script id="container" name="contents" type="text/plain">
                    </script>
                </div>
                <br><br><br>
                <div class="layui-form-item">
                    <label class="layui-form-label">內容(繁体字)</label>
                    <div class="layui-input-inline">
                        <script id="hk_container" name="hk_contents" type="text/plain">
                        </script>
                    </div>
                    <!-- 配置文件 -->
                    <script type="text/javascript"
                            src="<?= Url::to('@web/backend/utf8-php/ueditor.config.js', true); ?>"></script>
                    <!-- 编辑器源码文件 -->
                    <script type="text/javascript"
                            src="<?= Url::to('@web/backend/utf8-php/ueditor.all.js', true); ?>"></script>
                    <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        var url = '<?=Url::to(["helper/editor"], true)?>';
                        var ue = UE.getEditor('container', {serverUrl: url, autoFloatEnabled: false});
                        var hk = UE.getEditor('hk_container', {serverUrl: url, autoFloatEnabled: false});
                    </script>
                </div>
                <input class="layui-btn" type="button" value="提交" onclick="sub()">
                <input class="layui-btn" type="button" value="发布" onclick="pub()">
        </form>

    </div>
</fieldset>
<script>
    layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form()
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;
        layedit = layui.layedit;
        var editIndex = layedit.build('contain');

        var __init = $('#p_id').val();
        var type_children = $('#type_children').val();
        var type = $('#type').val();
        form.on('select(aihao)', function (data) {
            __init = data.value;
            $.post('<?=Url::toRoute("news/getexpire")?>',
                {__init: __init, type: type, type_children: type_children},
                function (data) {
                    if (data.code == 200) {
                        $('#id').val(data.data.id);
                        $('#title').val(data.data.title);
                        $('#hk_title').val(data.data.hk_title);
                        hk.setContent(data.data.hk_content);
                        ue.setContent(data.data.content);
                    } else {
                        $('#id').val('');
                        $('#title').val('');
                        $('#hk_title').val('');
                        hk.setContent('');
                        ue.setContent('');
                    }
                })
        });
        form.on('select(type_children)', function (data) {
            type_children = data.value;
            $.post('<?=Url::toRoute("news/getexpire")?>',
                {__init: __init, type: type, type_children: type_children},
                function (data) {
                    if (data.code == 200) {
                        $('#id').val(data.data.id);
                        $('#hk_title').val(data.data.hk_title);
                        hk.setContent(data.data.hk_content);
                        $('#title').val(data.data.title);
                        ue.setContent(data.data.content);
                    } else {
                        $('#id').val('');
                        $('#title').val('');
                        $('#hk_title').val('');
                        hk.setContent('');
                        ue.setContent('');
                    }
                })
        });
        $.post('<?=Url::toRoute("news/getexpire")?>',
            {__init: __init, type: type, type_children: type_children},
            function (data) {
                if (data.code == 200) {
                    $('#id').val(data.data.id);
                    $('#hk_title').val(data.data.hk_title);
                    hk.setContent(data.data.hk_content);
                    $('#title').val(data.data.title);
                    ue.setContent(data.data.content);
                } else {
                    $('#id').val('');
                    $('#title').val('');
                    $('#hk_title').val('');
                    hk.setContent('');
                    ue.setContent('');
                }
            })
    });

    function sub() {
        var html = ue.getContent();
        var hk_html = hk.getContent();
        var id = $('#id').val();
        var type = $('#type').val();
        var hk_title = $('#hk_title').val();
        var title = $('#title').val();
        var p_id = $('#p_id').val();
        var type_children = $('#type_children').val();

        $.post('<?=Url::toRoute('news/updateexpire')?>',
            {
                id: id,
                type: type,
                title: title,
                hk_title: hk_title,
                hk_content: hk_html,
                content: html,
                rank: p_id,
                type_children: type_children
            },
            function (data) {
                if (data.code == 200) {
                    if (data.msg != '') {
                        $('#id').val(data.msg);
                    }
                    layer.msg('成功');
                } else {
                    layer.msg('创建成功');
                }
                return false;
            });
    }

    function pub() {
        var id = $('#id').val();
        if (id == '') {
            layer.msg('请编辑后再发布');
        } else {
            $.post('<?=Url::toRoute("discount/pubbirth")?>', {id: id}, function (data) {
                if (data.code == 200) {
                    layer.msg('发布成功');
                }
            })
        }
    }
    $('.discount').show();
    $('#member_birth').attr('class', 'active');
</script>
<script>


</script>



