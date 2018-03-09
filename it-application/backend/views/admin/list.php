
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<form action="index.php?r=admin/list" method="get">
	<input type="text" name="search">
	<input type="submit" >
</form>


<?php
foreach ($list as $k => $v) {
    echo $v->username;
}
echo "<br>";
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]);

?>
</body>
</html>
