<?php
use yii\helpers\Url;

?>
<fieldset class="layui-elem-field layui-field-title" style="padding-bottom: 20px">
    <legend>会员分组</legend>
    <a href="<?= Url::to('@web/backend/public/VIP导入模版.xls',true) ?>" class="layui-btn layui-btn-mini" style="float:right;margin-right:10px;">下载模版</a>
    <div class="bs-bars pull-left" style="padding-top: 20px">
        <div id="toolbar" class="toolbar">
            <button class="layui-btn layui-btn-small group-add" data-toggle="tooltip">
                <i class="layui-icon">&#xe608;</i> 添加分组
            </button>
        </div>
    </div>
    <table id="table" lay-skin="row"></table>
</fieldset>
<script type="text/javascript">

    layui.use(['form', 'layer'], function () {
        var form = layui.form();
        var layer = layui.layer;

    });
    var _AJAXURL__ = "<?=Url::to(['vips/group-data'])?>";
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
            field: 'group_name',
            title: '分组名',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'create_date',
            title: '创建时间',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'update_date',
            title: '更新时间',
            align: 'center',
            valign: 'middle',
        }, {
            title: '操作',
            width: '400px',
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
        return '<button class="layui-btn layui-btn-mini edit"><i class="layui-icon">&#xe642;</i>编辑</button>'
            + '<button class="layui-btn layui-btn-mini add"><i class="layui-icon">&#xe654;</i>导入</button>'
            + '<button class="layui-btn layui-btn-mini vips"><i class="layui-icon">&#xe612;</i>查看</button>'
            + '<button class="layui-btn layui-btn-mini send"><i class="layui-icon">&#xe609;</i>发布</button>'
            + '<button class="layui-btn layui-btn-mini layui-btn-danger delete"><i class="layui-icon">&#xe640;</i>删除</button>';
    }

    window.operateEvents = {
        'click .edit': function (e, value, row, index) {
            url = "<?=Url::to(['vips/edit'])?>" + "?group_id=" + row.id + '&table_index=' + index;
            layer.open({
                type: 2,
                title: '编辑',
//                area: ['500px', '80%'],
                shadeClose: true,
                isOutAnim: false,
                content: url, //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        },
        'click .vips': function (e, value, row, index) {
            url = "<?=Url::to(['vips/group-view'])?>" + "?group_id=" + row.id + '&table_index=' + index;
            layer.open({
                type: 2,
                title: '查看',
                area: ['80%', '80%'],
                shadeClose: true,
                isOutAnim: false,
                content: url, //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        },
        'click .delete': function (e, value, row, index) {
            layer.confirm('确认删除分组' + row.group_name + '？', {
                icon: 3,
                title: '确认删除？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['vips/delete-group'])?>", {group_id: row.id}, function (ret) {
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
        'click .add': function (e, value, row, index) {
            url = "<?=Url::to(['vips/add-in'])?>" + "?group_id=" + row.id;
            layer.open({
                type: 2,
                title: '添加成员',
                shadeClose: true,
                isOutAnim: false,
                content: url,
            })
        },
        'click .send': function (e, value, row, index) {
            location.href = "<?=Url::to(['vips/group-message'])?>" + "?group_id=" + row.id;
//            url = "<?//=Url::to(['vips/add-task'])?>//" + "?group_id=" + row.id;
//            layer.open({
//                type: 2,
//                title: '发送消息',
//                area: ['500px', '80%'],
//                shadeClose: true,
//                isOutAnim: false,
//                content: url,
//            })
        }
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
