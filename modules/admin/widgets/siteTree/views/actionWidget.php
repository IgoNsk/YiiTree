<div class="btn-toolbar">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'primary',
        'buttons'=>array(
            array(
              'label'=>$actions['current'], 
              'items'=>$actions["items"], 
            ),
        ),
    )); ?>
</div>
