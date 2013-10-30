<?php

  class SiteSelectWidget extends CWidget {
  
    private $defaultOptions = array();
    public $options = array();
    
    private $defaultHtmlOptions = array();
    public $htmlOptions = array();
  
    public function run() {
    
      $this->options = array_merge(
        $this->defaultOptions,
        $this->options
      );
      $this->htmlOptions = array_merge(
        $this->defaultHtmlOptions,
        $this->htmlOptions
      );
    
      $items = TreeApplication::model()->findAll();
      $data = array(
        ""=>"Сменить сайт",
      );
      foreach ($items as $item) {
        $value = $this->owner->createUrl($this->options["route"], array($this->options['routeParamName']=>$item->Id));
        $data[$value] = $item->Caption;
      }
    
      $this->render("selectWidget", array("model"=>$data, "htmlOptions"=>$this->htmlOptions));
    }
  }
