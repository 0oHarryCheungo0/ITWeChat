<?php
use yii\helpers\Url;

?>
<fieldset class="layui-elem-field layui-field-title" style="padding-bottom: 20px">
    <legend>会员消息记录</legend>
    <div class="bs-bars pull-left" style="padding-top: 20px">
        <div id="toolbar" class="toolbar">
            <button class="layui-btn layui-btn-small task-add" data-toggle="tooltip">
                <i class="layui-icon">&#xe608;</i> 新增推送
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
    var _AJAXURL__ = "<?=Url::to(['vips/group-message-data'])?>";
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
            field: 'msg_type',
            title: '推送类型',
            align: 'center',
            valign: 'middle',
            formatter: showType,
        }, {
            field: 'create_date',
            title: '创建时间',
            align: 'center',
            valign: 'middle',
        },{
            field: 'success_num',
            title: '推送成功数',
            align: 'center',
            valign: 'middle',
        },{
            field: 'fail_num',
            title: '推送失败数',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'finish_date',
            title: '完成时间',
            align: 'center',
            valign: 'middle',
            formatter: showFinish,
        }, {
            field: 'status',
            title: '状态',
            align: 'center',
            valign: 'middle',
            formatter: showStatus,
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
        string = '';
        if (row.status == 0) {
            string += '<button class="layui-btn layui-btn-mini send"><i class="layui-icon">&#xe609;</i>推送</button>';
            string += '<button class="layui-btn layui-btn-mini edit"><i class="layui-icon">&#xe642;</i>编辑</button>'
        }
        string += '<button class="layui-btn layui-btn-mini layui-btn-danger delete"><i class="layui-icon">&#xe640;</i>删除</button>';
        return string;
    }

    function showFinish(value, row) {
        if (row.status == 2) {
            return row.finish_date;
        } else {
            return '-';
        }
    }

    function showStatus(value) {
        value = parseInt(value);
        switch (value) {
            case 0:
                status = '未推送';
                break;
            case 1:
                status = '正在推送';
                break;
            case 2:
                status = '已推送';
                break;
            default:
                status = '未知';
                break;
        }
        return status;
    }

    function showType(value) {
        if (value == 0) {
            return '文本推送';
        } else {
            return '图文推送';
        }
    }

    window.operateEvents = {
        'click .edit': function (e, value, row, index) {
            url = "<?=Url::to(['vips/edit-task'])?>" + "?message_id=" + row.id + '&table_index=' + index;
            layer.open({
                type: 2,
                title: '编辑',
                area: ['800px', '80%'],
                shadeClose: true,
                isOutAnim: false,
                content: url, //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        },
        'click .delete': function (e, value, row, index) {
            layer.confirm('确认删除推送信息？', {
                icon: 3,
                title: '确认删除？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['vips/delete-message'])?>", {message_id: row.id}, function (ret) {
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
        'click .send': function (e, value, row, index) {
            layer.confirm('确认推送给分组用户？', {icon: 3, title: '确认推送？'}, function (layer_index) {
                url = "<?=Url::to(['vips/sure-to-send'])?>";
                $.post(url, {message_id: row.id}, function (ret) {
                    if (ret.code == 0) {
                        $("#table").bootstrapTable('updateRow', {index: index, row: ret.data});
                    } else {
                        layer.msg(ret.msg);
                    }
                })
                layer.close(layer_index);
            });
        }
    };

    function queryParams(params) {
        params.group_id = "<?=Yii::$app->request->get('group_id')?>";
        return params;
    }

    $(".task-add").click(function () {
        group_id = "<?=Yii::$app->request->get('group_id')?>";
        url = "<?=Url::to(['vips/add-task'])?>" + "?group_id=" + group_id;
        layer.open({
            type: 2,
            title: '发送消息',
            area: ['800px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: url,
        })
    })

    $('#member').show();
    $('#menu_group').attr('class', 'active');
</script>
