<?php
use yii\helpers\Url;

?>

<a href="<?= Url::toRoute('discount/add-birth') ?>"
   style="text-decoration:none;float:right;margin-top:20px;margin-left:12px;"
   class="layui-btn layui-btn-small layui-btn-warm" id="bar">新增生日月</a>
<table id="table" layui-skin='row'></table>
<script type="text/javascript">
    var type = 4;
    var brand_data = <?=$brand_data?>;
    var month = <?=$month?>;
    console.log(month['1']);
    console.log(brand_data);
    $('#table').bootstrapTable({
        method: 'get',
        url: '<?= Url::toRoute("discount/birth-list", true); ?>',
        columns: [{
            field: 'title',
            title: '标题'
        },
            {
                field: 'status',
                title: '状态',
                formatter: showStatus
            },
            {
                field: 'member_rank',
                title: '会员类型',
                formatter: showBrand
            },
            {
                field: 'is_set',
                title: '默认',
                width: 10,
                formatter: showSet
            },
            {
                field: 'type_children',
                title: '月份',
                width: 80,
                formatter: showMonth
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
        pageSize: 20,
        selectItemName: true,
        showHeader: true,
        toolbar: '#tbar',
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
        if (row.status ==1){
              return [
            '<button class="set layui-btn  layui-btn-mini "><i class="layui-icon">&#xe631;</i>设置</button><button class="scan layui-btn  layui-btn-mini "><i class="layui-icon">&#xe60a;</i>查看</button><button class=" layui-btn  layui-btn-mini  layui-btn-disabled "><i class="layui-icon">&#xe642;</i>编辑</button><button class=" layui-btn  layui-btn-disabled layui-btn-mini "><i class="layui-icon">&#xe609;</i>发布</button><button class="del layui-btn  layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button>',
        ].join('');

        }else{
              return [
            '<button class="set layui-btn  layui-btn-mini "><i class="layui-icon">&#xe631;</i>设置</button><button class="scan layui-btn  layui-btn-mini "><i class="layui-icon">&#xe60a;</i>查看</button><button class="edit layui-btn  layui-btn-mini "><i class="layui-icon">&#xe642;</i>编辑</button><button class="push layui-btn  layui-btn-mini "><i class="layui-icon">&#xe609;</i>发布</button><button class="del layui-btn  layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button>',
        ].join('');

        }
      
    }

    window.operateEvents = {
        'click .del': function (e, value, row, index) {
            del(row.id);
        },

        'click .scan': function (e, value, row, index) {
            scan(row.id);
        },

        'click .edit': function (e, value, row, index) {
            edit(row.id);

        },
        'click .push': function (e, value, row, index) {
            push(row.id);

        },
        'click .set': function (e, value, row, index) {
            setSend(row.id);

        },
    };
    var $table = $('#table');

    function showStatus(value) {
        return value == 0 ? '未发布' : '已发布';

    }
    function showMonth(value) {
        return month[value];
    }

    function showBrand(value) {
        return brand_data[value];
    }

    function showSet(value) {
        return value == 0 ? '否' : '是';
    }


    function del(id) {
        layer.confirm('确定删除?', {
          btn: ['确定','取消'] //按钮
        }, function(){
          $.post('<?=Url::toRoute('discount/del-birth')?>?id=' + id,
            {id: id},
            function (data) {
                if (data.code == 200) {
                    layer.msg('删除成功', {icon: 1});
                    $table.bootstrapTable('refresh');
                }
            });
        }, function(index){
          layer.close(index);
        });
        
    }

    function scan(id) {
        layer.open({
            title: '选取发布对象',
            type: 2,
            area: ['120px', '170px'],
            content: '<?=Url::toRoute('discount/scannormal');?>?language=cn&type=2&id=' + id
        })
    }

    function edit(id) {
        location.href = "<?=Url::toRoute('discount/edit-birth')?>?id=" + id;
    }

    function setSend(id) {
        $.post('<?=Url::toRoute('discount/set-send')?>', {id: id}, function (data) {
            if (data.code == 200) {
                layer.msg('设置成功', {icon: 1});
                // $table.bootstrapTable('selectPage', 1);
                $table.bootstrapTable('refresh');
            } else {
                layer.msg('设置失败' + data.msg, {icon: 2});
            }
        })
    }

    function push(id) {
        console.log('发布资讯');
        $.post('<?=Url::toRoute("discount/pubbirth")?>', {id: id}, function (data) {
            if (data.code == 200) {
                layer.msg('发布成功', {icon: 1});
                $table.bootstrapTable('refresh');
            } else {
                layer.msg('发布失败'+data.msg);
            }
        })
    }


    $('.discount').show();
    $('#member_birth').attr('class', 'active');
</script>


