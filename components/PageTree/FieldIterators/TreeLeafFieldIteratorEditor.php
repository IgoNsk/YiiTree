<?php

  class TreeLeafFieldIteratorEditor extends TreeLeafFieldIterator {
  
    protected $defaultHtmlOptions = array(
      "class"=>"span12"
    );
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      $options = array_merge($this->defaultHtmlOptions, $options);
      
      ob_start();
      echo $form->labelEx($this->model, $fieldName);
      $form->widget('ext.editMe.widgets.ExtEditMe', array(
          'model'=>$this->model,
          'attribute'=>$fieldName
      ));
      echo $form->error($this->model, $fieldName);
      
      return ob_get_clean();
    }
  }
