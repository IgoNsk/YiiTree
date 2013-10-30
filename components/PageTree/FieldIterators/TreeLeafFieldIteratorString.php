<?php

  class TreeLeafFieldIteratorString extends TreeLeafFieldIterator {
  
    protected $defaultHtmlOptions = array(
      "class"=>"span12"
    );
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      $options = array_merge($this->defaultHtmlOptions, $options);
      return $form->textFieldRow($this->model, $fieldName, $options);
    }
  }
