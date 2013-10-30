<?php 

  class FullListWidget extends CWidget {
  
    public function run() {
      $model = AdminModulesList::getList();
      $this->render("fullList", array('model'=>$model));
    }
  }
