<?php

use yii\helpers\Url;

?>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item" style="display: none;">
            <label class="layui-form-label">URL</label>
            <div class="layui-input-block">
                <input type="text" name="url" lay-verify="required|url" placeholder="链接地址" autocomplete="off"
                       class="layui-input" value="http://www.baidu.com">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">优先级</label>
            <div class="layui-input-block">
                <input type="number" name="indexs" placeholder="required|number" autocomplete="off" class="layui-input"
                       value="0">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">缩略图</label>
            <div class="layui-input-inline">
                <div class="site-demo-upload image-box" style="display: none;">
                    <img src="" id='image_show' style="width:100px">
                    <input type="text" name='image' class="layui-hide" id='i_image'>
                </div>
                <input type="file" name="file" class="layui-upload-file">
                <div class="layui-form-mid layui-word-aux">推荐大小（749*381）</div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|停用" checked>
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
    }
    layui.use(['form', 'upload'], function () {
        var form = layui.form();
        form.on('submit(formDemo)', function (data) {
//            layer.msg(JSON.stringify(data.field));
            field = data.field;
            if (field.status == 'on') {
                field.status = 1;
            } else {
                field.status = 0;
            }
            json = JSON.stringify(field);
            $.post("<?=Url::to(['system-rules/save'])?>", {json: json}, function (ret) {
                if (ret.code == 0) {
                    parent.$("#table").bootstrapTable('insertRow', {index: 0, row: ret.data});
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