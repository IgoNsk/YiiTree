<?php

  class AdminModulesList {
  
    public static function getList($checkAccess = true) {
    
      $list = array();
    
      $file = Yii::getPathOfAlias('admin.config.modules').'.php';
      $data = include $file;
      $user = Yii::app()->getUser();
      
      foreach ($data as $moduleName=>$module) {
        if ($checkAccess && !empty($module['access'])) {
          
          $access = $module['access'];
          if (!empty($access['role'])) {
            $hasAccess = false;
            foreach ($access['role'] as $role) {
              if ($user->checkAccess($role)) {
                $hasAccess = true;
                continue;
              }
            }
            if (!$hasAccess) {
              continue;
            }
          }
        }
        
        $list[$moduleName] = $module;
      }
      return $list;
    }
  }
