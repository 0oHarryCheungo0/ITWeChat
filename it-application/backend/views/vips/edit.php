<?php
use yii\helpers\Url;

?>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">分组名</label>
            <div class="layui-input-inline">
                <input type="text" name="group_name" lay-verify="required" placeholder="请输入分组名" autocomplete="off"
                       class="layui-input" value="<?= $group['group_name'] ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">修改</button>
            </div>
        </div>
    </div>
</div>

<script>

    if (top.location != location) {
        var pindex = parent.layer.getFrameIndex(window.name); //获取窗口索引
        var table_index = "<?=Yii::$app->request->get('table_index', false);?>";
    }
    layui.use('form', function () {
        var form = layui.form();
        form.on('submit(formDemo)', function (data) {
            group_name = data.field.group_name;
            group_id = "<?=Yii::$app->request->get('group_id')?>";
            $.post("<?=Url::to(['vips/save'])?>", {group_name: group_name, group_id: group_id}, function (ret) {
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

    });
</script>