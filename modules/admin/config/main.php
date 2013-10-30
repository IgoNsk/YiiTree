<?php

  return array(
	  'user'=>array(
      'class'=>'WebUser',
      'loginUrl'=>array("admin/default/login")
    ),
    'authManager'=>array(
      'class'=>'PhpAuthManager',
      'defaultRoles' => array('guest'),
    ),
    'errorHandler'=>array(
      'class'=>'CErrorHandler',
      'errorAction'=>'admin/default/error',
    )
	);
