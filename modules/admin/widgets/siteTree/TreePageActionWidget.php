<?php

  class TreePageActionWidget extends CWidget {
  
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
      
      $model = $this->owner->model;
      $actions = array();
      foreach ($model->adminActions as $name=>$action) {
        $item = array(
          "label"=>$action["caption"],
          "url"=>!empty($action["url"]) ? $action["url"] : $this->owner->createUrl($action["route"], array($this->options['routeParamName']=>$model->Id)),
//           'htmlOptions'=>array(
// //             'submit'=>$this->owner->createUrl($action["route"], array($this->options['routeParamName']=>$model->Id)), 
//             'confirm'=>'Вы точно хотите удалить этот объект вместе с его детьми?'
//           )
        );
        
        if (!empty($action["linkOptions"])) {
          $item["linkOptions"] = $action["linkOptions"];
        }
        
        // вложенные пункты меню
        if (!empty($action["items"])) {
          if (is_array($action["items"])) {
            $item["items"] = $action["items"];
          }
          else if (is_callable($action["items"])) {
            $item["items"] = call_user_func_array($action["items"], array());
          }
        }
        
        $actions[] = $item;
      }
    
      $currentAction = $this->owner->getAction()->id;
      if (!isset($model->adminActions[$currentAction])) {
        list($currentAction) = array_keys($model->adminActions);
      }
      
      $this->render("actionWidget", array(
        "htmlOptions"=>$this->htmlOptions, 
        "actions"=>array(
          "items"=>$actions,
          "current"=>$model->adminActions[$currentAction]["caption"]
        )
      ));
    }
  }
