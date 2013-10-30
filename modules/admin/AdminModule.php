<?php

class AdminModule extends CWebModule {

  public $ipFilters = array();

  public function init()
  {
    parent::init();
    Yii::app()->name = "Reviews - admin panel";
    Yii::app()->setId(null);
    Yii::app()->setComponents(include "config/main.php", false);
    
    // import the module-level models and components
    $this->setImport(array(
      'admin.models.*',
      'admin.components.*',
    ));
  }
	
  /**
   * Checks to see if the user IP is allowed by {@link ipFilters}.
   * @param string $ip the user IP
   * @return boolean whether the user IP is allowed by {@link ipFilters}.
   */
  protected function allowIp($ip) {
    if (empty($this->ipFilters)) {
      return true;
    }
		
    foreach ($this->ipFilters as $filter) {
      if ($filter==='*' || $filter===$ip || (($pos=strpos($filter,'*'))!==false && !strncmp($ip,$filter,$pos))) {
        return true;
      }
    }
    return false;
  }

  public function beforeControllerAction($controller, $action) {
    if(parent::beforeControllerAction($controller, $action)) {
      $route = $controller->id.'/'.$action->id;
      if (!$this->allowIp(Yii::app()->request->userHostAddress) && $route!=='default/error') {
        throw new CHttpException(403, "У вас нет доступа к этой странице.");
      }	
      return true;
    }
    else {
      return false;
    }
  }
}
