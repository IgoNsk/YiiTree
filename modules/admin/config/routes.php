<?php

return array(
  'admin'=>'admin',
  
  'admin/catalogProperty/<action:\w+>'=>'catalog/CatalogProperty/<action>',
  
  'admin/SiteTreeManagement/node-<node:\d+>'=>'admin/SiteTreeManagement/view',
  'admin/SiteTreeManagement/node-<node:\d+>/module/<module:\w+>/*'=>'admin/SiteTreeManagement/module',
  'admin/SiteTreeManagement/node-<node:\d+>/<action:\w+>/class-<classId:\d+>'=>'admin/SiteTreeManagement/<action>',
  'admin/SiteTreeManagement/node-<node:\d+>/<action:\w+>'=>'admin/SiteTreeManagement/<action>',
  
  'admin/<controller:\w+>'=>'admin/<controller>',
  'admin/<controller:\w+>/<action:\w+>'=>'admin/<controller>/<action>',
);
