<?php

class ObjectCatalogRubric extends CActiveRecord {
  
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName() {
	
		return 'ObjectCatalogRubric';
	}
	
	public function primaryKey() {
	
    return "ObjectId"; 
  }
}
