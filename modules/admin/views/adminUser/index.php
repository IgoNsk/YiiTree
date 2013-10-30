<?php
$this->breadcrumbs=array(
	'Упрвляющие сайтом',
);

$this->menu=array(
	array('label'=>'Создать пользователя','url'=>array('create')),
	array('label'=>'Управлять пользователями','url'=>array('admin')),
);
?>

<h1>Управляющие сайтом</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
