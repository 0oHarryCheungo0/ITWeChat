<style>
    .layui-layer-title {
        background: #09c;
        color: #FFF;
        border: none;
    }

    .site-list {
        position: relative;
        font-size: 0;
        width: 250px;
        height: 150px;
        display: inline-block;
        overflow: hidden;
    }

    .site-list img {
        min-height: 150px;
        width: 100%;
    }

    .site-list .site-input {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 2;
        left: 0;
        top: 0;
    }

    .site-list .layui-form-checkbox {
        width: 100%;
        height: 100%;
        margin-top: 0;
        background: none;
        position: relative;
    }

    .site-list .layui-form-checkbox span {
        height: auto;
        width: 100%;
        position: absolute;
        z-index: 1;
        left: 0;
        bottom: 0;
    }

    .site-list:active {
        border-radius: 10px;
    }
</style>
<div class="layui-form">
    <div class="layui-form-label">选择资源</div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <?php
            foreach ($all as $item) {
                ?>
                <div class="site-list">
                    <img src="<?= $item['image'] ?>">
                    <div class="site-input">
                        <input type="checkbox" data-src="<?= $item['image'] ?>" name="<?= $item['id'] ?>"
                               title="<?= $item['title'] ?>">
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="ok">立即提交</button>
        </div>
    </div>
</div>

<script>
    if (top.location != location) {
        var pindex = parent.layer.getFrameIndex(window.name); //获取窗口索引
        table_index = "<?=Yii::$app->request->get('table_index', false);?>";
    }


    layui.use('form', function () {
        var form = layui.form();
        var ckI = 0;
        form.on('checkbox', function (data) {
            if (data.elem.checked == true) {
                ckI = ckI + 1;
            } else {
                ckI = ckI - 1;
            }
            if (ckI > 8) {
                ckI = 8;
                layer.msg('素材最多只能选择8个');
                data.elem.checked = false;
                data.othis.removeClass('layui-form-checked');
            }
        });

        //监听提交
        form.on('submit(ok)', function (data) {
            var subdata = '';
            items = data.field;
            $.each(items, function (index, item) {
                subdata = subdata + '<div class="layui-img-box"data-name="' + index + '"><i class="layui-icon">&#xe640;</i><img src="' + $('input[name="' + index + '"]').attr('data-src') + '"><span>' + $('input[name="' + index + '"]').attr('title') + '</span></div>';
            });
            if (ckI == 0) {
                layer.msg('请选择素材');
                return false;
            } else {
                parent.$("#response_source_ids").html(subdata);
                parent.layer.close(pindex);
            }

            return false;
        });
    });
</script>