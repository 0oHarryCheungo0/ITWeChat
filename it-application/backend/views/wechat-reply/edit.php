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

    .layui-upload-button {
        display: none;
    }
</style>
<div style="padding-left: 20px;padding-right: 20px;padding-top: 20px;">
    <div class="layui-form  layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">关键字</label>
            <div class="layui-input-inline">
                <input type="text" name="keyword" required lay-verify="required" placeholder="请输入关键字" autocomplete="off"
                       class="layui-input" value="<?= $data['keyword'] ?>">
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
            <label class="layui-form-label">回复类型</label>
            <div class="layui-input-inline">
                <select name="response_type" lay-filter="res_type" lay-verify="required" id="response_type">
                    <option value="0">文字</option>
                    <option value="1">图文</option>
                    <option value="5">图片</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">回复内容</label>
            <div class="layui-input-block">
                <textarea name="response_text" placeholder="回复内容" class="layui-textarea"
                          id="response_text" <?php if ($data['response_type'] == 1) {
                    echo "style='display:none'";
                } ?>><?= $data['response_text'] ?></textarea>

                <div class="layui-img-list" id="response_source_ids">
                    <?php
                    foreach ($source as $s) {
                        ?>
                        <div class="layui-img-list" id="response_source_ids">
                            <div class="layui-img-box" data-name="<?= $s['id'] ?>">
                                <i class="layui-icon">&#xe640;</i>
                                <img src="<?= $s['image'] ?>">
                                <span><?= $s['title'] ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <button class="layui-btn reply-resource" id='response_resource' <?php if ($data['response_type'] == 0) {
                    echo "style='display:none'";
                } ?>>
                    <i class="layui-icon">&#xe608;</i> 设置资源
                </button>
                <div class="site-demo-upload image-box" style="display: none;">
                    <img src="<?= $data['image'] ?>" id='image_show' style="width:100px">
                    <input type="text" name='media_id' class="layui-hide" id='media_id'
                           value="<?= $data['media_id'] ?>">
                    <input type="text" name='image' class="layui-hide" id='image' style="display: none;"
                           value="<?= $data['image'] ?>">
                </div>
                <input type="file" name="file" class="layui-upload-file" id="response_image" style="display: none;">
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

    var re_type = "<?=$data['response_type']?>";


    layui.use(['form', 'upload'], function () {
        var form = layui.form();

        layui.upload({
            url: '<?=Url::to(['wechat-reply/save-image'])?>'
            , success: function (res) {
                if (res.code == 0) {
                    $("#media_id").val(res.data.media_id);
                    $("#image").val(res.data.file);
                    $("#image_show").attr('src', res.data.file);
                    $(".image-box").show();
                    $("#image_show").show();
                } else {
                    layer.msg("上传素材失败;微信返回:" + res.msg);
                }
            }
        });
        $("#response_type").val(re_type);
        showContent(re_type);
        if (re_type == 5) {
            $(".image-box").show();
            $("#image_show").show();
        }
        form.render();

        form.on('select(res_type)', function (data) {
            console.log(data);
            res_type = data.value;
            showContent(res_type);
        });

        function showContent(res_type) {
            if (res_type == '1') {
                $(".image-box").hide();
                $("#response_text").hide();
                $(".layui-upload-button").hide();
                $("#response_source_ids").show();
                $("#response_resource").show();
            }
            if (res_type == '0') {
                $(".image-box").hide();
                $("#response_resource").hide();
                $("#response_source_ids").hide();
                $(".layui-upload-button").hide();
                $("#response_text").show();
                form.render();
            }
            if (res_type == '5') {
                $("#response_text").hide();
                $("#response_resource").hide();
                $("#response_source_ids").hide();
                $("#response_image").show();
                $(".layui-upload-button").show();
            }
        }

        form.on('submit(formDemo)', function (data) {


            var resType = [];
            if (data.field.response_type == 1) {
                $('.layui-img-box').each(function () {
                    resType.push($(this).attr('data-name'));
                });
                if (resType.length == 0) {
                    layer.msg('请选择至少一个资源');
                    return false;
                }
            } else if (data.field.response_type == 0) {
                if (data.field.response_text == '') {
                    layer.msg('请填写回复内容');
                    return false;
                }
            } else {
                if (data.field.media_id == '') {
                    layer.msg('素材错误,请重新上传');
                    return false;
                }
            }
//            layer.msg(JSON.stringify(data.field));
            field = data.field;
            if (field.status == 'on') {
                field.status = 1;
            } else {
                field.status = 0;
            }
            reply_id = "<?=Yii::$app->request->get('reply_id')?>";
            field.response_source_ids = JSON.stringify(resType);
            json = JSON.stringify(field);
            $.post("<?=Url::to(['wechat-reply/save'])?>", {
                    json: json,
                    reply_id: reply_id,
                },
                function (ret) {
                    if (ret.code == 0) {
                        parent.$("#table").bootstrapTable('updateRow', {index: table_index, row: ret.data});
                        parent.layer.msg('操作成功');
                        parent.layer.close(pindex);

                    } else {
                        layer.msg(ret.msg);
                    }
                }
            )
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

    $(document).on('click', '.layui-img-box .layui-icon', function () {
        $(this).parent().remove();
    })
</script>