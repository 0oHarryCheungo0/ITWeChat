<?php use yii\helpers\Url; ?>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>登录</title>
    <link rel="stylesheet" href="<?=Url::to('@web/landing/css/landCss.css')?>">
    <link rel="stylesheet" href="<?=Url::to('@web/landing/css/fonts/iconfont.css')?>">
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
</head>
<body>
<div class="landTop">
    <div class="main">
        <h3>管理员系统<small>Admin system</small></h3>
    </div>
</div>
<div class="landBox">
    <img src="<?=Url::to('@web/landing/images/bnanner_02.jpg')?>" class="mainImg">
    <div class="landPosition">
        <div class="main landMain">
            <div class="infoBox">
                <div class="main">
                    <h3><span>管理员</span>登录</h3>
                    <form action="<?=Url::toRoute('admin/login')?>" method="post">
                    <p>用户名<span class="message" style="display:none">(用户名不能为空)</span></p>
                    <div class="inputBox">
                        <input type="text" name="username">
                        <div class="iconBox"><span class="iconfont icon-denglu"></span></div>
                    </div>
                    <p>密码</p>
                    <div class="inputBox">
                        <input type="password" name="password">
                        <div class="iconBox"><span class="iconfont icon-mima"></span></div>
                    </div>
                    <div class="btnBox">
                        <div class="left btnBox_l">
                            <a href="#" onclick="login()">登 录</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="landBt">
    <div class="main">
        <div class="btInfo">
            广州市常盈网络科技有限公司 粤B2-20080166 粤ICP备09113667号
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).keyup(function(event){
  if(event.keyCode ==13){
    login();
  }
});
	function login(){
		var url = '<?= Url::toRoute('admin/login')?>';
		var data = $("form").serialize();
		$.post(url,
			{data:data},
			function(data){
				if (data.code ==200){
					location.href='<?= Url::toRoute('user/list')?>';
				}else if(data.code ==201){
					location.href='<?= Url::toRoute('store/index')?>';
				}else{
                    alert(data.msg);
                }
			});
	}
</script>
</body>
</html>
