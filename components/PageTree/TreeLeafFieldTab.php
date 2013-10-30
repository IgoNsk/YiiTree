<?php
  
  Yii::import("application.components.PageTree.FieldIterators.TreeLeafFieldIterator");
  
  class TreeLeafFieldTab extends CComponent {
  
    private $name;
    
    public $label;
    public $description;
    public $model;
    
    protected $defaultOptions = array();
    
    private $_fields = array();
    
    public function __construct(CActiveRecord $model, $name, array $options = array()) {
         
      $this->model = $model;
      $this->name  = $name;
      $options = array_merge($this->defaultOptions, $options);
      foreach ($options as $key=>$value) {
        $this->$key = $value;
      }
    }
    
    public function getName() {
      
      return $this->name;
    }
    
    /*  
    * array(
    *   "caption"=>array(
    *     "label"=>"Название",
    *     "type"=>["string", "checkbox", "textarea", "datetime", "memoeditor", array('select', array('1', '2')], default="string" // может это и не надо
    *     "default"=>["123", 'значение', function(){ return $value}]           
    *   ),
    * );  
    */
    public function addField($name, $options) {
      
      if (!empty($options["type"])) {
        $type = $options["type"];
        unset($options["type"]);
      }
      else {
        $type = "string";
      }
      
      $classmap = array(
        "string"=>"TreeLeafFieldIteratorString",
        "area"=>"TreeLeafFieldIteratorArea",
        "editor"=>"TreeLeafFieldIteratorEditor",
        "dropdown"=>"TreeLeafFieldIteratorDropdown",
        "file"=>"TreeLeafFieldIteratorFile",
        "boolean"=>"TreeLeafFieldIteratorCheckbox"
      );
      
      $formatType = strtolower($type);
      if (isset($classmap[$formatType])) {
        $classname = $classmap[$formatType];
        if(!class_exists($classname, false)) {
          Yii::import("application.components.PageTree.FieldIterators.$classname");
        }
      }
      else {
        $classname = Yii::import($type, true);
      }
      
      if (!$classname) {
        throw new CException("Unknown type of field {$type}");
      }
      
      $field = new $classname($name, $options);
      $field->tab = $this;
      $field->init();
      
      return $this->_fields[$name] = $field;
    }
    
    public function getFields() {
    
      return $this->_fields;
    }
  }
