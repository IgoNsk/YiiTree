<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'admin-user-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array( 
    'validateOnSubmit'=>true
  )
)); ?>

	<?php echo $form->textFieldRow($model,'login',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span5','maxlength'=>60, 'value'=>'')); ?>

	<?php echo $form->textFieldRow($model,'caption',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->radioButtonListInlineRow($model, 'role', $model->getRoleValues(), array()); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Добавить' : 'Изменить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
