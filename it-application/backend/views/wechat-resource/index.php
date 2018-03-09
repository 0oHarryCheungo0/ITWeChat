<?php
use yii\helpers\Url;

?>
<fieldset class="layui-elem-field layui-field-title" style="padding-bottom: 20px">
    <legend>微信资源管理</legend>
    <div class="bs-bars pull-left" style="padding-top: 20px">
        <div id="toolbar" class="toolbar">
            <button class="layui-btn layui-btn-small source-add">
                <i class="layui-icon">&#xe608;</i> 添加
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


    var _AJAXURL__ = "<?=Url::to(['wechat-resource/resource'])?>";
    $('#table').bootstrapTable({
        pagination: true,
        classes: 'table-no-bordered layui-table',
        queryParams: queryParams,
        pageSize: 10,
        sortOrder: 'desc',
        // search: true,
        sidePagination: 'server',
        // showRefresh: true,
        columns: [{
            field: 'id',
            title: 'ID',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'image',
            title: '略缩图',
            align: 'center',
            valign: 'middle',
            formatter: showImage,
        }, {
            field: 'title',
            title: '标题',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'url',
            title: 'URL',
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
            field: 'status',
            title: '当前状态',
            align: 'center',
            valign: 'middle',
            formatter: showStatus,
        }, {
            title: '操作',
            width: '180px',
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
        return '<button class="layui-btn layui-btn-mini edit"><i class="layui-icon">&#xe642;</i></button><button class="layui-btn layui-btn-mini layui-btn-danger delete"><i class="layui-icon">&#xe640;</i></button>';
    }

    window.operateEvents = {
        'click .edit': function (e, value, row, index) {
            url = "<?=Url::to(['wechat-resource/edit'])?>" + "?source_id=" + row.id + '&table_index=' + index;
            layer.open({
                type: 2,
                title: '编辑',
                area: ['500px', '80%'],
                shadeClose: true,
                isOutAnim: false,
                content: url, //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        },
        'click .delete': function (e, value, row, index) {
            layer.confirm('确认删除资源？', {
                icon: 3,
                title: '确认删除？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['wechat-resource/delete'])?>", {source_id: row.id}, function (ret) {
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

    function showStatus(value) {
        if (value == 0) {
            return '已停用';
        } else {
            return '正在使用';
        }
    }

    function showImage(value) {
        if (value == null) {
            return '图片错误';
        } else {
            return '<img src="' + value + '" style="width:100px">';
        }

    }

    $(".source-add").click(function () {
        layer.open({
            type: 2,
            title: '添加',
            area: ['500px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: '<?=Url::to(['wechat-resource/add'])?>' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
        });
    })
    $('.wxset').show();
    $('#menu_resource').attr('class','active');
</script>
