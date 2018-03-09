<?php use yii\helpers\Url; ?>
<ol class="am-breadcrumb">
    <li><a href="#">品牌</a></li>
    <li class="am-active">新增品牌</li>
</ol>
<div class="layui-tab layui-tab-card" style="height:1700px;">
    <div style="background: #fff;padding: 15px;margin-bottom: 40px;">
        <form class="layui-form layui-box">
            <div class="layui-form-item">
                <label class="layui-form-label">分组</label>
                <div class="layui-input-inline">
                    <select name="p_id" lay-filter="aihao">
                        <option value=''>请选择</option>
                        <?php foreach ($parent as $k => $v): ?>
                            <option value='<?= $v->id; ?>' <?php if (isset($data)) {
                            if ($data->p_id == $v->id) { ?>selected <?php }
                            } ?> ><?php echo $v->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <input type="hidden" name="id" value="<?= $data->id; ?>">
            <div class="layui-form-item">
                <label class="layui-form-label">品牌牌名</label>
                <div class="layui-input-inline">
                    <input type="text" name="brand_name" lay-verify="title" autocomplete="off" placeholder="请输入品牌名称"
                           class="layui-input" value="<?= $data->brand_name; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标识位</label>
                <div class="layui-input-inline">
                    <input type="text" name="identify" lay-verify="title" autocomplete="off" placeholder="请输入品牌名称"
                           class="layui-input" value="<?= $data->identify; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">appid</label>
                <div class="layui-input-block">
                    <input type="text" name="appid" lay-verify="title" autocomplete="off" placeholder="请输入微信appid"
                           class="layui-input" value="<?= $data->appid; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">appsecret</label>
                <div class="layui-input-block">
                    <input type="text" name="appsecret" lay-verify="title" autocomplete="off" placeholder="请输入appsecret"
                           class="layui-input" value="<?= $data->appsecret; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">token</label>
                <div class="layui-input-inline">
                    <input type="text" name="token" lay-verify="title" autocomplete="off" placeholder="请输入微信token"
                           class="layui-input" value="<?= $data->token; ?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">会员等级</label><label class='layui-btn layui-btn-small'
                                                                   id='a'>添加</label><label
                        class='layui-btn layui-btn-small' id='look'>查看结果</label>
                <div id='rank' class="layui-input-inline">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="button" class="layui-btn" onclick="sub()" value="提交">
                </div>
            </div>
        </form>
    </div>
</div>
</div>
<script>
    layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form()
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;

        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor');
        //自定义验证规则
        form.verify({
            title: function (value) {
                if (value.length < 5) {
                    return '标题至少得5个字符啊';
                }
            }
            , pass: [/(.+){6,12}$/, '密码必须6到12位']
            , content: function (value) {
                layedit.sync(editIndex);
            }
        });
        //监听提交
        form.on('submit(demo1)', function (data) {
            layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })
            return false;
        });
    });


    $('#cardtwo').attr('class', 'active');
</script>
<script type="text/javascript">
    $(function () {
        <?php if (empty($data->rank)):?>
        var array = [
            {"rank": "1", "name": ""},
        ];
        <?php else: ?>
        var array = JSON.parse('<?=$data->rank?>');
        <?php endif; ?>
        var html = '';
        if (array.length > 0) {
            for (var i = 0; i < array.length; i++) {
                html += '<li>' +
                    '<input class="input1" style="    height: 38px;line-height: 38px;line-height: 36px\9;border: 1px solid #e6e6e6; background-color: #fff;border-radius: 2px;" type="text" value="' + array[i].rank + '"/>' + '----' + '<input style="    height: 38px;line-height: 38px;line-height: 36px\9;border: 1px solid #e6e6e6; background-color: #fff;border-radius: 2px;" type="text" class="input2" value="' + array[i].name + '"/>' +
                    '<b style="color:#FF9797;cursor:pointer;"><i class="layui-icon" >&#xe640;</i></b>' +
                    '</li>';
            }
            $('#rank').html(html);
        }

        $('#a').click(function () {
            var html = "";
            html += '<li>' +
                '<input class="input1" style="    height: 38px;line-height: 38px;line-height: 36px\9;border: 1px solid #e6e6e6; background-color: #fff;border-radius: 2px;"/>' + '----' + '<input class="input2" style="    height: 38px;line-height: 38px;line-height: 36px\9;border: 1px solid #e6e6e6; background-color: #fff;border-radius: 2px;"/><b style="color:#FF9797;cursor:pointer;"><i class="layui-icon">&#xe640;</i></b>' +
                '</li>';
            $('#rank').append(html);
        });
        $(document).on("click", "#rank li b", function () {
            $(this).parent().remove();
            console.log($(this));
        });
        $(document).on('click', '#look', function () {
            var array = [];
            $('#rank li').each(function () {
                //定义一个json
                var json = {}
                var v1 = $(this).find('.input1').val();
                var v2 = $(this).find('.input2').val();
                json.rank = v1;
                json.name = v2;
                console.log($('.ul li'));
                array.push(json);
            })
            console.log(array);
        });
    });

    function sub() {
        var array = [];
        $('#rank li').each(function () {
            //定义一个json
            var json = {}
            var v1 = $(this).find('.input1').val();
            var v2 = $(this).find('.input2').val();
            json.rank = v1;
            json.name = v2;
            console.log($('.ul li'));
            array.push(json);
        })

        $.post('<?= Url::toRoute('brand/add')?>',
            {data: decodeURIComponent($("form").serialize(), true), array: array},
            function (data) {
                if (data.code == 200) {
                    layer.msg('成功');
                    location.href = "<?= Url::toRoute('brand/list')?>";
                } else {
                    layer.msg(data.msg);
                }
                return false;
            });
    }
</script>

