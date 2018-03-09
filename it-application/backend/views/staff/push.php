<?php
use yii\helpers\Url;
?>
<div class="layui-tab-item layui-show"><table id="table" lay-skin="row"></table></div>
<script type="text/javascript">
    var type = "<?= $staff_id; ?>";
    $('#table').bootstrapTable({
        method:'get',
        url: '<?= Url::toRoute("staff/push", true); ?>',
        columns: [{
            field: 'staff_name',
            title: '关联员工'
        }, 
        {
            field: 'scan.openid',
            title: 'Openid'
        },  {
            field: 'user.nickname',
            title: '用户昵称'
        }, {
            field: 'scan.scan_date',
            title: '扫码日期'
        },
        {
            field: 'scan.subscribe',
            title: '是否关注'
        },
        {
            title: '操作',
            events: 'operateEvents',
            formatter: 'operateFormatter',
            align: 'center',
            width:280
        }],
        striped:true,
        pagination: true,
        sidePagination: 'server',
        pageNumber: 1,
        pageSize: 20,
        search: true,
        selectItemName: true,
        showHeader: true,
        searchText:'',
        showRefresh:true,
        toolbar:'#tbar',
        classes: 'table-no-bordered layui-table',
        queryParams:function(params) {
            params.staff_id = type;
            return params;
        },
        responseHandler: function (ret) {
           return ret.data;
       },

   });

    function operateFormatter(value, row, index) {
        return [
        '<button href="<?=Url::toRoute("staff/transfer")?>?id='+row.id+'" class="scan layui-btn layui-btn-small">查看</button>',
        ].join('');
    }

    window.operateEvents = {
        'click .scan':function(e,value,row,index){
            scan(row.id);
        },
        'click .verify': function (e, value, row, index) {
            delStaff(row.id);
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

    function scan(id){
        layer.open({
            type:2,
            title:'会员详情页',
            content:'<?=Url::toRoute("stff/scanpush")?>?id='+id,
        });
    }
    $('#store').show();
    $('#store').find('li').attr('class','active');

</script>


