<?php

  class SiteTreeWidget extends CWidget {
  
    private $defaultHtmlOptions = array(
      "id"=>"siteTree__js_tree",
      "treeLoadRoute"=>"",
      "node"=>1
    );
    public $htmlOptions = array();
  
    public function run() {
    
      $htmlOptions = array_merge(
        $this->defaultHtmlOptions,
        $this->htmlOptions
      );
    
      $this->render("default", array("htmlOptions"=>$htmlOptions));
    }
  }
