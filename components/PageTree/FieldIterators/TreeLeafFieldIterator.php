<?php

  abstract class TreeLeafFieldIterator extends CComponent implements ITreeLeafFieldIterator{
  
    public $label;
    public $labelAdmin;
    public $default;
  
    public $options = array();
  
    protected $name;
    protected $tab;
    protected $defaultOptions = array();
    
    public function __construct($name, array $options = array()) {
    
      $this->name  = $name;
      $options = array_merge($this->defaultOptions, $options);
      
      if (empty($options["label"])) {
        $options["label"] = $name;//$options["name"];
      }
      if (empty($options["labelAdmin"])) {
        $options["labelAdmin"] = $options["label"];
      }
      
      foreach ($options as $key=>$value) {
        $this->$key = $value;
      }
      
      Yii::setPathOfAlias('treeObjectBehaviors', Yii::getPathOfAlias("application.components.PageTree.FieldIterators")."/behavior");
    }
  
    public function getName() {
       
      return $this->name;
    }
    
    public function getModel() {
    
      return $this->tab->model;
    }
    
    public function init() {
    
    }
    
    public function setTab(TreeLeafFieldTab $tab) {
    
      $this->tab = $tab;
    }
    
    public function getTab() {
    
      return $this->tab;
    }
  }
