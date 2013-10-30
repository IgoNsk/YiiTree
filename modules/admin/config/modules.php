<?php

return array(
  'tree'=>array(
    'label'=>'Дерево сайта',
    'description'=>'Управление страницами сайта.',
    'controller'=>'SiteTreeManagement',
    'access'=>array(
      'role'=>array('moderator')
    )
  ),
  'admins'=>array(
    'label'=>"Администраторы",
    'description'=>'Управление аккаунтами администраторов сайта.',
    'controller'=>'AdminUser',
    'access'=>array(
      'role'=>array('administrator')
    )
  )
);
