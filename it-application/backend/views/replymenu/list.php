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
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

<!-- Latest compiled and minified Locales -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/locale/bootstrap-table-zh-CN.min.js"></script>

<ol class="am-breadcrumb">
  <li><a href="#">微信配置</a></li>
  <li class="am-active">回复列表</li>
</ol>
<div class="layui-tab layui-tab-card" style="height:2000px;">
    <form id="tbar" class="layui-form layui-box" >
    
    </form>
    <!-- <a href="index.php?r=brand/add" style="float:right;margin-top:22px;margin-left:10px;margin-right:10px;" class="layui-btn layui-btn-small" id="bar"><i class="layui-icon">&#xe642;</i>新增员工</a> -->
    <!-- <a href="index.php?r=brand/add" style="float:right;margin-top:22px;margin-left:10px;" class="layui-btn layui-btn-small" id="bar"><i class="layui-icon">&#xe601;</i>批量导出</a> -->
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show"><table id="table"></table></div>
    </div>



</div>

<script type="text/javascript">
	$('.wxset').show();
	$('#reply').attr('class','active');
  $("#table").bootstrapTable({
	  method:"post",
	  striped:true,
	  pageList:[10, 25, 50],
	  url:"<?php echo \yii::$app->urlManager->createUrl(['replymenu/keywordlist'])?>",
	  sortOrder:'desc',
	  sortStable:true,
	  dataType:"json",
	  pagination:true,
	  pageSize:10,
	  search:true,
	  columns:[{field:"id",
	  		  title:"图文ID",
			  align:"center",
			  sorter:"true",		  
		  },{
		  field:"keyword",
		  title:"关键词",
		  align:"center",
		  sorter:"true",		  
	  },{
		  field:"title",
		  title:"标题",
		  align:"center",
		  sorter:"true",		  
	  },{
		  field:"description",
		  title:"内容",
		  align:"center",
		  sorter:"true",		  
	  },{
		  field:"url",
		  title:"链接",
		  align:"center",
		  sorter:"true",		  
	  },
	  {
		 
		  title:"图片",
		  align:"center",
		  sorter:"true",
		  formatter:"handlePic",		  
	  },{
		  title:"操作",
		  align:"center",
		  events:"operate",
		  sorter:"true",
		  formatter:"handle",	  
	  }
	  ],
	  responseHandler: function (ret) {
		  console.log(ret);
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
				window.location.href="<?php echo \yii::$app->urlManager->createUrl('replymenu/keywordedit')?>"+"&id="+row.id;
			},

			"click .delete":function(e,value,row,index){
				layer.confirm('确定要删除吗?', {icon: 3, title:'提示'}, function(index){
					window.location.href="<?php echo \yii::$app->urlManager->createUrl('replymenu/keyworddelete')?>"+"&id="+row.id;
					});
				
			}
	}
	
		Query(document).ready(function() {
		    jQuery('.nailthumb-container').nailthumb();

		});
	
	
</script>


