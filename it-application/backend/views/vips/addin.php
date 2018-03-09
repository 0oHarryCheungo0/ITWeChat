<?php
use yii\helpers\Url;

?>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label" id="file-name">excel</label>
            <div class="layui-input-inline">
                <input type="file" name="file" class="layui-upload-file">
                <input type="text" class="layui-hide" value="" id="path" name="file_path">
            </div>
        </div>
        <div class="layui-form-item" id="add-button" style="display: none;">
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
    layui.use(['form', 'upload'], function () {
        var form = layui.form();
        group_id = "<?=Yii::$app->request->get('group_id')?>";
        form.on('submit(formDemo)', function (data) {
            $.post("<?=Url::to(['vips/excel-add'])?>", {group_id: group_id,path:data.field.file_path}, function (ret) {
                if (ret.code == 0) {
//                    parent.$("#table").bootstrapTable('insertRow', {index: 0, row: ret.data});
                    parent.layer.msg('操作成功');
                    parent.layer.close(pindex);
                } else {
                    layer.msg(ret.msg);
                }
            })
            return false;
        });

        layui.upload({
            url: "<?=Url::to(['helper/excel'])?>",
            title:'excel上传',
            type:'file',
            ext: 'xls|xlsx',
            success: function (res) {
                console.log(res);
                if (res.code == 0){
                    $("#add-button").show();
                    $('#path').val(res.data);
                } else {
                    $("#add-button").hide();
                    layer.msg(res.msg);
                }
            }
        });

    });
</script>