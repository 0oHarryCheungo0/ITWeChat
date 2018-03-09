<?php

use yii\helpers\Url;

?>
<style type="text/css">
    .layui-layer-btn .layui-layer-btn0 {
        height: 28px;
        line-height: 28px;
        margin: 6px 6px 0;
        padding: 0 15px;
        border: 1px solid #dedede;
        background-color: #f1f1f1;
        color: #333;
        border-radius: 2px;
        font-weight: 400;
        cursor: pointer;
        text-decoration: none;
    }

    legend {
        width: auto;
        border-bottom: none;
        margin-bottom: 0px;
    }
</style>

<fieldset class="layui-elem-field">
    <legend>导出</legend>
    <div class="layui-field-box" style="padding-bottom: 20px">
        <button class='layui-btn layui-btn-small' onclick="export_scan()">扫码加粉数</button>
        <button class='layui-btn layui-btn-small' onclick="export_member()">会员绑定数</button>
        <button class='layui-btn layui-btn-small' onclick="export_active(3)">签到活动积分表</button>
        <button class='layui-btn layui-btn-small' onclick="export_active(2)">完善资料活动积分表</button>
        <button class='layui-btn layui-btn-small' onclick="export_active(1)">注册绑定活动积分表</button>
        <button class='layui-btn layui-btn-small' onclick="export_point()">积分报表</button>
        <button class='layui-btn layui-btn-small' onclick="export_newold()">新老会员绑定资料</button>
        <button class='layui-btn layui-btn-small' onclick="staff()">店员报表</button>
    </div>
</fieldset>
<a id="tbar" href="<?= Url::toRoute('store/add') ?>">
    <button class="layui-btn layui-btn-small layui-btn-normal"><i class="layui-icon">&#xe654;</i>店铺</button>
</a>
<table id="table" lay-skin="row"></table>
<script type="text/javascript">
    var export_param;
    $('#table').bootstrapTable({
        method: 'get',
        url: '<?php echo Url::toRoute("store/list", true); ?>',
        columns: [{
            field: 'store_code',
            title: '店铺编号'
        }, {
            field: 'store_name',
            title: '店铺名'
        }, {
            field: 'city',
            title: '地区'
        }, {
            field: 'is_disabled',
            title: '是否禁用',
            formatter: showDis,
        }, {
                field: 'create_time',
                title: '注册时间'
            }, {
                title: '操作',
                events: 'operateEvents',
                formatter: 'operateFormatter',
                align: 'center',
            }],
        striped: true,
        search: true,
        pagination: true,
        sidePagination: 'server',
        pageNumber: 1,
        toolbar: '#tbar',
        pageSize: 10,
        classes: 'table-no-bordered layui-table',
        queryParams: function (params) {
            export_param = params;
            return params;
        },
        responseHandler: function (ret) {
            return ret.data;
        },

    });
    var $table = $('#table');
    //value:代表当前单元格中的值，row：代表当前行,index:代表当前行的下标,可以使用return 返回你想要的数据显示在单元格中;
    function operateFormatter(value, row, index) {
        if (row.is_disabled == 1) {
            return [
                '<button class="up layui-btn layui-btn-primary layui-btn-mini"><i class="layui-icon">&#xe618;</i>启用店铺</button>',
            ].join('');

        } else {
            return [
                '<a style="text-decoration:none" href="<?=Url::toRoute(
                    "store/edit")?>?id=' + row.id + '"><button class="like layui-btn layui-btn-warm layui-btn-mini"><i class="layui-icon">&#xe642;</i>编辑</button></a>&nbsp;' +
                '<a href="<?=Url::toRoute('staff/codelist')?>?store_id=' + row.id + '" target="_blank"><button class="layui-btn layui-btn-normal layui-btn-mini" ><i class="layui-icon">&#xe62d;</i>二维码</button></a>&nbsp;' +
                '<a href="<?=Url::toRoute('staff/list')?>?store_id=' + row.id + '"><button class="layui-btn  layui-btn-mini "><i class="layui-icon">&#xe63c;</i>员工</button></a>&nbsp;'+
                '<button class="down layui-btn layui-btn-danger layui-btn-mini"><i class="layui-icon">&#x1006;</i>禁用</button>',
            ].join('');
        }

    }

    window.operateEvents = {
        'click .like': function (e, value, row, index) {
            updateneed(row.id, index);
        },

        'click .remove': function (e, value, row, index) {
            delStore(row.id);
        },

        'click .down': function (e, value, row, index) {
            downStore(row.id);
        },
        'click .up': function (e, value, row, index) {
            upStore(row.id);
        },
        'click .del': function (e, value, row, index) {
            delStore(row.id);
        },
    };

    function delStore(id) {
        // $.post('<?=Url::toRoute("store/del")?>',
        //     {id: id},
        //     function (data) {
        //         if (data.code == 200) {
        //             location.reload()
        //         } else {
        //             alert('删除失败');
        //         }
        //     })
    }

    function scan(id) {
        layer.confirm('', {
            btnAlign: 'c',
            btn: ['员工二维码', '员工列表'] //按钮
        }, function (index) {
            window.open("<?=Url::toRoute('staff/codelist')?>?store_id=" + id);
            layer.close(index);
        }, function () {
            location.href = "<?= Url::toRoute("staff/list", true); ?>" + '?store_id=' + id;
        });
    }

    function exportData(id) {
        layer.confirm('', {
            btnAlign: 'c',
            btn: ['粉丝人数', '会员绑定人数'] //按钮
        }, function (index) {
            location.href = "<?=Url::toRoute('store/export')?>?type=1&store_id=" + id;
        }, function () {
            location.href = "<?= Url::toRoute("staff/list", true); ?>" + '?store_id=' + id;
        });
    }

    function export_scan() {

        var data = export_param.search;
        layer.open({
            type: 2,
            content: '<?=Url::to(["report/select-date", 'type' => 3])?>&n_type=0&data=' + data,
            title: false,
            closeBtn: false,
            area: ['580px', '350px'],
            shadeClose: true,
        });
    }

    function export_member() {
        var data = export_param.search;
        layer.open({
            type: 2,
            content: '<?=Url::to(["report/select-date", 'type' => 3])?>&n_type=1&data=' + data,
            title: false,
            closeBtn: false,
            area: ['580px', '350px'],
            shadeClose: true,
        });

    }

    function export_point(){
        var data = export_param.search;
        layer.open({
            type: 2,
            content: '<?=Url::to(["report/select-date", 'type' => 7])?>&n_type=1&data=' + data,
            title: false,
            closeBtn: false,
            area: ['580px', '350px'],
            shadeClose: true,
        });
    }

    function export_newold() {
        var data = export_param.search;
        layer.open({
            type: 2,
            content: '<?=Url::to(["report/select-date", 'type' => 2])?>&data=' + data,
            title: false,
            closeBtn: false,
            area: ['580px', '350px'],
            shadeClose: true,
        });
        return false;

        location.href = '<?=Url::toRoute("store/exportmember")?>?&data=' + JSON.stringify(export_param);

    }

    function staff() {
        var data = export_param.search;
        layer.open({
            type: 2,
            content: '<?=Url::to(["report/select-date", 'type' => 4])?>&data=' + data,
            title: false,
            closeBtn: false,
            area: ['580px', '350px'],
            shadeClose: true,
        });
    }

    function export_active(type) {
        url = '<?=Url::to(["report/select-date", 'type' => 1])?>';
        layer.open({
            type: 2,
            content: url + '&report=' + type,
            title: false,
            closeBtn: false,
            area: ['580px', '350px'],
            shadeClose: true,
        })
    }

    function downStore(id) {
        $.post('<?=Url::toRoute("store/down")?>', {id: id}, function (data) {
            if (data.code == 200) {
                layer.msg('店铺禁用成功', {icon: 1});
                $table.bootstrapTable('refresh');
            } else {
                layer.msg('失败,' + data.msg, {icon: 2});
            }
        })
    }

    function upStore(id) {
        $.post('<?=Url::toRoute("store/up-store")?>', {id: id}, function (data) {
            if (data.code == 200) {
                layer.msg('店铺启用成功', {icon: 1});
                $table.bootstrapTable('refresh');
            } else {
                layer.msg('店铺已启用');
            }
        })
    }

    function showDis(value) {
        return value == 1 ? '禁用' : '启用';
    }

    console.log(window.screen.width);
    console.log(window.screen.height);
    $('.store').show();
    $('.store').find('li[id=storelist]').attr('class', 'active');

</script>

