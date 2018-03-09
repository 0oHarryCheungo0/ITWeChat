<?php use yii\helpers\Url; ?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.css') ?>">
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.min.css') ?>">
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.js') ?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.min.js') ?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.js') ?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.min.js') ?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.js') ?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.min.js') ?>"></script>
<div class="am-panel am-panel-default">
    <table class="am-table am-table-bordered am-table-striped am-table-compact">
        <tr>
            <th>标题</th>
            <td class="am-text-middle"><?= $detail['title']; ?></td>
        </tr>
        <tr>
            <th>内容</th>
            <td class="am-text-middle"><?= $detail['content']; ?></td>
        </tr>
    </table>
</div>