<?php

  class TreeCatalogRubric extends TreeObjectPage {
  
    public static $classId = 4;
    public static $classLabel = "Каталог: рубрика";
//     protected $template = "application.views.treeObject.TreeCatalogRubric.view";
    
  	public static function model($className=__CLASS__) {
  	
  		return parent::model($className);
  	}
    
    public function childrenClasses() {
  
      return array(
        "TreeCatalogRubric"=>array()
      );
    }
    
    public function behaviors()
    {
      Yii::app()->getModule("catalog");
      
      return array(
        "catalog"=>array(
          "class"=>"CatalogModule.components.CatalogRubricBehavior",
          "rubricFieldId"=>"Id",
          "childrenField"=>"childrens",
          "parentField"=>"parent",
          "onAfterModifyProperty"=>function($event) {
            $event->sender->getOwner()->setChanged(true);
          }
        )
      );
    }
    
    public function getActionMap() {
    
      return array(
        "rubricAdd"=>"/catalog/CatalogProperty/create",
        "rubricEdit"=>"/catalog/CatalogProperty/update",
        "rubricDelete"=>"/catalog/CatalogProperty/delete"
      );
    }
    
    public function getRenderData() {
      
      try {
//         $prop = new CatalogProperty;
//         $prop->attributes = array(
//           "caption"=>"Тест",
//           "type_id"=>1
//         );
//         $this->addCatalogProperty($prop);

//         $prop = CatalogProperty::model()->findByPk(5);
//         $prop->attributes = array(
//           "prev_id"=>null
//         );
//         
//         $this->editCatalogProperty($prop);
        
//         $this->deleteCatalogProperty(5);
//         $this->save();
      }
      catch (CException $e) {
        __($e->getMessage());
      }
      
//       __($this->catalogRubricFields);
//       $this->regenerateCatalogFieldsCache();
      
      return array();
    }
    
    protected function fields() {
    
      $fields = array(
        "Property"=>array(
          "label"=>"Рубрики",
          "type"=>"CatalogModule.components.TreeLeafFieldIteratorCatalogProperty",
          "tab"=>"property",
        ),
      );
      
      return array_merge(parent::fields(), $fields);
    }
    
    protected function tabs() {
  	
      return 
        array(
          "content"=>array(
        	  "label"=>"Контент",
          ),
          "property"=>array(
        	  "label"=>"Свойства",
        	  "description"=>"Настраиваемые поля каталога"	
          ),
          "seo"=>array(
        	  "label"=>"SEO",
        	  "description"=>"Все seo поля объекта"	
          )
        );
    }
    
    protected function relatedTables() {
    
      return array_merge(
        parent::relatedTables(),
        array(
          "catalogRubricTable"=>array("ObjectCatalogRubric")
        )
      );
    }
  }
