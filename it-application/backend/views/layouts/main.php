<?php
use backend\assets\AppAsset;
use yii\helpers\Url;
use backend\models\Auth;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language; ?>">
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <!-- 妹子UI CSS 文件 -->
    <!--   <link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.css') ?>"> -->
    <link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.min.css') ?>">
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>" media="all">
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/css/adminCss.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/IconFont/iconfont.css') ?>">
    <!-- 缩略图 -->
    <link rel="stylesheet" href="<?= Url::to('@web/backend/public/css/jquery.nailthumb.1.1.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/backend/bootstrap/dist/bootstrap-table.min.css') ?>">
    <script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="<?= Url::to('@web/backend/bootstrap/dist/bootstrap-table.min.js') ?>"></script>

    <!-- Latest compiled and minified Locales -->
    <script src="<?= Url::to('@web/backend/bootstrap/dist/locale/bootstrap-table-zh-CN.min.js') ?>"></script>
    <script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="<?= Url::to('@web/backend/public/js/bootstrap.min.js') ?>"></script>
    <!-- 通用js -->
    <script src="<?= Url::to('@web/backend/public/js/common.js') ?>"></script>
    <!-- 妹子UI JavaScript 文件 -->
    <!--    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.js') ?>"></script> -->
    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.min.js') ?>"></script>
    <!--    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.js') ?>"></script> -->
    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.min.js') ?>"></script>
    <!--  <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.js') ?>"></script> -->
    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.min.js') ?>"></script>

    <script>
        layui.use('layer', function(){
            var layer = layui.layer;
        });
    </script>
</head>
<body>
<?php $this->beginBody(); ?>
<body ng-app="myApp">
<nav class="navbar navbar-default admin-navbar" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="">I.T.</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#/">后台<?php $auth =  \Yii::$app->session->get('auth');
                        if (!empty($auth)){
                            $arr = explode(',',$auth);
                        }else{
                            $arr = [];
                        }
                        ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                <li><a href="<?= Url::toRoute('admin/logout') ?>">退出</a></li>
                <ul class="dropdown-menu" role="menu">
                </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="" class="dropdown-toggle"
                       data-toggle="dropdown"><?php if (isset(Yii::$app->user->identity->username)) {
                            echo Yii::$app->user->identity->username;
                        } ?><span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= Url::toRoute('admin/logout') ?>">退出</a></li>
                    </ul>
                </li>
            </ul>

        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="containerBox">
    <div class="admin-left-nav" id="adminNav">
        <div class="admin-left-navBox" style="height:100%;">
            <div class="left-nav-box">
                <div class="extend"><span class="iconfont icon-systole"></span></div>
                <?php if (Yii::$app->user->id == 1) { ?>
                    <div class="module">
                        <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务"><span
                                        class="pull-right iconfont icon-cog"></span><span
                                        class="iconfont icon-downBottom module-title"></span><span
                                        class="titleHtml">用户</span></a></div>
                        <ul class="brand" style='display: none'>
                            <li id="cardone"><a href="<?= Url::toRoute('user/list') ?>" data-toggle="tooltip"
                                                data-placement="right" title="用户">
                                    <div class="icoBox pull-left"><span class="am-icon-book"></span></div>
                                    <span class="fontBox pull-left">用户列表页</span></a></li>
                            <li id="cardtwo"><a href="<?= Url::toRoute('brand/list') ?>" data-toggle="tooltip"
                                                data-placement="right" title="品牌">
                                    <div class="icoBox pull-left"><span class="am-icon-cloud"></span></div>
                                    <span class="fontBox pull-left">品牌</span></a></li>

                        </ul>
                    </div>
                    <div class="module">
                        <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务"><span
                                        class="pull-right iconfont icon-cog"></span><span
                                        class="iconfont icon-downBottom module-title"></span><span
                                        class="titleHtml">权限管理</span></a></div>
                        <?php $auth_all = Auth::find()->where(['>','id',1])->all();  ?>
                        <ul class='auth' style="display:none">
                            <?php foreach ($auth_all as $k=>$v):?>
                                <li id="auth<?=$v->id?>"><a href="<?= Url::toRoute(['user/auth','auth'=>$v->id]) ?>" data-toggle="tooltip"
                                                            data-placement="right" title="用户">
                                        <div class="icoBox pull-left"><span class="am-icon-book"></span></div>
                                        <span class="fontBox pull-left"><?=$v->auth; ?></span></a></li>

                            <?php endforeach;?>
                        </ul>
                    </div>

                <?php } else { ?>
                    <?php foreach ($arr as $k=>$v): ?>
                        <?php if ($v ==1): ?>
                            <div class="module">
                                <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务2"><span
                                                class="iconfont icon-downBottom module-title"></span><span
                                                class="titleHtml">店铺</span></a></div>
                                <ul class='store' style="display:none">
                                    <li id='storelist'><a href="<?= Url::toRoute('store/list') ?>" data-toggle="tooltip" data-placement="right"
                                                          title="店铺">
                                            <div class="icoBox pull-left"><span class="am-icon-reorder"></span></div>
                                            <span class="fontBox pull-left">店铺列表</span></a></li>
                                    <li id='fans'><a href="<?= Url::toRoute('fans/fanslist') ?>" data-toggle="tooltip" data-placement="right"
                                                     title="店铺">
                                            <div class="icoBox pull-left"><span class="am-icon-reorder"></span></div>
                                            <span class="fontBox pull-left">推广列表</span></a></li>
                                </ul>

                            </div>
                        <?php endif; ?>
                        <?php if ($v ==2): ?>
                            <div class="module">
                                <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务2"><span
                                                class="iconfont icon-downBottom module-title"></span><span
                                                class="titleHtml">会员</span></a></div>
                                <ul id='member' style="display:none">
                                    <li id="menu_vip"><a href="<?php echo \yii::$app->urlManager->createUrl('vips/all') ?>"
                                                         data-toggle="tooltip" data-placement="right" title="会员">
                                            <div class="icoBox pull-left"><span class="am-icon-reorder"></span></div>
                                            <span class="fontBox pull-left">会员列表</span></a>
                                    </li>
                                    <li id="menu_group"><a href="<?php echo \yii::$app->urlManager->createUrl('vips/group') ?>"
                                                           data-toggle="tooltip" data-placement="right" title="会员分组">
                                            <div class="icoBox pull-left"><span class="am-icon-reorder"></span></div>
                                            <span class="fontBox pull-left">会员分组</span></a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ($v ==3): ?>
                            <div class="module">
                                <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务2"><span
                                                class="iconfont icon-downBottom module-title"></span><span
                                                class="titleHtml">系统配置</span></a></div>
                                <ul id='point' style="display:none">
                                    <li id="menu_point"><a href="<?php echo \yii::$app->urlManager->createUrl('system-rules/index') ?>"
                                                           data-toggle="tooltip" data-placement="right" title="">
                                            <div class="icoBox pull-left"><span class="am-icon-tag"></span></div>
                                            <span class="fontBox pull-left">积分配置</span></a>
                                    </li>
                                    <li id="failure_point"><a href="<?php echo \yii::$app->urlManager->createUrl('system-rules/failure-bonus') ?>"
                                                           data-toggle="tooltip" data-placement="right" title="">
                                            <div class="icoBox pull-left"><span class="am-icon-tag"></span></div>
                                            <span class="fontBox pull-left">异常积分处理</span></a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ($v ==4): ?>
                            <div class="module">
                                <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务2"><span
                                                class="iconfont icon-downBottom module-title"></span><span class="titleHtml">微信配置</span></a>
                                </div>
                                <ul class='wxset' style="display:none">
                                    <li id='menu'><a href="<?php echo \yii::$app->urlManager->createUrl('menu/add-menu') ?>"
                                                     data-toggle="tooltip" data-placement="right" title="菜单配置">
                                            <div class="icoBox pull-left"><span class="am-icon-asterisk"></span></div>
                                            <span class="fontBox pull-left">菜单配置</span></a>
                                    </li>
                                </ul>
                                <ul class='wxset' style="display:none">
                                    <li id='menu_reply'><a
                                                href="<?php echo \yii::$app->urlManager->createUrl('wechat-reply/index') ?>"
                                                data-toggle="tooltip" data-placement="right" title="自动回复管理">
                                            <div class="icoBox pull-left"><span class="am-icon-paw"></span></div>
                                            <span class="fontBox pull-left">自动回复管理</span></a>
                                    </li>
                                </ul>
                                <ul class='wxset' style="display:none">
                                    <li id='menu_resource'><a
                                                href="<?php echo \yii::$app->urlManager->createUrl('wechat-resource/index') ?>"
                                                data-toggle="tooltip" data-placement="right" title="资源管理">
                                            <div class="icoBox pull-left"><span class="am-icon-photo"></span></div>
                                            <span class="fontBox pull-left">资源管理</span></a>
                                    </li>
                                </ul>
                                <ul class="wxset" style="display: none">
                                    <li id="menu_slider"><a href="<?php echo \yii::$app->urlManager->createUrl('system-rules/slider') ?>"
                                                            data-toggle="tooltip" data-placement="right" title="">
                                            <div class="icoBox pull-left"><span class="am-icon-tag"></span></div>
                                            <span class="fontBox pull-left">轮播图设置</span></a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ($v ==5): ?>
                            <div class="module">
                                <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="专属优惠"><span
                                                class="iconfont icon-downBottom module-title"></span><span class="titleHtml">专属优惠</span></a>
                                </div>
                                <ul class='discount' style="display:none">
                                    <li id="member_birth"><a
                                                href="<?php echo \yii::$app->urlManager->createUrl('discount/birth-list') ?>"
                                                data-toggle="tooltip" data-placement="right" title="生日月优惠模版">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">常规优惠生日月</span></a>
                                    </li>
                                </ul>
                                <ul class='discount' style="display:none">
                                    <li id="member_rank"><a href="<?= Url::toRoute('discount/rank-list') ?>"
                                                            data-toggle="tooltip" data-placement="right" title="会员等级优惠模版">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">常规优惠等级</span></a>
                                    </li>
                                </ul>
                                <ul class='discount' style="display:none">
                                    <li id="member_list"><a
                                                href="<?php echo \yii::$app->urlManager->createUrl('discount/list') ?>"
                                                data-toggle="tooltip" data-placement="right" title="优惠列表">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">限时优惠列表</span></a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ($v ==6): ?>
                            <div class="module">
                                <div class="module-top"><a data-toggle="tooltip" data-placement="right" title="产品与服务2"><span
                                                class="iconfont icon-downBottom module-title"></span><span class="titleHtml">最新资讯</span></a>
                                </div>
                                <!--       <ul class='news' style="display:none">
                            <li id='send'><a href="<?= Url::toRoute(['news/add', 'type' => 1]) ?>" data-toggle="tooltip"
                                             data-placement="right" title="新增待发布资讯">
                                    <div class="icoBox pull-left"><span class=""></span></div>
                                    <span class="fontBox pull-left">新增待发布资讯</span></a>
                            </li>
                        </ul> -->
                                <ul class='news' style="display:none">
                                    <li id="rank"><a href="<?= Url::toRoute('news/member-rank-list') ?>" data-toggle="tooltip"
                                                     data-placement="right" title="会员权益模版">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">会员等级模版</span></a>
                                    </li>
                                </ul>
                                <ul class='news' style="display:none">
                                    <li id='expire'><a href="<?= Url::toRoute(['news/expire-list']) ?>"
                                                       data-toggle="tooltip" data-placement="right" title="会员到期模版">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">会员到期模版</span></a>
                                    </li>
                                </ul>
                                <ul class='news' style="display:none">
                                    <li id='news_birth'><a href="<?= Url::toRoute('news/birth-news') ?>" data-toggle="tooltip"
                                                           data-placement="right" title="资讯列表">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">生日月资讯</span></a>
                                    </li>
                                </ul>
                                <ul class='news' style="display:none">
                                    <li id='list'><a href="<?= Url::toRoute('news/list') ?>" data-toggle="tooltip"
                                                     data-placement="right" title="资讯列表">
                                            <div class="icoBox pull-left"><span class=""></span></div>
                                            <span class="fontBox pull-left">积分资讯列表</span></a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <style>
        .layui-form-select dl {
            z-index: 10000;
        }
        .layui-form-label {
            width: 150px
        }

        legend {
            width: auto;
            border-bottom: none;
            margin-bottom: 0px;
        }

        .layui-layer-title {
            background: #09c;
            color: #FFF;
            border: none;
        }
    </style>
    <div class="admin-right-box" style="height:900px;overflow-x: hidden;background: #FFF">
        <div class="admin-right-main" >
            <fieldset class="layui-elem-field">
                <legend></legend>
                <div class="layui-field-box">
                    <?= $content; ?>
                </div>
            </fieldset>

        </div>
    </div>
</div>
<script type="text/javascript">
    window.onresize=function(){
        var navh=$('.navbar').height();
        //var bh=window.screen.availHeight;
        var bh = window.innerHeight;
        var hei=bh-navh;
        $('.admin-right-box').height(hei+'px');
    }
    var navh=$('.navbar').height();
    var bh=window.screen.availHeight;
    var bh = window.innerHeight;
    var hei=(bh-navh);
    $('.admin-right-box').height(hei+'px');

</script>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
