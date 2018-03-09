<?php
use yii\helpers\Url;
?>
<a href="<?= Url::toRoute('user/adduser')?>" style="text-decoration:none" class='layui-btn layui-btn-small'><i class='layui-icon'>&#xe613;</i>添加用户</a>
<a href="<?= Url::toRoute('user/change-password')?>" style="text-decoration:none" class='layui-btn layui-btn-small'><i class='layui-icon'>&#xe620;</i>修改管理员密码</a>
<table id="table" layui-skin='row'></table>

<script type="text/javascript">
	$('#table').bootstrapTable({
	method:'get',
    url: '<?php echo Url::toRoute("user/list", true); ?>',
    columns: [{
        field: 'id',
        title: '用户ID'
    }, {
        field: 'username',
        title: '用户名'
    }, {
        field: 'brandOne.brand_name',
        title: '品牌'
    },
    {
        field:'auth_id',
        title:'分组',
        formatter:showGroup
       
    },{
        field:'is_disabled',
        title:'是否禁用',
        formatter:showDis
    },
    {
        field: 'create_time',
        title: '注册时间',
        formatter:showTime,
    }, {
        title: '操作',
        events: 'operateEvents',
        formatter: 'operateFormatter',
        align: 'center',
    }],
    striped:true,
    pagination: true,
    sidePagination: 'server',
    pageNumber: 1,
    pageSize: 10,
    search: true,
    selectItemName: true,
    showHeader: true,
    searchText:'',
    showRefresh:true,
     classes: 'table-no-bordered layui-table',

    responseHandler: function (ret) {
           return ret.data;
       },

	});
    var auth = <?=$auth?>;
    console.log(auth);
	function operateFormatter(value, row, index) {
        return [
            '<a style="text-decoration:none" href="<?= Url::toRoute(['user/edit'])?>?id='+row.id+'" class=" layui-btn layui-btn-small"><i class="layui-icon">&#xe642;</i>编辑</a>&nbsp;<button  class="like layui-btn layui-btn-small"><i class="layui-icon">&#xe60a;</i>密码重置</a>&nbsp;<button  class="dis layui-btn layui-btn-small"><i class="layui-icon">&#xe614;</i>禁用/启用</a>',
        ].join('');
    }

    window.operateEvents = {
        'click .like': function (e, value, row, index) {
            password(row.id, index);
        },

        'click .remove': function (e, value, row, index) {
            delHouse(row.id);
        },
        'click .dis': function (e, value, row, index) {
            disAdmin(row.id);
        },
    };
    var $table = $('#table');
    function disAdmin(id){
        $.post('<?=Url::toRoute("user/dis-admin")?>',{id:id},function(data){
            if (data.code == 200){
                layer.msg(data.msg);
                $table.bootstrapTable('selectPage', 1);
                $table.bootstrapTable('refresh');
            }else{
                layer.msg(data.msg);
            }
        })
    }

    function showGroup(value){
        console.log(auth[value]);
        return auth[value];
    }

        function showTime(value) {
        if (value == 0) {
            return '永久';
        } else {
            return new Date(parseInt(value) * 1000).toLocaleString();
        }
    }

    function showDis(value){
        return value==0?'启用':'禁用';
    }

    function password(id){
        layer.prompt({title: '输入重置密码，并确认', formType: 1}, function(pass, index){
          layer.close(index);
          layer.prompt({title: '输入确认密码，并确认', formType: 1}, function(text, index){
            layer.close(index);
              $.post('<?= Url::toRoute("user/resetpassword")?>',
                {password1:pass,password2:text,id:id},
                function(data){
                    if (data.code ==200){
                        layer.msg('修改密码成功');
                    }else{
                        layer.msg(data.msg);
                    }
                });
          });
        });
    }
    $('.brand').show();
    $('#cardone').attr('class','active');
</script>
