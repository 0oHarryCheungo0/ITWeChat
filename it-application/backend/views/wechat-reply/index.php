<?php
use yii\helpers\Url;

?>

<fieldset class="layui-elem-field layui-field-title" style="padding-bottom: 20px">
    <legend>自动回复</legend>
    <div class="bs-bars pull-left" style="padding-top: 20px">
        <div id="toolbar" class="toolbar">
            <button class="layui-btn layui-btn-small reply-add">
                <i class="layui-icon">&#xe608;</i> 添加
            </button>
            <button class="layui-btn btn-warm layui-btn-small reply-sub">
                <i class="layui-icon">&#xe614;</i> 关注自动回复
            </button>
            <button class="layui-btn btn-warm layui-btn-small reply-scan">
                <i class="layui-icon">&#xe614;</i> 扫描自动回复
            </button>
            <button class="layui-btn btn-warm layui-btn-small reply-msg">
                <i class="layui-icon">&#xe614;</i> 消息自动回复
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


    var _AJAXURL__ = "<?=Url::to(['wechat-reply/replys'])?>";
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
            field: 'keyword',
            title: '关键字',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'response_type',
            title: '回复类型',
            align: 'center',
            valign: 'middle',
            formatter: showReplyType,
        }, {
            field: 'match_times',
            title: '匹配次数',
            align: 'center',
            valign: 'middle',
        },{
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
            url = "<?=Url::to(['wechat-reply/edit'])?>" + "?reply_id=" + row.id + '&table_index=' + index;
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
            layer.confirm('确认删除回复？', {
                icon: 3,
                title: '确认删除？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['wechat-reply/delete'])?>", {reply_id: row.id}, function (ret) {
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
    function showReplyType(value) {
        if (value == 0) {
            return '文字回复';
        } else if(value == 1){
            return '图文回复';
        } else {
            return '图片回复'
        }
    }

    function showStatus(value) {
        if (value == 0) {
            return '已停用';
        } else {
            return '正在使用';
        }
    }

    $(".reply-add").click(function () {
        layer.open({
            type: 2,
            title: '添加',
            area: ['800px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: '<?=Url::to(['wechat-reply/add'])?>' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
        });
    })

    $(".reply-sub").click(function () {
        layer.open({
            type: 2,
            title: '关注自动回复设置',
            area: ['800px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: '<?=Url::to(['wechat-reply/sub-reply'])?>' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
        });
    })

    $(".reply-scan").click(function () {
        layer.open({
            type: 2,
            title: '扫描自动回复设置',
            area: ['800px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: '<?=Url::to(['wechat-reply/scan-reply'])?>' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
        });
    })

    $(".reply-msg").click(function () {
        layer.open({
            type: 2,
            title: '消息自动回复设置',
            area: ['800px', '80%'],
            shadeClose: true,
            isOutAnim: false,
            content: '<?=Url::to(['wechat-reply/msg-reply'])?>' //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
        });
    })

    $('.wxset').show();
    $('#menu_reply').attr('class','active');
</script>
