<?php

class AdminController extends Controller {

  public $layout = 'base';
  public $title = 'Securella: управление  сайтом';
  public $breadcrumbs = array();
  public $menu = array();
  
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
		  array('allow',
        'actions'=>array('login', 'error'),
        'users'=>array('?'),
			),
			array('allow',
        'roles'=>array('moderator')
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
  
  public function init() {
  
    parent::init();
    
    $this->assetsPath = 'admin.assets';
  }
}
