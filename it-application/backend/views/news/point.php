<?php
use yii\helpers\Url;

?>
<div class="layui-tab layui-tab-card">
    <ul class="layui-tab-title" id="tbar">
        <li onclick='change(0)' type='button' value="分类1" class="layui-this">未发布</li>
        <li onclick='change(1)' type='button' value="分类1">已发布</li>
    </ul>
    <a href="index.php?r=brand/add" style="text-decoration: none;float:right;margin-top:17px;margin-left:10px;"
       class="layui-btn layui-btn-small" id="bar"><i class='layui-icon'>&#xe608;</i>新增资讯</a>
    <button class="layui-btn layui-btn-small" onclick='release()'>发布积分资讯</button>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <table id="table"></table>
        </div>
    </div>
</div>

<script>
    //注意：选项卡 依赖 element 模块，否则无法进行功能性操作
    layui.use('element', function () {
        var element = layui.element();
    });
</script>
<script type="text/javascript">
    var type = 0;
    $('#table').bootstrapTable({
        method: 'get',
        url: '<?= Url::toRoute("news/list"); ?>',
        columns: [
            {
                checkbox: true,
            },
            {
                field: 'title',
                title: '资讯标题'
            },
            {
                title: '操作',
                events: 'operateEvents',
                formatter: 'operateFormatter',
                align: 'center',
            }],
        striped: true,
        pagination: true,
        sidePagination: 'server',
        pageNumber: 1,
        pageSize: 3,
        search: true,
        selectItemName: true,
        showHeader: true,
        searchText: '',
        showRefresh: true,
        toolbar: '#tbar',
        classes: 'table table-no-bordered',
        queryParams: function (params) {
            params.type = type;
            params.cat = 2;
            return params;
        },
        responseHandler: function (ret) {
            return ret.data;
        },

    });

    function operateFormatter(value, row, index) {
        return [
            '<button class="verify layui-btn layui-btn-small"><i class="layui-icon">&#xe643</i>查看</button>',
        ].join('');
    }

    window.operateEvents = {
        'click .like': function (e, value, row, index) {
            updateneed(row.id, index);
        },

        'click .remove': function (e, value, row, index) {
            delHouse(row.id);

        },
        'click .verify': function (e, value, row, index) {
            scan(row.id);

        },
    };
    var $table = $('#table');
    function change(id) {
        type = id;
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
    }

    function push() {
        var data = $table.bootstrapTable('getAllSelections');
        var len = $table.bootstrapTable('getAllSelections').length;
        var str = '';

        for (i = 0; i < len; i++) {
            str += data[i].id + ',';
        }

        $.post('index.php?r=news/release',
            {data: str},
            function (data) {
                if (data.code == 200) {
                    layer.msg('推送成功');
                } else {
                    layer.msg('推送失败');
                }
            })
    }

    function release() {
        var data = $table.bootstrapTable('getAllSelections');
        var len = $table.bootstrapTable('getAllSelections').length;
        var str = '';
        for (i = 0; i < len; i++) {
            str += data[i].id + ',';
        }
        layer.open({
            title: '选取发布对象',
            type: 2,
            content: '<?=Url::toRoute('news/group')?>?type=2&str=' + str
        })
    }

    function scan(id) {
        layer.open({
            title: '资讯详情',
            type: 2,
            btn: ['关闭'],
            area: ['450px', '280px'],
            content: '<?=Url::toRoute('news/scan')?>?id=' + id,
        });
    }
    $('#cardtwo').attr('class', 'active');
</script>


