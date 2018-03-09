<?php
use yii\helpers\Url;

?>
<a href="<?= Url::toRoute('discount/release') ?>"
   style="text-decoration:none;float:right;margin-top:20px;margin-left:12px;"
   class="layui-btn layui-btn-small layui-btn-warm" id="bar" onclick="push()">新增限时优惠</a>
<table id="table" layui-skin='row'></table>


<script type="text/javascript">
    var type = 0;
    var brand_data = <?=$brand_data?>;
    $('#table').bootstrapTable({
        method: 'get',
        url: '<?= Url::toRoute("discount/list", true); ?>',
        columns: [{
            field: 'title',
            title: '标题'
        },
            {
                field: 'is_send',
                title: '状态',
                formatter: showStatus,
            },{
                field:'send_time',
                title:'发送时间',
                formatter:showTime
            },
            {
                field:'end',
                title:'到期时间',
                formatter:showTime1
            },
            {
                field:'member_rank',
                title:'vip_type',
                formatter:showBrand
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
            params.store_id = type;
            return params;
        },
        responseHandler: function (ret) {
            return ret.data;
        },

    });

    function operateFormatter(value, row, index) {
        if (row.is_send == 1){
            return [
                '<button class="del layui-btn  layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button><button class="scan layui-btn  layui-btn-mini "><i class="layui-icon">&#xe60a;</i>查看</button><button class=" layui-btn layui-btn-disabled  layui-btn-mini "><i class="layui-icon">&#xe609;</i>发布</button><button class=" layui-btn layui-btn-disabled  layui-btn-mini "><i class="layui-icon">&#xe642;</i>编辑</button><button class=" layui-btn layui-btn-disabled  layui-btn-mini "><i class="layui-icon">&#xe637;</i>定时</button>',
            ].join('');
        }else{
            return [
                '<button class="del layui-btn  layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>删除</button><button class="scan layui-btn  layui-btn-mini "><i class="layui-icon">&#xe60a;</i>查看</button><button class="send layui-btn  layui-btn-mini "><i class="layui-icon">&#xe609;</i>发布</button><button class="edit layui-btn  layui-btn-mini "><i class="layui-icon">&#xe642;</i>编辑</button><button class="auto layui-btn  layui-btn-mini "><i class="layui-icon">&#xe637;</i>定时</button>',
            ].join('');
        }

    }

    window.operateEvents = {
        'click .scan': function (e, value, row, index) {
            scan(row.id);
        },

        'click .del': function (e, value, row, index) {
            del(row.id);
        },

        'click .send': function (e, value, row, index) {
            send(row.id);

        },
        'click .edit': function (e, value, row, index) {
            edit(row.id);

        },
        'click .auto': function (e, value, row, index) {
            autoSend(row.id);

        },
    };
    var $table = $('#table');

    function showStatus(value) {
        return value == 0 ? '未发布' : '已发布';
    }

    function autoSend(id){
        $.post('<?=Url::toRoute("discount/is-auto")?>',{id:id},function(data){
            if (data.code==200){
                layer.msg('设置成功')
            }else{
                layer.msg('设置失败');
            }
        })
    }

    function showBrand(value) {
        return brand_data[value];
    }

    function showTime(value) {
        if (value == 0) {
            return '未设置';
        } else {
            return new Date(parseInt(value) * 1000).toLocaleString();
        }
    }
      function showTime1(value) {
        if (value == 0) {
            return '未设置';
        } else {
            return new Date(parseInt(value) * 1000).toLocaleString();
        }
    }
    function change(id) {
        type = id;
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
    }

    function del(id) {
         layer.confirm('确定删除?', {
          btn: ['确定','取消'] //按钮
        }, function(){
            $.post('<?=Url::toRoute('discount/del')?>?id=' + id,
            {id: id},
            function (data) {
                if (data.code == 200) {
                    layer.msg('删除成功');
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
            content: '<?=Url::toRoute('discount/scannormal');?>?language=cn&type=3&id=' + id
        })
    }

    function send(id) {
        $.post('<?=Url::toRoute('discount/send-limit')?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg('发布成功');
                $table.bootstrapTable('refresh');
            }
        });
    }

    function edit(id) {
        location.href = "<?=Url::toRoute('discount/release-edit');?>?id=" + id;
    }

    $('.discount').show();
    $('#member_list').attr('class', 'active');
</script>


