<?php
use yii\helpers\Url;

?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/bootstrap/dist/bootstrap-table.min.css') ?>">
<script src="<?= Url::to('@web/backend/bootstrap/dist/bootstrap-table.min.js') ?>"></script>
<script src="<?= Url::to('@web/backend/bootstrap/dist/locale/bootstrap-table-zh-CN.min.js') ?>"></script>
<fieldset class="layui-elem-field layui-field-title" style="padding-bottom: 20px">
    <legend分组成员
    </legend>
    <table id="table" lay-skin="row"></table>
</fieldset>
<script type="text/javascript">

    layui.use(['form', 'layer'], function () {
        var form = layui.form();
        var layer = layui.layer;

    });
    var _AJAXURL__ = "<?=Url::to(['vips/group-view-data', 'group_id' => Yii::$app->request->get('group_id')])?>";
    $('#table').bootstrapTable({
        pagination: true,
        classes: 'table-no-bordered layui-table',
        queryParams: queryParams,
        pageSize: 10,
        sortOrder: 'desc',
        sidePagination: 'server',
        columns: [{
            field: 'id',
            title: 'ID',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'openid',
            title: 'openid',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'vip_code',
            title: '会员卡号',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'vips.name',
            title: '微信昵称',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'vips.phone',
            title: '手机号',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'join_date',
            title: '添加时间',
            align: 'center',
            valign: 'middle',
        }, {
            title: '操作',
            width: '80px',
            align: 'center',
            valign: 'middle',
            formatter: operateFormatter,
            events: 'operateEvents',
        }],
        method: 'GET',
        url: _AJAXURL__,
        contentType: 'application/json',
        dataType: 'json',
        responseHandler: function (ret) {
            return ret.data;
        }
    });

    function operateFormatter(value, row, index) {
        return '<button class="layui-btn layui-btn-mini layui-btn-danger delete"><i class="layui-icon">&#xe640;</i>删除</button>';
    }

    window.operateEvents = {
        'click .delete': function (e, value, row, index) {
            layer.confirm('确认删除' + row.vip_code  + '？', {
                icon: 3,
                title: '确认删除？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['vips/delete-vips'])?>", {vips_id: row.id}, function (ret) {
                    if (ret.code == 0) {
                        layer.msg('删除成功');
                        $('#table').bootstrapTable('remove', {field: 'id', values: row.id});
                    } else {
                        layer.msg('删除失败');
                    }
                })
                layer.close(layer_index);
            });
        },
    };

    function queryParams(params) {
        return params;
    }

    $(".group-add").click(function () {
        layer.open({
            type: 2,
            title: '添加',
//            area: ['500px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: '<?=Url::to(['vips/add-group'])?>'
        });
    })

    $('#member').show();
    $('#menu_group').attr('class', 'active');
</script>
