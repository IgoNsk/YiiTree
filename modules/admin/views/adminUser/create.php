<?php
$this->breadcrumbs=array(
	'Администраторы сайта'=>array('index'),
	'Добавить',
);

$this->menu=array(
	array('label'=>'К списку','url'=>array('index')),
);
?>

<h1>Создание администратора сайта</h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
