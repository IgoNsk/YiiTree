<?php
$this->breadcrumbs=array(
	'Дерево сайта'
);
?>

<?php if (!empty($errors)) : ?>
<div class="error">
  <h2>Ошибка:</h2>
  <div><?php echo CHtml::encode($errors); ?></div>
</div>
<?php return; ?>
<?php endif; ?>

<h1><?php echo CHtml::encode($model->Caption); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
