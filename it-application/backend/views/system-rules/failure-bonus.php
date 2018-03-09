<?php

use yii\helpers\Url;

?>
<div class="layui-field-box">
    <table id="table" lay-skin="row"></table>
</div>

<script type="text/javascript">


    layui.use(['form', 'layer', 'laydate'], function () {
        var layer = layui.layer;
        var form = layui.form();

    });


    var _AJAXURL__ = "<?=Url::to(['system-rules/failures'])?>";
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
            field: 'vip_code',
            title: 'VIP卡号',
            align: 'center',
            valign: 'middle',
        },{
            field: 'params',
            title: '积分',
            align: 'center',
            valign: 'middle',
            formatter:showPoint,
        }, {
            field: 'create_time',
            title: '创建时间',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'process_time',
            title: '处理时间',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'reason',
            title: '原因',
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
        if (row.status == 0) {
            return '<button class="layui-btn layui-btn-mini layui-btn-danger re-do">重新请求</button>';
        }
    }

    window.operateEvents = {
        'click .re-do': function (e, value, row, index) {
            layer.confirm('确认重新请求' + row.vip_code + '的积分？', {
                icon: 3,
                title: '确认请求？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['system-rules/resend'])?>", {id: row.id}, function (ret) {
                    if (ret.code == 0) {
                        layer.msg('请求成功');
                        $('#table').bootstrapTable('refresh');
                    } else {
                        layer.msg(ret.msg);
                    }
                })
                layer.close(layer_index);
            });
        },
    };

    function showPoint(value){
        ret = JSON.parse(value);
        return ret.BP;
    }

    function showStatus(value) {
        switch (value) {
            case "1":
                status = '处理成功';
                break;
            case "2":
                status = '处理失败';
                break;
            default:
                status = '待处理';
        }
        return status;
    }


    function queryParams(params) {
        return params;
    }

    $('#point').show();
    $('#failure_point').attr('class', 'active');
</script>