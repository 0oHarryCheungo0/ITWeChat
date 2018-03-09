<?php
use yii\helpers\Url;
?>

<a href="<?= Url::toRoute('news/add-expire') ?>"
   style="text-decoration:none;float:right;margin-top:20px;margin-left:12px;"
   class="layui-btn layui-btn-small layui-btn-warm" id="bar">新增会员到期模版</a>
<table id="table" layui-skin='row'></table>
<script type="text/javascript">
    var type = <?=$type?>;
    var brand_data = <?=$brand_data?>;
    var month = <?=$month?>;

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
                field: 'is_set',
                title: '默认',
                formatter: showSet
            },
            {
                field: 'type_children',
                title: '到期类型',
                formatter: showMonth
            },
            {
                field: 'end_time',
                title: '到期时间',
                formatter: showTime
            },
            {
                field: 'member_rank',
                title: '会员类型',
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
        if (row.status == 1){
            return [
                '<button class="del layui-btn  layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button><button class="scan layui-btn  layui-btn-mini layui-btn-normal "><i class="layui-icon">&#xe60a;</i>查看</button><button class="layui-btn-disabled layui-btn  layui-btn-mini layui-btn-normal "><i class="layui-icon">&#xe642;</i>编辑</button><button class="layui-btn-disabled layui-btn  layui-btn-mini layui-btn-normal"><i class="layui-icon">&#xe609;</i>发布</button><button class="def layui-btn  layui-btn-mini "><i class="layui-icon">&#xe631;</i>设置</button>',
            ].join('');

        }else{
            return [
                '<button class="del layui-btn  layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button><button class="scan layui-btn  layui-btn-mini layui-btn-normal "><i class="layui-icon">&#xe60a;</i>查看</button><button class="edit layui-btn  layui-btn-mini layui-btn-normal "><i class="layui-icon">&#xe642;</i>编辑</button><button class="set layui-btn  layui-btn-mini layui-btn-normal"><i class="layui-icon">&#xe609;</i>发布</button><button class="def layui-btn  layui-btn-mini "><i class="layui-icon">&#xe631;</i>设置</button>',
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
        'click .set': function (e, value, row, index) {
            setSend(row.id);

        },
          'click .def': function (e, value, row, index) {
            setDef(row.id);

        },
    };
    var $table = $('#table');

    function showStatus(value) {
        return value == 0 ? '未发布' : '已发布';

    }
    function showTime(value) {
        if (value == 0) {
            return '永久';
        } else {
            return new Date(parseInt(value) * 1000).toLocaleString();
        }
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
              $.post('<?=Url::toRoute('news/del-rank')?>?id=' + id,
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
        location.href = "<?=Url::toRoute('news/edit-rank')?>?id=" + id;
    }

        function setSend(id){
        $.post('<?=Url::toRoute("news/pub-expire")?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg('发布成功');
                $table.bootstrapTable('refresh');
            }else{
                layer.msg('失败'+data.msg);
            }
        })
    }

    function setDef(id){
         $.post('<?=Url::toRoute("discount/set-send")?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg('设置成功');
                $table.bootstrapTable('refresh');
            }else{
                layer.msg('失败');
            }
        })
    }


    $('.news').show();
    $('#expire').attr('class', 'active');
</script>


