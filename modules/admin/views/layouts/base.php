<!DOCTYPE html>
<html lang="ru">
<head>
<?php 
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->bootstrap->register();
Yii::app()->clientScript->registerScriptFile($this->assetsBase."/js/base.js");
Yii::app()->clientScript->registerCssFile($this->assetsBase."/css/base.css");
?>
  <link rel="icon" href="http://www.top-page.ru/files/top-page/Design/favicon.ico">
  <link rel="shortcut icon" href="http://www.top-page.ru/files/top-page/Design/favicon.ico">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta property="og:image" content="http://www.top-page.ru/files/top-page/Design/logo_small.png" />
  <meta name="description" content="Top-page: управление сайтом"/>
  <title><?php echo CHtml::encode($this->title);?></title>
</head>
<body>
  <div id="head">
<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'На сайт', 'url'=>array('/site/index'), array('target'=>'_blank')),
                array('label'=>'На стартовую', 'url'=>array('/admin/default/index')),
                array('label'=>'Сбросить кеш', 'url'=>array("/admin/default/clearCache"), array('target'=>'_blank')),
                array('label'=>'Выйти ('.Yii::app()->user->name.')', 'url'=>array('/admin/default/logout'), 'visible'=>!Yii::app()->user->isGuest)
            ),
        ),
    ),
)); ?>
  </div>
  <div class="container" id="page">
    <?php if(isset($this->breadcrumbs)):?>
      <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
              'links'=>$this->breadcrumbs,
      )); ?><!-- breadcrumbs -->
    <?php endif?>
  
    <div class="row-fluid">
      <div class="span9">
        <?php echo $content;?>
      </div>
      <div class="span3">
        <?php if(isset($this->menu)):?>
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
                'title'=>'Действия',
            ));
        $this->widget('bootstrap.widgets.TbMenu', array(
          'items'=>$this->menu,
          'htmlOptions'=>array('class'=>'operations'),
        ));
        $this->endWidget();
        ?>
        <?php endif?>
      </div>
    </div>
    <div class="clear"></div>
    <div id="footer">
      Copyright &copy; <?php echo date('Y'); ?> by Top-Page.<br/>
      All Rights Reserved.<br/>
      <?php echo Yii::powered(); ?>
    </div><!-- footer -->
  </div>
</body>
</html>
