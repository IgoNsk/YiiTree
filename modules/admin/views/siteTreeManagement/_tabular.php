<fieldset>
 
  <?php if (!empty($tab->description)) : ?>
  <legend><?php echo CHtml::encode($tab->description); ?></legend>
  <?php endif; ?>
 
  <?php foreach ($tab->fields as $field) : ?>
    <div class="controls controls-row">
    <?php echo $field->render($form);?>
    </div>
  <?php endforeach; ?>
</fieldset>
