<?php
use yii\helpers\Url;

?>
<div class="layui-tab layui-tab-brief">
  <ul class="layui-tab-title" id="tbar">
    <li onclick='change(0)' type='button' value="分类1" class="layui-this" >全部</li>
    <li onclick='change(1)' type='button' value="分类1">BIT</li>
    <li onclick='change(2)' type='button' value="分类1">SIT</li>
    <li onclick='change(3)' type='button' value="分类1">Others</li>
  </ul>
  <a href="<?=Url::toRoute('brand/add')?>" style="text-decoration: none;float:right;margin-top:17px;margin-left:10px;" class="layui-btn layui-btn-small" id="bar"><i class='layui-icon'>&#xe608;</i>新增品牌</a>
  <div class="layui-tab-content">
    <div class="layui-tab-item layui-show"><table id="table" layui-skin='row'></table></div>
  </div>
</div>

<script>
//注意：选项卡 依赖 element 模块，否则无法进行功能性操作
layui.use('element', function(){
  var element = layui.element();

  //…
});
</script>
<script type="text/javascript">
    var type = 0;
	$('#table').bootstrapTable({
	method:'get',
    url: '<?= Url::toRoute("brand/list", true); ?>',
    columns: [{
        field: 'id',
        title: 'ID'
    }, {
        field: 'parent.name',
        title: '父类'
    }, {
        field: 'brand_name',
        title: '品牌'
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
    search: true,
    selectItemName: true,
    showHeader: true,
    searchText:'',
    showRefresh:true,
    classes: 'table-no-bordered layui-table',
    toolbar:'#tbar',
    queryParams:function(params) {
        params.p_id = type;
        return params;
        },
    responseHandler: function (ret) {
           return ret.data;
       },

	});

	function operateFormatter(value, row, index) {
        return [
            '<a style="text-decoration:none" href="<?= Url::toRoute('brand/edit'); ?>?id='+row.id+'" class="like layui-btn layui-btn-small"><i class="layui-icon">&#xe642;</i>编辑</a>&nbsp;<button class="verify layui-btn layui-btn-small"><i class="layui-icon">&#xe630;</i>查看</button>',
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
    function change(id){
        type = id;
        $table.bootstrapTable('selectPage', 1);
        $table.bootstrapTable('refresh');
    }

    function scan(id){
        layer.open({
            title:'品牌详情',
            type:2,
            btn:['关闭'],
            area:['450px','280px'],
            content:'<?= Url::toRoute("brand/scan")?>?id='+id,
        });
    }
    $('.brand').show();
    $('#cardtwo').attr('class','active');
</script>


