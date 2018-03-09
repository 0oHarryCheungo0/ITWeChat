<?php
use yii\helpers\Url;

?>
<style>
    .layui-img-list {
        width: auto;
    }

    .layui-layer-title {
        background: #09c;
        color: #FFF;
        border: none;
    }

    .layui-img-list {
        width: auto;
    }

    .layui-img-box {
        display: inline-block;
        width: 200px;
        height: 100px;
        overflow: hidden;
        position: relative;
    }

    .layui-img-box i {
        display: none;
    }

    .layui-img-box:hover i {
        position: absolute;
        z-index: 2;
        right: 4px;
        top: 4px;
        font-size: 18px;
        cursor: pointer;
        color: #f43530;
        display: block;
    }

    .layui-img-box img {
        width: 200px;
        height: 100px;
    }

    .layui-img-box span {
        display: block;
        min-width: 100%;
        position: absolute;
        z-index: 2;
        left: 0;
        bottom: 0;
        background: #179b16;
        color: #fff;
        padding: 3px;
    }
</style>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">消息类型</label>
            <div class="layui-input-inline">
                <select name="msg_type" lay-filter="msg_type" lay-verify="required" id="response_type">
                    <option value="0">文字</option>
                    <option value="1">图文</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">消息内容</label>
            <div class="layui-input-block">
                <textarea name="msg_text" placeholder="推送内容" class="layui-textarea" id="msg_text"></textarea>
                <div class="layui-img-list" id="response_source_ids">
                </div>
                <button class="layui-btn reply-resource" id='resource_button' style="display: none">
                    <i class="layui-icon">&#xe608;</i> 添加资源
                </button>
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
    layui.use('form', function () {
        var form = layui.form();


        form.on('select(msg_type)', function (data) {
            console.log(data);
            res_type = data.value;
            if (res_type == '1') {
                $("#msg_text").hide();
                $("#response_source_ids").show();
                $("#resource_button").show();
                console.log('1');
            }
            if (res_type == '0') {
                $("#resource_button").hide();
                $("#response_source_ids").hide();
                $("#msg_text").show();
                form.render();
            }

        });
        form.on('submit(formDemo)', function (data) {
            var resType = [];
            if (data.field.msg_type == 1) {
                $('.layui-img-box').each(function () {
                    resType.push($(this).attr('data-name'));
                });
                if (resType.length == 0) {
                    layer.msg('请选择至少一个资源');
                    return false;
                }
            } else {
                if (data.field.msg_text == '') {
                    layer.msg('请填写回复内容');
                    return false;
                }
            }
            field = data.field;
            field.resource_ids = JSON.stringify(resType);
            json = JSON.stringify(field);
            group_id = "<?=Yii::$app->request->get('group_id')?>";
            $.post("<?=Url::to(['vips/save-task'])?>", {json: json,group_id:group_id}, function (ret) {
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


    $(".reply-resource").click(function () {
        response_type = $("#response_type").val();
        if (response_type == '') {
            layer.msg('请选择回复类型');
            return false;
        } else {
            layer.open({
                type: 2,
                area: ['80%', '80%'],
                content: '<?=Url::to(['wechat-resource/get-resource'])?>',
            })
        }
    })
</script>