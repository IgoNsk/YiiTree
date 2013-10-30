<?php 

  class TreeRelation extends CActiveRecord {
    
    const TYPE_OBJECT_TREE = 1;
    const TYPE_CLASS_TREE  = 2;
    
  	/**
  	 * Returns the static model of the specified AR class.
  	 * @param string $className active record class name.
  	 * @return Widget the static model class
  	 */
  	public static function model($className=__CLASS__)
  	{
  		return parent::model($className);
  	}
  	
  	public function getRelationTypes() {
    
      return array(TYPE_OBJECT_TREE, TYPE_CLASS_TREE);
    }
    
    public static function buildNode($id, $type = TreeRelation::TYPE_OBJECT_TREE){
    
      $sql = "
        insert into Relation (Page, Node, Level, Type)
        select
         Object.Id as Page,
         ifnull(ParentObject.Id, 0) as Node,
         0 as Level,
         :type as Type
  
        from
          Object
          left join Object as ParentObject 
            on ParentObject.Id = Object.ParentId
  
        where
          Object.Id = :id
              
        union
        select
         Object.Id as Page,
         Relation.Node,
         Relation.Level+1 as Level,
         :type as Type
         
        from
          Relation
          join Object as ParentPage 
            on ParentPage.Id = Relation.Page
          join Object 
            on Object.ParentId = ParentPage.Id
  
        where
          Object.Id = :id
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindParam(":id", $id, PDO::PARAM_INT);
      $command->bindParam(":type", $type, PDO::PARAM_INT);
      return $command->execute();
    } 
  
  	/**
  	 * @return string the associated database table name
  	 */
  	public function tableName() {
  	
  		return 'Relation';
  	}
  	
  	public function primaryKey() {
  	
      return "Id"; 
    }
  }
