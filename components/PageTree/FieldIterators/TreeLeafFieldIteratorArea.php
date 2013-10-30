<?php

  class TreeLeafFieldIteratorArea extends TreeLeafFieldIterator {
  
    protected $defaultHtmlOptions = array(
      "class"=>"span12",
      "rows"=>10
    );
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      $options = array_merge($this->defaultHtmlOptions, $options);
      return $form->textAreaRow($this->model, $fieldName, $options);
    }
  }
