<?php
use yii\helpers\Url;

?>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-inline">
                <input type="text" name="title" lay-verify="required" placeholder="请输入标题" autocomplete="off"
                       class="layui-input" value="<?= $data['title'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">URL</label>
            <div class="layui-input-block">
                <input type="text" name="url" lay-verify="required|url" placeholder="链接地址" autocomplete="off"
                       class="layui-input" value="<?= $data['url'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">描述</label>
            <div class="layui-input-block">
                <input type="text" name="description" placeholder="描述内容" autocomplete="off" class="layui-input"
                       value="<?= $data['description'] ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">略缩图</label>
            <div class="layui-input-inline">
                <div class="site-demo-upload image-box">
                    <img src="<?= $data['image'] ?>" id='image_show' style="width:100px">
                    <input type="text" name='image' class="layui-hide" id='i_image' value="<?= $data['image'] ?>">
                </div>
                <input type="file" name="file" class="layui-upload-file">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" name="status" lay-skin="switch"
                       lay-text="开启|停用" <?php if ($data['status'] == 1) {
                    echo 'checked';
                } ?>>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">提交</button>
            </div>
        </div>
    </div>
</div>

<script>

    if (top.location != location) {
        var pindex = parent.layer.getFrameIndex(window.name); //获取窗口索引
        table_index = "<?=Yii::$app->request->get('table_index', false);?>";
    }

    layui.use(['form', 'upload'], function () {
        var form = layui.form();
        form.on('submit(formDemo)', function (data) {
            field = data.field;
            if (field.status == 'on') {
                field.status = 1;
            } else {
                field.status = 0;
            }
            json = JSON.stringify(field);
            source_id = "<?=Yii::$app->request->get('source_id')?>";
            $.post("<?=Url::to(['wechat-resource/save'])?>", {json: json, source_id:source_id}, function (ret) {
                if (ret.code == 0) {
                    parent.$("#table").bootstrapTable('updateRow', {index: table_index, row: ret.data});
                    parent.layer.msg('操作成功');
                    parent.layer.close(pindex);
                } else {
                    layer.msg(ret.msg);
                }
            })
            return false;
        });

        layui.upload({
            url: "<?=Url::to(['helper/image'])?>",
            title:'修改缩略图',
            success: function (res) {
                $("#i_image").val(res.data);
                $("#image_show").attr('src', res.data);
                $(".image-box").show();
                $("#image_show").show();
                // console.log(res); //上传成功返回值，必须为json格式
            }
        });


    });


    $(".reply-resource").click(function () {
        response_type = $("#response_type").val();
        if (response_type == '') {
            layer.msg('请选择回复类型');
            return false;
        } else {
            layer.open({
                type: 2,
                area: ['80%', '80%'],
                content: 'http://www.baidu.com',
            })
        }
    })
</script>