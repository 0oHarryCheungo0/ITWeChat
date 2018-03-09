<?php
use yii\helpers\Url;
use yii\web\UrlManager;
?>
<style>
.thumbPic {
    width: 100px;
    height: 100px;
}
</style>

<ol class="am-breadcrumb">
  <li><a href="#">微信配置</a></li>
  <li class="am-active">回复列表</li>
</ol>
<div class="layui-tab layui-tab-card" style="height:2000px;">
    <form id="tbar" class="layui-form layui-box" >  
    </form>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"><table id="table"></table></div>
    </div>
</div>

<script type="text/javascript">
$('.wxset').show();
		$('#templatenewslist').attr('class','active');
  $("#table").bootstrapTable({
	  method:"post",
	  striped:true,
	  pageList:[10, 25, 50],
	  url:"<?php echo \yii::$app->urlManager->createUrl(['templatenews/list'])?>",
	  sortOrder:'desc',
	  sortStable:true,
	  dataType:"json",
	  pagination:true,
	  pageSize:10,
	  search:true,
	  columns:[{field:"id",
	  		  title:"ID",
			  align:"center",
			  sorter:"true",		  
		  },{
		  field:"title",
		  title:"标题",
		  align:"center",
		  sorter:"true",		  
	  },{
		  field:"orderid",
		  title:"订单编号",
		  align:"center",
		  sorter:"true",		  
	  },
	  {
		  field:"orderstatus",
		  title:"订单状态",
		  align:"center",
		  sorter:"true",		  
	  },{
		  field:"content",
		  title:"内容",
		  align:"center",
		  sorter:"true",		  
	  },
	  {
		  title:"操作",
		  align:"center",
		  events:"operate",
		  sorter:"true",
		  formatter:"handle",	  
	  }
	  ],
	  responseHandler: function (ret) {
          return ret
      },
	  
	 	  
	  })
	  
	  function handlePic(value,row,index){
	  	return '<img class="thumbPic" src='+row.image+' />';
  	}
	  
	  function handle(){
	  return [
	          '<button  class="edit layui-btn layui-btn-small "><i class="layui-icon">&#xe642;</i>编辑</button>&nbsp;<button class="delete layui-btn layui-btn-small "><i class="layui-icon">&#xe640;</i>删除</button>',
	          ].join('');
  		}
		window.operate={
			"click .edit":function(e,value,row,index){
				window.location.href="<?php echo \yii::$app->urlManager->createUrl('templatenews/edit')?>"+"&id="+row.id;
			},

			"click .delete":function(e,value,row,index){
				layer.confirm('确定要删除吗?', {icon: 3, title:'提示'}, function(index){
					window.location.href="<?php echo \yii::$app->urlManager->createUrl('templatenews/delete')?>"+"&id="+row.id;
					});
				
			}
	}
	
		Query(document).ready(function() {
		    jQuery('.nailthumb-container').nailthumb();
		    
		});

		
	
</script>


