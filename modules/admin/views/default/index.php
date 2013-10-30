<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	"Админка",
);
?>
<h1>Добро пожаловать в админскую часть проекта top-page!</h1>
<p>Сюда можно добавить какой нибудь приветствующий текст - над этим надо подумать.</p>
<?php
  $this->widget('admin.widgets.adminModules.FullListWidget', array(
    
  ));
 ?>
