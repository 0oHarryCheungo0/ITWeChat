<?php
use yii\helpers\Url;
?>
<a class="layui-btn layui-btn-small" style="text-decoration: none;float:right;margin-top:17px;margin-left:10px;"
   href="<?= Url::toRoute('discount/rank-add'); ?>">新增等级优惠</a>

<table id="table" layui-skin='row'></table>


<script>
    //注意：选项卡 依赖 element 模块，否则无法进行功能性操作
    layui.use('element', function () {
        var element = layui.element();
    });
</script>
<script type="text/javascript">
    var brand_data = <?=$brand_data?>;
    var __RESETURL__ = '<?=Url::toRoute('discount/rank-list');?>';
    var type = 1;
    $('#table').bootstrapTable({
        method: 'get',
        url: '<?php echo Url::toRoute("discount/rank-list", true); ?>',
        columns: [

            {
                field: 'title',
                title: '资讯标题'
            },

            {
                field: 'status',
                title: '状态',
                width: 100,
                formatter: showStatus
            }, {
                field: 'is_set',
                title: '默认',
                width: 100,
                formatter: showSet
            },
            {
                field: 'member_rank',
                title: '会员类型',
                width: 50,
                formatter: showBrand
            },
            {
                title: '操作',
                events: 'operateEvents',
                formatter: 'operateFormatter',
                align: 'center',
                width: 320
            }],

        pagination: true,
        sidePagination: 'server',
        pageNumber: 1,
        pageSize: 10,
        classes: 'table-no-bordered layui-table',
        queryParams: function (params) {
            params.type = type;
            return params;
        },
        responseHandler: function (ret) {
            return ret.data;
        },

    });

    function operateFormatter(value, row, index) {
        if (row.status == 1){
            return [
                '<button class=" layui-btn layui-btn-disabled layui-btn-mini"><i class="layui-icon">&#xe609;</i>发布</button><button class="scan layui-btn layui-btn-mini"><i class="layui-icon">&#xe60a;</i>查看</button><button class="layui-btn-disabled layui-btn layui-btn-mini "><i class="layui-icon">&#xe642;</i>编辑</button><button class="set layui-btn layui-btn-mini "><i class="layui-icon">&#xe631;</i>设置</button><button class="like layui-btn layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button>',
            ].join('');
        }else{
            return [
                '<button class="publish layui-btn layui-btn-mini"><i class="layui-icon">&#xe609;</i>发布</button><button class="scan layui-btn layui-btn-mini"><i class="layui-icon">&#xe60a;</i>查看</button><button class="edit layui-btn layui-btn-mini "><i class="layui-icon">&#xe642;</i>编辑</button><button class="set layui-btn layui-btn-mini "><i class="layui-icon">&#xe631;</i>设置</button><button class="like layui-btn layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button>',
            ].join('');
        }

    }

    window.operateEvents = {
        'click .like': function (e, value, row, index) {
            del(row.id, index);
        },

        'click .publish': function (e, value, row, index) {
            pub(row.id);

        },
        'click .scan': function (e, value, row, index) {
            scan(row.id);

        },
        'click .edit': function (e, value, row, index) {
            edit(row.id);

        },
        'click .set': function (e, value, row, index) {
            set(row.id);

        },
    };
    function showStatus(value) {
        return value == 0 ? '未发布' : '已发布';

    }


    function showBrand(value) {
        return brand_data[value];
    }

    function showSet(value) {
        return value == 0 ? '否' : '是';
    }

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
            area: ['300px', '200px'],
            content: '<?=Url::toRoute('news/group');?>?str=' + str
        })
    }

    function scan(id) {
        layer.open({
            title: '选取发布对象',
            type: 2,
            area: ['120px', '170px'],
            content: '<?=Url::toRoute('discount/scannormal');?>?language=cn&type=2&id=' + id
        })
    }

    function pub(id) {
        $.post('<?=Url::toRoute("discount/rel");?>', {id: id}, function (data) {
            if (data.code == 200) {
                layer.msg('发布成功');
                $table.bootstrapTable('selectPage', 1);
                $table.bootstrapTable('refresh');
            }
        })
    }

    function del(id) {

            layer.confirm('确定删除?', {
          btn: ['确定','取消'] //按钮
        }, function(){
         $.post('<?=Url::toRoute("discount/rankdel");?>', {id: id}, function (data) {
            if (data.code == 200) {
                layer.msg('删除成功');
                $table.bootstrapTable('selectPage', 1);
                $table.bootstrapTable('refresh');
            }
        });
         
        }, function(index){
          layer.close(index);
        });



       
    }

    function edit(id) {
        location.href = "<?=Url::toRoute('discount/rank-edit')?>?id=" + id;
    }

    function set(id){
        $.post('<?=Url::toRoute("discount/set-rank")?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg('设置成功');
                $table.bootstrapTable('refresh');   
            }
        });
    }

    $('.discount').show();
    $('#member_rank').attr('class', 'active');
</script>


