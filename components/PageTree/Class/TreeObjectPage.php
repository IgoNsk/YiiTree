<?php

  class TreeObjectPage extends TreeObjectNode {
  
    public static $classId = 2;
    public static $classLabel = "Страница";
    protected $template = "application.views.treeObject.TreeObjectPage.view";
    
    public static function findByUrl($url, $siteId = null) {
  
      if ($siteId === null) {
        $siteId = Yii::app()->site->id;
      }
   
      $sql = "
        select
          Object.Id,
          DictClass.ClassName
          
        from
          ObjectPageUrl
          join Object
            on Object.Id = ObjectPageUrl.ObjectId
          join DictClass
            on DictClass.Id = Object.Class
            
        where
          ObjectPageUrl.Url = :Url
          and ObjectPageUrl.SiteId = :SiteId
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindValue(":Url", $url, PDO::PARAM_STR);
      $command->bindValue(":SiteId", $siteId, PDO::PARAM_INT);
      $row = $command->queryRow();
      
      if (empty($row)) {
        return null;
      }
      
      $item = self::createObject($row["ClassName"], $row["Id"]);
      return $item;
    }
    
    public function getUrl() {

      return "/".$this->urlTable->Url."/";
    }
    
    public function setUrl($url) {

      $url = trim($url, '/');
      $this->urlTable->Url = $url;
      $this->urlTable->SiteId = $this->getSiteNode()->Id;
    }
    
  	public static function model($className=__CLASS__) {
  	
  		return parent::model($className);
  	}
  	
  	public function afterSetPath() {
    
      $this->regenerateUrl();
    }
  
    public function regenerateUrl(){
      
      $url = $this->generateUrl();    
      $this->Url = $url;
      
      // TODO 
      // надо переделать под ситуацию, когда встречается узел, но его детей надо обойти
      foreach ($this->childrens as $children) {
        if (method_exists($children, "regenerateUrl")) {
          $children->regenerateUrl();
          $children->save();
        }
      }
    }
    
    public function generateUrl() {
    
      $path = array();
      for ($parent = $this; $parent !== null; $parent = $parent->parent) {
        if (isset($parent->Path) && ($itemPath = $parent->Path)) {
          $path[] = $itemPath;
        }
      }
      
      $pathString = "";
      if (!empty($path)) {      
        $path = array_reverse($path);
        $pathString = implode('/', $path);
      }
      return $pathString;
    }
    
    protected function beforeSave() {
  
      if (empty($this->Title)) {
        $this->Title = $this->Caption;
      }
      if (empty($this->Header)) {
        $this->Header = $this->Caption;
      }
      
      if (empty($this->Path)) {
        $this->Path = $this->OrderBy;
      }
    
      return parent::beforeSave();
    }
    
    public function childrenClasses() {
  
      return array(
        "TreeObjectPage"=>array()
      );
    }
    
    protected function fields() {
    
      return array(
        "Caption"=>array(
          "label"=>"Название",
          "type"=>"string",
          "tab"=>"content",
          "rules"=>array(
            array("required", 'on'=>array("insert", "update")),
          )
        ),
        "Description"=>array(
          "label"=>"Анонс",
          "type"=>"editor",
          "tab"=>"content"
        ),
        "Content"=>array(
          "label"=>"Содержание",
          "type"=>"editor",
          "tab"=>"content"
        ),
        "Header"=>array(
          "label"=>"Заголовок H1",
          "tab"=>"seo"
        ),
        "Title"=>array(
          "tab"=>"seo"
        ),
        "Path"=>array(
          "label"=>"Путь",
          "tab"=>"seo"
        ),
        "MetaKeywords"=>array(
          "label"=>"Keywords",
          "type"=>"area",
          "tab"=>"seo"
        ),
        "MetaDescription"=>array(
          "label"=>"Description",
          "type"=>"area",
          "tab"=>"seo"
        ),
      );
    }
    
    protected function tabs() {
  	
      return 
        array(
          "content"=>array(
        	  "label"=>"Контент",
          ),
          "seo"=>array(
        	  "label"=>"SEO",
        	  "description"=>"Все seo поля объекта"	
          )
        );
    }
    
    protected function relatedTables() {
    
      return array(
        "pageTable"=>array("ObjectPage"),
        "urlTable"=>array("ObjectPageUrl")
      );
    }
  }
