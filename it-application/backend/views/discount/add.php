<?php use yii\helpers\Url; ?>
<style type="text/css">
</style>

<fieldset class="layui-elem-field">
    <legend>专属优惠 - 会员等级优惠发布</legend>
    <div class="layui-field-box">

        <div style="background: #fff;padding: 15px;margin-bottom: 40px;height:1700px">
            <form class="layui-form layui-box" action="">
                <input type="hidden" name="id" id="id" value=''>
                <input type="hidden" name="type" id='type' value='<?= $type ?>'>
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
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" id='title' lay-verify="title" autocomplete="off" placeholder=""
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="hk_title" id='hk_title' lay-verify="title" autocomplete="off"
                               placeholder="" class="layui-input">
                    </div>
                </div>
                <br/>
                <br/>
                <div class="layui-form-item">
                    <label class="layui-form-label">内容</label>
                    <div class="layui-input-block">
                        <script id="container" name="contents" type="text/plain"></script>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">内容繁体字</label>
                        <div class="layui-input-block">
                            <script id="hk_container" name="hk_contents" type="text/plain"></script>
                        </div>

                        <!-- 配置文件 -->
                        <script type="text/javascript"
                                src="<?= Url::to('@web/backend/utf8-php/ueditor.config.js', true) ?>"></script>
                        <!-- 编辑器源码文件 -->
                        <script type="text/javascript"
                                src="<?= Url::to('@web/backend/utf8-php/ueditor.all.js', true) ?>"></script>
                        <!-- 实例化编辑器 -->
                        <script type="text/javascript">
                            var url = '<?=Url::to(["helper/editor"], true)?>';
                            var ue = UE.getEditor('container', {serverUrl: url, autoFloatEnabled: false});
                            var hk = UE.getEditor('hk_container', {serverUrl: url, autoFloatEnabled: false});
                        </script>
                    </div>
                    <input class="layui-btn" type="button" value="提交" onclick="sub()">
            </form>
            <hr>
        </div>

    </div>

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
        //创建一个编辑器


        var __init = $('#p_id').val();
        var type = $('#type').val();
        form.on('select(aihao)', function (data) {
            var __init = data.value;

            $.post('<?=Url::toRoute("news/getmemberdata")?>',
                {type: type, __init: __init},
                function (data) {
                    if (data.code == 200) {
                        $('#id').val(data.data.id);
                        $('#title').val(data.data.title);
                        ue.setContent(data.data.content);
                    } else {
                        $('#id').val('');
                        $('#title').val('');
                        ue.setContent('');
                    }
                })

        })
        $.post('<?=Url::toRoute("news/getmemberdata")?>',
            {type: type, __init: __init},
            function (data) {
                if (data.code == 200) {
                    $('#id').val(data.data.id);
                    $('#title').val(data.data.title);
                    ue.setContent(data.data.content);
                }
            })

    });
    function sub() {
        var html = ue.getContent();
        var hk_content = hk.getContent();
        var type = $('#type').val();
        var id = $('#id').val();
        var title = $('#title').val();
        var hk_title = $('#hk_title').val();
        var p_id = $('#p_id').val();

        $.post('<?=Url::toRoute('news/memberrank')?>',
            {id: id, type: type, title: title, hk_title: hk_title, hk_content: hk_content, content: html, rank: p_id},
            function (data) {
                if (data.code == 200) {
                    layer.msg('成功');
                } else {
                    layer.msg('创建成功');
                }
                return false;
            });
    }
    $('.discount').show();
    $('#member_rank').attr('class', 'active');
</script>
<script>


</script>



