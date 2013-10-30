<?php 
$assets = Yii::app()->controller->assetsBase;
Yii::app()->clientScript->registerScriptFile($assets.'/package/jstree/jquery.jstree.js');
Yii::app()->clientScript->registerScript(null,
  'var jsTreeOptions = '.CJavaScript::encode($htmlOptions).';', CClientScript::POS_HEAD
);
Yii::app()->clientScript->registerScriptFile($assets.'/js/jstree.js');
?>
<div id="siteTreeWidget">
  <div id="<?php echo $htmlOptions["id"]; ?>"></div>
</div>
