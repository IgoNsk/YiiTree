<?php

  class TreeLeafFieldIteratorDropdown extends TreeLeafFieldIterator {
  
    public $data;
  
    protected $defaultHtmlOptions = array(
      "class"=>"span12"
    );
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      $options = array_merge($this->defaultHtmlOptions, $options);
      
      $data = array();
      if ($this->data instanceof IDataProvider) {
        $data[""] = "";
        foreach ($this->data->getData() as $item) {
          $data[$item["Value"]] = $item["Caption"];
        }
      }
      else if (is_array($this->data)) {
        $data = $this->data;
      }
      
      return $form->dropDownListRow($this->model, $fieldName, $data, $options);
    }
  }
