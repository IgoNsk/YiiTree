<?php
// $this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
  'Рабочий стол'=>array('default/index'),
	'404 - запрашиваемая странциа не найдена',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>
