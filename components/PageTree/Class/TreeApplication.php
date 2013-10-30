<?php

  class TreeApplication extends TreeObjectPage {
    
    public static $classId = 1;
    public static $classLabel = "Сайт";
    
  	public static function model($className=__CLASS__) {
  	
  		return parent::model($className);
  	}
  	
    protected function beforeValidate() {
  
      if (empty($this->Caption)) {
        $this->Caption = $this->Address;
      }
    
      return parent::beforeValidate();
    }
    
    protected function beforeSave() {
  
      if (empty($this->Title)) {
        $this->Title = $this->Caption;
      }
      if (empty($this->Header)) {
        $this->Header = $this->Caption;
      }
    
      return TreeObjectNode::beforeSave();
    }
    
    public function childrenClasses() {
  
      return array(
        "TreeObjectPage"=>array(),
        "TreeCatalogRubric"=>array(),
      );
    }
  	
  	protected function tabs() {
  	
      return array_merge(
        array(
          "main"=>array(
        	  "label"=>"Основное",
          )
        ),
        parent::tabs()
      );  
    }
    
    public function fields() {
    
      return array_merge(
        array(
          "Address"=>array(
            "label"=>"Домен",
            "type"=>"string",
            "tab"=>"main",
          ),
        ),
        parent::fields()
      );
    }
    
    protected function relatedTables() {
    
      return array_merge(
        parent::relatedTables(),
        array(
          "applicationTable"=>array("ObjectApplication"),
        )
      );
    }
  }
