<?php

class ObjectPage extends CActiveRecord {
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Widget the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
	
		return 'ObjectPage';
	}
	
	public function primaryKey() {
	
    return "ObjectId"; 
  }
}
