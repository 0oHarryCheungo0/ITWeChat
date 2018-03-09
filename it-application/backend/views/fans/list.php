<?php
use yii\helpers\Url;
?>   
 <fieldset class="layui-elem-field">
  <legend>搜索区域</legend>
  <div class="layui-field-box">

<form class="layui-form"> <!-- 提示：如果你不想用form，你可以换成div等任何一个普通元素 -->
  <div class="layui-form-item">
    <label class="layui-form-label">店铺编号</label>
    <div class="layui-input-inline">
      <input type="text" id='store_name' placeholder="请输入" class="layui-input" >
    </div>
     <label class="layui-form-label">员工编号</label>
    <div class="layui-input-inline">
      <input type="text" name="" id="staff_name" placeholder="请输入" autocomplete="off" class="layui-input" value="<?=$get_staff_name?>">
    </div>
     <div class="layui-input-inline">
      <p class="layui-btn" onclick="search()">搜索</p>
      <p class="layui-btn layui-btn-primary" onclick="reset()">重置</p>
    </div>
  </div>
</form>
  </div>
</fieldset>
<script src="layui.js"></script>
<script>
layui.use('form', function(){
  var form = layui.form();
  
  //各种基于事件的操作，下面会有进一步介绍
});
</script>
<div class="layui-tab-item layui-show"><table id="table" lay-skin="row"></table></div>
<script type="text/javascript">
    var type = new Object ;
    $('#table').bootstrapTable({
        method:'get',
        url: '<?= Url::toRoute("fans/fanslist", true); ?>',
        columns: [
         {
            field: 'store.store_name',
            title: '关联店铺'
        },{
            field: 'staff.staff_name',
            title: '关联员工'
        }, 
        {
            field: 'store.store_code',
            title: '店铺编号'
        },
        {
            field: 'staff.staff_code',
            title: '员工编号'
        },
        
        {
            field: 'user.openid',
            title: 'Openid'
        },  {
            field: 'user.nickname',
            title: '用户昵称'
        }, {
            field: 'scan_time',
            title: '扫码日期',
            formatter:scandate,
        },
        {
            field: 'type',
            title: '是否绑定为会员',
            formatter:isNotice
        },
        {
            title: '操作',
            events: 'operateEvents',
            formatter: 'operateFormatter',
            align: 'center',
            width:150
        }],
        striped:true,
        pagination: true,
        sidePagination: 'server',
        pageNumber: 1,
        pageSize: 20,
        toolbar:'#tbar',
        classes: 'table-no-bordered layui-table',
        queryParams:function(params) {
            params.type = type;
            return params;
        },
        responseHandler: function (ret) {
           return ret.data;
       },

   });

    function operateFormatter(value, row, index) {
        return [
        '<button class="scan layui-btn layui-btn-small">转移粉丝</button>',
        ].join('');
    }

    function scandate(value){
        return getLocalTime(value);
    }

    function getLocalTime(nS) { 
         return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' '); 
    } 

    function isNotice(value){
        return value == 1?'会员':'粉丝';
    }
    
    function search(){
        type.store_name = $('#store_name').val();
        type.staff_name = $('#staff_name').val();
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
    }

    $(function(){
        search();
    })

    function reset(){
        $('#staff_name').val('');
        $('#store_name').val('');
        type.store_name = '';
        type.staff_name = '';
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
    }

    window.operateEvents = {
        'click .scan':function(e,value,row,index){
            tranFans(row.id);
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

    function tranFans(id){
        layer.open({
            type:2,
            title:'转移粉丝',
            content:'<?=Url::toRoute("fans/tran")?>?id='+id,
        })
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
    $('.store').show();
    $('.store').find('li[id=fans]').attr('class','active');
    
</script>


