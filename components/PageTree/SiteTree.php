<?php
  
  class SiteTree extends CApplicationComponent {
  
    private $requestPage = null;
    public $fileStoragePath = "application.data.treeObjects";
    private $id = 1;
    
    public function getId() {
    
      return $this->id;
    }
    
    public function registerMetaTags($item) {
    
      Yii::app()->clientScript->registerMetaTag($item->MetaDescription, 'description');
      Yii::app()->clientScript->registerMetaTag($item->MetaKeywords, 'keywords');
    }
    
    public function setRequestPage(TreeLeaf $page) {
    
      $this->requestPage = $page;
      $this->registerMetaTags($this->requestPage);
    }
    
    public function setHost($host) {
    
      
    }
    
    public function getRequestPage() {
    
      return $this->requestPage;
    }
  }
