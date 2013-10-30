<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'admin-site-object-form',
	'enableAjaxValidation'=>true,
	'clientOptions' => array( 
    'validateOnSubmit'=>true
  ),
  'htmlOptions'=>array(
    'enctype'=>'multipart/form-data'
  )
)); ?>

<?php echo $form->errorSummary($model, "Пожалуйста, исправьте следующие ошибки:"); ?>

<?php 
  
  $tabs = array();
  $count = 0;
  foreach($model->fieldTabs as $name=>$tab) {
    $tabs[] = array(
      'active'=>$count++ === 0,
      'label'=>$tab->label,
      'title'=>$tab->description,
      'content'=>$this->renderPartial('_tabular', array('form'=>$form, 'model'=>$model, 'tab'=>$tab), true),
    );
  }
?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(
    'tabs'=>$tabs,
)); ?>

<div class="form-actions">
  <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>'Сохранить')); ?>
  <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'htmlOptions'=>array('name'=>'SaveAndNext', "value"=>"1"), 'label'=>'Сохранить и следующий')); ?>
</div>

<?php $this->endWidget(); ?>
