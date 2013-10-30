<?php
$this->breadcrumbs=array(
	'Админситраторы сайта'=>array('index'),
	'Поиск',
);

$this->menu=array(
	array('label'=>'Добавить администратора','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('admin-user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Администраторы сайта</h1>
<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'type'=>'striped bordered condensed',
  'id'=>'admin-user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
// 		array(
//       'class'=>'CLinkColumn',
//       'labelExpression'=>'$data->login',
//       'urlExpression'=>'Yii::app()->controller->createUrl("update", array("id"=>$data->id));'
//     ),
    'login',
		'caption',
		array(
      'name'=>'role',
      'value'=>'$data->roleLabel',
    ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
		),
	),
)); ?>
