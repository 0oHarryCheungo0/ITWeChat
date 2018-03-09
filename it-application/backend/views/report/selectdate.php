<div class="layui-container" style="padding-top: 10px">
    <div class="layui-row">
        <div class="layui-col-md12">
            <div id="test1"></div>
        </div>
    </div>
</div>

<script>
    var request_url = "<?=$request_url?>";
    layui.use('laydate', function () {
        var laydate = layui.laydate;
        var ins1 = laydate.render({
            elem: '#test1', //指定元素
            range: true,
            show: true,
            position:'static',
            btns: ['confirm'],
            change: function(value, date, endDate){
                if  (date.year != endDate.year || date.month !=endDate.month){
                    ins1.hint("范围必须在同一个月内");
                    return false;
                }
            },
            done: function(value, date, endDate){
                if  (date.year != endDate.year || date.month !=endDate.month){
                    ins1.hint("范围必须在同一个月内");
                    return false;
                }
                if (request_url.indexOf("?") != -1){
                    url = request_url + "&range="+value;
                } else {
                    url = request_url + "?range="+value;
                }
                window.open(url);
            }
        });

    });
</script>