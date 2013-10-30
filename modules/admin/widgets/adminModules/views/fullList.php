<?php if (!empty($model)) : ?>
<p>А пока перечислим список всех досутпных модулей для управления проектом:</p>
<ul class="moduleList">
  <?php foreach ($model as $item) : ?>
  <li class="item">
    <div class="linkBlock"><?php 
      echo CHtml::link(
        CHtml::encode($item['label']), 
        Yii::app()->controller->createUrl($item["controller"]."/index")
      ); ?></div>
    <div class="descriptionBlock"><?php echo CHtml::encode($item['description']);?></div>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
