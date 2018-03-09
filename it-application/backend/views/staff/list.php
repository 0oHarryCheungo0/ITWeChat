<?php
use yii\helpers\Url;
?>

<style type="text/css">
 .layui-layer-btn .layui-layer-btn0{
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
    <div class="layui-input-inline">
                <a style="text-decoration:none" href='<?=Url::toRoute("staff/add")?>?store_id=<?= $store_id ?>' class="layui-btn layui-btn-small layui-btn-danger" >新增员工</a>
                <!-- <a style="text-decoration:none"  class="layui-btn layui-btn-small" onclick='exportStaff()' >员工报表导出</a> -->
    </div>
 
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"><table id="table" lay-skin="row"></table></div>
    </div>

<script type="text/javascript">
    var type = "<?= $store_id; ?>";
    var export_param;

    $('#table').bootstrapTable({
        method:'get',
        url: '<?= Url::toRoute("staff/list", true); ?>',
        columns: [{
            field: 'staff_code',
            title: '店员编号'
        }, {
            field: 'staff_name',
            title: '店员'
        },
        {
            field: 'store.store_name',
            title: '所属店铺'
        },
         {
            field: 'counts.subscribe_count',
            title: '关注数'
        },
         {
            field: 'counts.scan_count',
            title: '扫码数'
        },
         {
            field: 'counts.vip_count',
            title: '绑定会员'
        },
        {
            field: 'is_disabled',
            title: '是否禁用',
            formatter:showDis
        },
        {
            title: '操作',
            events: 'operateEvents',
            formatter: 'operateFormatter',
            align: 'center',
            width:350

        }],
        striped:true,
        pagination: true,
        sidePagination: 'server',
        pageNumber: 1,
        pageSize: 20,
        search: true,
        showHeader: true,
        searchText:'',
        showRefresh:true,
        toolbar:'#tbar',
        classes: 'table-no-bordered layui-table',
        queryParams:function(params) {
            params.store_id = type;
            export_param = params;
            return params;
        },
        responseHandler: function (ret) {
           return ret.data;
       },

   });

    function operateFormatter(value, row, index) {
        if (row.is_disabled == 1){
              return [
        '<button class="up layui-btn layui-btn-mini layui-btn-primary">启用</button>',
        ].join('');

        }else{
              return [
        '<button class="transfer layui-btn layui-btn-mini layui-btn-normal">转移员工</button><button class="transferAll layui-btn layui-btn-mini layui-btn-normal">批量转移粉丝</button>&nbsp;<a href="<?=Url::toRoute('fans/fanslist')?>?staff_name='+row.staff_code+'"><button class="layui-btn  layui-btn-mini layui-btn-warm"> 推广</button></a>&nbsp;<a><button class="layui-btn  layui-btn-mini " onclick="scan('+row.id+')">详情</button></a>&nbsp;<button class="down layui-btn layui-btn-mini layui-btn-danger">禁用</button><a class="layui-btn layui-btn-mini" href="<?=Url::toRoute('staff/edit')?>?staff_id='+row.id+'">编辑</a>',
        ].join('');
        }
      
    }

    window.operateEvents = {

        'click .scan':function(e,value,row,index){
            scan(row.id);
        },

        'click .transfer': function (e, value, row, index) {
            transfer(row.id, index);
        },
        'click .transferAll': function (e, value, row, index) {
            transferAll(row.id, index);
        },

        'click .verify': function (e, value, row, index) {
            delStaff(row.id);

        },
         'click .down': function (e, value, row, index) {
            downStaff(row.id, index);
        },
         'click .up': function (e, value, row, index) {
            upStaff(row.id, index);
        },
    };
    var $table = $('#table');


    function change(id){
        type = id;
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
    }

    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form()
        ,layer = layui.layer
        ,layedit = layui.layedit
        ,laydate = layui.laydate;
        form.on('select(aihao)', function(data){
          type = data.value; //得到被选中的值
          $table.bootstrapTable('selectPage', 1);
          $table.bootstrapTable('refresh');
      });
    });

    function delStaff(id){
        $.get('index.php?r=staff/del',
            {id:id},
            function(data){
                if (data.code == 200){
                    layer.msg('删除成功');
                     $table.bootstrapTable('selectPage', 1);
                     $table.bootstrapTable('refresh');
                }else{
                    layer.msg('删除失败');
                }
            })
    }

    function scan(id){
         layer.open({
              type: 2,
              title: '员工详情',
              shadeClose: true,
              shade: 0.8,
              area: ['300px', '50%'],
              content: "<?=Url::toRoute('staff/detail')?>?staff_id="+id 
            }); 
    }

    function exportStaff(){
      var data = JSON.stringify(export_param);
      console.log(JSON.stringify(export_param));
      var url = '<?=Url::toRoute('staff/export')?>?data='+data;
      location.href=url;
    }

    function transfer(id){
        layer.open({
            type:2,
            area:['300px','220px'],
            content:'<?=Url::toRoute('staff/transfer')?>?id='+id,
        })
    } 

     function transferAll(id){
        layer.open({
            type:2,
            area:['300px','220px'],
            content:'<?=Url::toRoute('staff/transfer-all')?>?id='+id,
        })
    } 

    function downStaff(id){
        $.post('<?=Url::toRoute("staff/down-staff")?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg('禁用员工成功');
                 $table.bootstrapTable('refresh');
            }else{
                layer.msg('失败,'+data.msg);
            }
        })
    }

     function upStaff(id){
        $.post('<?=Url::toRoute("staff/up-staff")?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg('启用员工成功');
                 $table.bootstrapTable('refresh');
            }else{
                layer.msg('已启用');
            }
        })
    }

    function showDis(value){
        return value == 0?'启用':'禁用';
    }

    $('#store').show();
    $('#store').find('li').attr('class','active');
</script>


