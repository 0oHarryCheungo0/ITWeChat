<?php
use yii\helpers\Url;
?>
<ol class="am-breadcrumb">
  <li><a href="#">会员</a></li>

  <li class="am-active">会员列表</li>
</ol>

<div class="layui-tab layui-tab-card" style="height:1700px;">
<form class="layui-form" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">会员姓名</label>
    <div class="layui-input-block">
      <input type="text" name="nickname"  placeholder="会员姓名" autocomplete="off" class="layui-input">
    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">手机号码</label>
    <div class="layui-input-block">
      <input type="text" name="phone"  placeholder="手机号码" autocomplete="off" class="layui-input">
    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">openid</label>
    <div class="layui-input-block">
      <input type="text" name="openid"  placeholder="openid" autocomplete="off" class="layui-input">
    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">VipType</label>
    <div class="layui-input-block">
      <input type="text" name="vip_type"  placeholder="会员等级" autocomplete="off" class="layui-input">
    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">MembershipId</label>
    <div class="layui-input-block">
      <input type="text" name="member_id"  placeholder="" autocomplete="off" class="layui-input">
    </div>
  </div>
     <div class="layui-form-item">
    <label class="layui-form-label">生日</label>
    <div class="layui-input-block">
      <input type="text" name="birthday"  placeholder="选择生日" autocomplete="off" class="layui-input">
    </div>
  </div>
     <div class="layui-form-item">
    <label class="layui-form-label">城市</label>
    <div class="layui-input-block">
      <input type="text" name="city"  placeholder="输入城市" autocomplete="off" class="layui-input">
    </div>
  </div>
     <div class="layui-form-item">
    <label class="layui-form-label">绑定门店</label>
    <div class="layui-input-block">
      <input type="text" name="store"  placeholder="请输入标题" autocomplete="off" class="layui-input">
    </div>
  </div>
     <div class="layui-form-item">
    <label class="layui-form-label">绑定时间</label>
    <div class="layui-input-block">
      <input type="text" name="bind_time"  placeholder="请输入绑定时间" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">立即搜索</button>
    </div>
  </div>
</form>
  <div class="layui-tab-content">
    <div class="layui-tab-item layui-show"><table id="table"></table></div>
  </div>
</div>
<script type="text/javascript">
    var type = 1;
    var test;
    var text;
$('#table').bootstrapTable({
	method:'get',
    url: '<?php echo Url::toRoute("members/list", true); ?>',
    columns: [
       {
        field: 'nickname',
        title: '会员姓名'
    },
     {
        title: '操作',
        events: 'operateEvents',
        formatter: 'operateFormatter',
        align: 'center',
    }],
    striped:true,
    pagination: true,
    sidePagination: 'server',
    pageNumber: 1,
    pageSize: 3,
    search: false,
    selectItemName: true,
    showHeader: true,
    searchText:'',
    showRefresh:false,
    toolbar:'#tbar',
    queryParams:function(params) {
        params.type = type;
        params.text =text;
        $('#str').val(JSON.stringify(params));
        test = params;

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

    layui.use('form', function(){
      var form = layui.form();
      form.on('submit(formDemo)', function(data){
        text = JSON.stringify(data.field);
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
        return false;
    });
  });

   
</script>


