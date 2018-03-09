<?php
use yii\helpers\Url;

?>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">分组名</label>
            <div class="layui-input-inline">
                <input type="text" name="group_name" lay-verify="required" placeholder="请输入分组名" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">添加</button>
            </div>
        </div>
    </div>
</div>

<script>

    if (top.location != location) {
        var pindex = parent.layer.getFrameIndex(window.name); //获取窗口索引
    }
    layui.use('form', function () {
        var form = layui.form();
        form.on('submit(formDemo)', function (data) {
            group_name = data.field.group_name;
            $.post("<?=Url::to(['vips/save'])?>", {group_name: group_name}, function (ret) {
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

    });
</script>