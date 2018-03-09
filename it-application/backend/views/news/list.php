<?php
use yii\helpers\Url;

?>
<a class="layui-btn layui-btn-small" style="text-decoration: none;float:right;margin-top:17px;margin-left:10px;"
   href="<?= Url::toRoute(['news/add', 'type' => 1]) ?>">新增积分资讯</a>
<button class="layui-btn layui-btn-small layui-btn-warm"
        style="text-decoration: none;float:right;margin-top:17px;margin-left:10px;" onclick="point()">签到初始化
</button>
<button class="layui-btn layui-btn-small layui-btn-warm"
        style="text-decoration: none;float:right;margin-top:17px;margin-left:10px;" onclick="prefect()">完善资料
</button>
<div class="layui-tab-content">
    <table id="table" layui-skin='row'></table>
    <script>
        layui.use('layer', function () {
            var layer = layui.layer;
        });
    </script>
    <script type="text/javascript">
        var __RESETURL__ = '<?=Url::toRoute('news/reset')?>';
        var type = 1;
        $('#table').bootstrapTable({
            method: 'get',
            url: '<?php echo Url::toRoute("news/list", true); ?>',
            columns: [

                {
                    field: 'title',
                    title: '资讯标题'
                },
                {
                    field: 'end',
                    title: '到期时间',
                    formatter:showTime,
                },
                {
                    field: 'is_send',
                    title: '发布',
                    formatter: showStatus,
                },
                {
                    title: '操作',
                    events: 'operateEvents',
                    formatter: 'operateFormatter',
                    align: 'center',
                    width: 280
                }],
            striped: true,
            pagination: true,
            sidePagination: 'server',
            pageNumber: 1,
            pageSize: 10,
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
            if (row.is_send ==1){
                 return [
                '<button class="verify layui-btn layui-btn-mini"><i class="layui-icon">&#xe60a;</i>查看</button><button class="layui-btn layui-btn-mini layui-btn-disabled"><i class="layui-icon">&#xe609;</i>发布</button><button class="layui-btn layui-btn-mini layui-btn-disabled"><i class="layui-icon">&#xe642;</i>编辑</button><button class="del layui-btn layui-btn-mini"><i class="layui-icon">&#xe640;</i>删除</button>',
            ].join('');
            }else{
                return [
                '<button class="verify layui-btn layui-btn-mini"><i class="layui-icon">&#xe60a;</i>查看</button><button class="publish layui-btn layui-btn-mini"><i class="layui-icon">&#xe609;</i>发布</button><button class="edit layui-btn layui-btn-mini"><i class="layui-icon">&#xe642;</i>编辑</button><button class="del layui-btn layui-btn-mini"><i class="layui-icon">&#xe640;</i>删除</button>',
            ].join('');
            }
            
        }

        window.operateEvents = {
            'click .like': function (e, value, row, index) {
                updateneed(row.id, index);
            },

            'click .publish': function (e, value, row, index) {
                pub(row.id);

            },
            'click .verify': function (e, value, row, index) {
                scan(row.id);

            },
            'click .del': function (e, value, row, index) {
                del(row.id, index);
            },

            'click .edit': function (e, value, row, index) {
                edit(row.id);

            },
        };
        var $table = $('#table');
        function change(id) {
            type = id;
            $table.bootstrapTable('selectPage', 1);
            $table.bootstrapTable('refresh');
        }

        function showStatus(value) {
            return value == 0 ? '未发布' : '已发布';
        }

        function push() {
            var data = $table.bootstrapTable('getAllSelections');
            var len = $table.bootstrapTable('getAllSelections').length;
            var str = '';

            for (i = 0; i < len; i++) {
                str += data[i].id + ',';
            }

            $.post('index.php?r=news/release',
                {data: str},
                function (data) {
                    if (data.code == 200) {
                        layer.msg('推送成功');
                          $table.bootstrapTable('refresh');
                    } else {
                        layer.msg('推送失败');
                    }
                })
        }

        function release() {
            var data = $table.bootstrapTable('getAllSelections');
            var len = $table.bootstrapTable('getAllSelections').length;
            var str = '';
            for (i = 0; i < len; i++) {
                str += data[i].id + ',';
            }
            layer.open({
                title: '选取发布对象',
                type: 2,
                area: ['300px', '200px'],
                content: '<?=Url::toRoute('news/group')?>?str=' + str
            })
        }

        function scan(id) {
            layer.open({
                title: '选取发布对象',
                type: 2,
                area: ['120px', '170px'],
                content: '<?=Url::toRoute('discount/scannormal');?>?language=cn&type=1&id=' + id
            })
        }

        function pub(id) {
            $.get('<?= Url::toRoute('news/release')?>?str=' + id, function (data) {
                if (data.code == 200) {
                    layer.msg('发布成功');
                      $table.bootstrapTable('refresh');

                }
            })
        }


        function point() {
            $.post('<?=Url::toRoute('news/pupoint')?>', {}, function (data) {
                if (data.code == 200) {
                    layer.msg('发布成功');
                }
            })
        }

        function prefect() {
            $.post('<?=Url::toRoute('news/puprefect')?>', function (data) {
                if (data.code == 200) {
                    layer.msg('发布成功');
                }
            })
        }

        function reset() {
            $.get(__RESETURL__,
                {data: 'test'},
                function (data) {
                    if (data.code == 200) {
                        layer.msg('发布成功');
                    } else {
                        layer.msg(data.error);
                    }
                });
        }

        function del(id){
             layer.confirm('确定删除?', {
          btn: ['确定','取消'] //按钮
        }, function(){
        $.post('<?=Url::toRoute("news/del-point")?>',{id:id},function(data){
                if (data.code == 200){
                    layer.msg('删除成功');
                      $table.bootstrapTable('refresh');
                }else{
                    layer.msg('失败');
                }
            })
   
         
        }, function(index){
          layer.close(index);
        });
        
        }

          function showTime(value) {
        if (value == 0) {
            return '永久';
        } else {
            return new Date(parseInt(value) * 1000).toLocaleString();
        }
    }

        function edit(id){
            location.href='<?=Url::toRoute("news/edit-point")?>?id='+id;
        }


        $('.news').show();
        $('#list').attr('class', 'active');
    </script>


