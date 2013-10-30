<?php

class ObjectCatalogItem extends CActiveRecord {
  
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName() {
	
		return 'ObjectCatalogItem';
	}
	
	public function primaryKey() {
	
    return "ObjectId"; 
  }
}
