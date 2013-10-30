<?php

  class TreeLeafFieldIteratorCheckbox extends TreeLeafFieldIterator {
  
    protected $defaultHtmlOptions = array(
    );
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      $options = array_merge($this->defaultHtmlOptions, $options);
      return $form->checkboxRow($this->model, $fieldName, $options);
    }
  }
