<?php

 abstract class TreeLeaf extends CActiveRecord {

  protected $_parent = false;
  
  private $_relatedFields = array();
  private $_isMoved = false;
  private $_isChanged = false;
  private $_childrens = null;
  private $_next      = null;
  private $_prev      = null;
  private $_fieldTabs = array();
  private $_attributeLabels = array();
  private $_initTabs   = false;
  private $_initFields = false;
  private static $_objectById = array();
  private static $_objectByName = array();
  
  public static $classId;
  public static $classLabel;
  
  const CLASS_PATH = "application.components.PageTree.Class";
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
	
		return 'Object';
	}
	
	public function moveInNode($position = null) {
  
    return $this->move($this->ParentId, $position);
  }
	
	public function move($parentId, $position = null) {
    
    // ищем узел родителя
    if (($parentNode = self::findById($parentId)) === null) {
      throw new CException("Object with id {$parentNode} not found!");
    }
    
    if ($position !== null && $position < 0) {
      throw new CException("Incorrect position {$position} for moving!");
    }
    
    if ($position !== null) {
      $sql = "
        select
          Object.OrderBy
          
        from
          Object
          
        where
          Object.ParentId = :parentId
          
        order by
          Object.OrderBy
          
        limit 1 offset :limitShift
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindValue(":parentId", $parentId, PDO::PARAM_INT);
      $command->bindValue(":limitShift", intval($position), PDO::PARAM_INT);
      $orderByNext = $command->queryScalar();
      $orderBy = $orderByNext ? $orderByNext : ($position = 0 ? 1 : null);
      
      // Если записи данного порядка нет, и мы вставляем не первую запись
      // то получается что мы добавляе в конец. будем соотвествено и обрабатывать
      if ($orderByNext === null && intval($position) !== 0) {
        $position = null;
      }
    }
    
    // добавляем в конец
    if ($position === null) {
      $sql = "
        select
          max(Object.OrderBy) as OrderBy
          
        from
          Object
          
        where
          Object.ParentId = :parentId
          
        order by
          Object.OrderBy
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindParam(":parentId", $parentId, PDO::PARAM_INT);
      $orderBy = $command->queryScalar()+1;
      $orderByNext = null;
    }
    
    if ($orderByNext) {
      $sql = "
        update
          Object
          
        set
          Object.OrderBy = Object.OrderBy+1
          
        where
          Object.ParentId = :parentId
          and Object.OrderBy >= :order
          
        order by
          Object.OrderBy desc
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindParam(":parentId", $parentId, PDO::PARAM_INT);
      $command->bindParam(":order", $orderByNext, PDO::PARAM_INT);
      $command->execute();
    }
    
    $this->OrderBy = $orderBy;
    
    // определяем с какого orderBy нам надо всех подвинуть
    if ($parentId == $this->ParentId) {
      $this->setIsMoved(true);
      $this->ParentId = $parentId;
    }
    
    return $this;    
  }
	
	public function getIsMoved() {
  
    return $this->_isMoved;
  }
  
  public function setIsMoved($value = true) {
  
    $this->_isMoved = $value;
  }
	
	public function init() {
    
    $this->initRelatedTables();
    parent::init();
  }
  
  public function adminPrepare(){
  
    $this->initFieldTabs();
    $this->initFields();
  }
  
  public function getClassId() {
    
    return $this::$classId;
  }
  
  public function getClassLabel() {
  
    
    return $this::$classLabel;
  }
  
  private function initRelatedTables() {
   
    $relations = array_keys($this->relatedTables());
    foreach ($relations as $name) {
      $relation = $this->getMetaData()->relations[$name];
      $class = CActiveRecord::model($relation->className);
      foreach ($class->attributeNames() as $attr) {
        if (!isset($this->_relatedFields[$attr])) {
          $this->_relatedFields[$attr] = $name;
        }
      }
    }
  }

  public static function findById($id) {
  
    if (($item = self::getFromStaticById($id)) === null) {
      
      $sql = "
        select
          DictClass.ClassName
          
        from
          Object
          join DictClass
            on DictClass.Id = Object.Class
            
        where
          Object.Id = :id
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindParam(":id", $id, PDO::PARAM_INT);
      $className = $command->queryScalar();

      if (!$className) {
        return null;
//         throw new CException("Class for object with id {$id} not found!");
      }
      
      $item = self::createObject($className, $id);
    }

    return $item;
  }
  
  public static function findByName($name, $siteId = null) {
  
    if (($item = self::getFromStaticByName($name, $siteId)) === null) {
      
      $sql = "
        select
          Object.Id, 
          DictClass.ClassName
          
        from
          Object
          join DictClass
            on DictClass.Id = Object.Class
            
        where
          Object.Name = :name
      ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindParam(":name", $name, PDO::PARAM_STR);
      $row = $command->queryRow();
      $className = $row['ClassName'];
      if ($className === null) {
        throw new CException("Object with name {$name} not found!");
      }
      
      $item = self::createObject($className, $row['Id']);
    }

    return $item;
  }
  
  public static function createObject($className, $id) {
      
    $fullClassName = self::CLASS_PATH.".".$className;
    Yii::import($fullClassName);
    $item = CActiveRecord::model($className)->findByPk($id);

    return $item;
  }

  private function findInRelated($fieldName) {
    
    if (isset($this->_relatedFields[$fieldName])) {
      $name = $this->_relatedFields[$fieldName];
      return $this->getRelated($name);
    }        
		return null;
  }
  
  public function __get($name) {
  
    $getter = "get".$name;
    if (method_exists($this, $getter)) {
	    return $this->$getter();
    }
  
    if (($related = $this->findInRelated($name)) !== null) {
      return $related->$name;
    }
    else {
      return parent::__get($name);
    }
  }
  
  public function setChanged($value = true) {
    
    $this->_isChanged = $value;
  }
  
  public function isChanged() {
    
    return $this->_isChanged;
  }
  
  public function __set($name, $value) {
  
    $this->setChanged(true);
    
    $this->initFieldTabs();
    $this->initFields();
    
    $setter = "set".$name;
    if (method_exists($this, $setter)) {
      $this->$setter($value);
	    return true;
    }
  
    if (($related = $this->findInRelated($name)) !== null) {
      $related->$name = $value;
      
      $callback = 'afterSet'.$name;
		  if (method_exists($this, $callback)) {
		    $this->$callback();
      }
    }
    else {
      return parent::__set($name, $value);
    }
    
    return true;
  }
  
  public function __isset($name) {
  
    if (($related = $this->findInRelated($name)) !== null) {
      return true;
    }
    else {
      return parent::__isset($name);
    }
  }
  
  private function saveInStatic() {
  
    self::$_objectById[$this->Id] = $this;
    
    if ($this->Name) {
      self::$_objectByName[$this->Name] = $this->Id;
    }
  }
  
  public function getFromStaticByName($name) {
  
    if (isset(self::$_objectByName[$name])) {
      return self::findById(self::$_objectByName[$name]);
    }
    
    return null;
  }
  
  public function getFromStaticById($id) {
  
    if (isset(self::$_objectById[$id])) {
      return self::$_objectById[$id];
    }
    
    return null;
  }
  
  protected function afterFind() {
    
    $this->saveInStatic();
    parent::afterFind();
  }
  
  public function getParent() {
  
    if ($this->_parent === false) {
      if ($this->ParentId === null) {
        $this->_parent = null;
      }
      else {
        $this->_parent = self::findById($this->ParentId);
//         $className = get_class($this);
//         $this->_parent = $className::model()->findByPk($this->ParentId);
      }
    }
    
    return $this->_parent;
  }
 
	/*
  * Возвращает массив, который состоит из описания полей объекта
  * array(
  *   "caption"=>array(
  *     "label"=>"Название",
  *     "labelAdmin"=>"Название поля для админки", default=label
  *     "field"=>["Caption", "Page.Header"],
  *     "alias"=>default=field  // ????
  *     "type"=>["string", "checkbox", "textarea", "datetime", "memoeditor", array('select', array('1', '2')], default="string" // может это и не надо
  *     "tab"=>["название табы из списка"] default="main"
  *     "default"=>["123", 'значение', function(){ return $value}]           
  *   ),
  * );  
  */
	protected function fields() {
    
    return array();
  }
  
  public function childrenClasses() {
  
    return array();
  }
	
	/*
	* Список используемых групп полей
	* array(
	*   "main"=>array(
	*     "label"=>"Основное",
	*     "deafultShow"=>default=true
	*     "description"=>"Тут может быть какое то текстовое описнаие табы"	
	*   ) 
	* );
	*/  	
	protected function tabs() {
	
    return array();
  }
  
  private function initFieldTabs() {
  
    if ($this->_initTabs) {
      return;
    }
    $this->_initTabs = true;
  
    $tabs = array();
    foreach ($this->tabs() as $name=>$tab) {
      $tabs[$name] = new TreeLeafFieldTab($this, $name, $tab);
    }
    $this->_fieldTabs = $tabs;
  }
  
  private function initFields() {
  
    if ($this->_initFields) {
      return;
    }
    $this->_initFields = true;
  
    list($defaultTab) = array_keys($this->_fieldTabs);
    $safeFields = array();
    $defaultScenarios = array("insert", "update");
    foreach ($this->fields() as $name=>$field) {
    
      // таба где лежит поле
      if (!empty($field["tab"])) {
        $tabName = $field["tab"];        
        unset($field["tab"]);
      }
      else {
        $tabName = $defaultTab;
      }
      if (!isset($this->_fieldTabs[$tabName])) {
        throw new CException("Tab with name «{$tabName}» does not exist");
      }
      $tab = $this->_fieldTabs[$tabName];
      
      // если явно не указано, то по умолчанию все атрибуты безопасные для вставки и редактирования
      if (empty($field["unsafe"])) {
        if (in_array($this->scenario, $defaultScenarios)) {
          $safeFields[] = $name;
        }
      }
      else {
        unset($field["unsafe"]);
      }
      
      // правила валидации
      if (isset($field["rules"])) {
        foreach ($field["rules"] as $rule) {
        
          $on = isset($rule["on"]) ? $rule["on"] : array("insert", "update");
        
          if (in_array($this->scenario, $on)) {
            $attr = array_splice($rule, 1);
            $fileValidator = CValidator::createValidator(
              $rule[0], $this, $name, $attr
            );
            $this->validatorList->add($fileValidator);
          }
        }
        unset($field["rules"]);
      }
      
      $fieldItem = $tab->addField($name, $field);
      $this->_attributeLabels[$fieldItem->getName()] = $fieldItem->label;
    }
    
    if (!empty($safeFields)) {
      $fileValidator = CValidator::createValidator(
        "safe", $this, implode(",", $safeFields)
      );
      $this->validatorList->add($fileValidator);
    }
  }
  
  public function getFieldTabs() {
  
    $this->initFieldTabs();
    $this->initFields();
  
    return $this->_fieldTabs;
  }

	protected function relatedTables() {
	
    return array();
  }
  
  protected function insertAfter(array $data, $index, array $params){
  
    $index = 0;
    $size  = count($data);
    foreach (array_keys($data) as $key) {
    
      if ($key == $index) {
        break;
      }
      $index++;
    }
    
    if ($index >= $size) {
      $result = array_merge($data, $params);
    }
    else {
      $data1 = array_slice($data, 0, $index+1, true);
      $data2 = array_slice($data, $index+1, null, true);
      $result = array_merge($data1, $params, $data2);
    }
    
    return $result;
  }
  
  public function relations() {
     
    $relations = array();
    $tables = $this->relatedTables(); 
    foreach ($tables as $tableName=>$table) {
    
      $relatedField = "ObjectId";
      if (is_array($table)){
        $className = $table[0];
        if (!empty($table[1])) {
          $relatedField = $table[1];
        }
      }
      else {
        $className = $tableName = $table; 
      }
      
      $tableName = lcfirst($tableName);
      $relations[$tableName] = array(self::HAS_ONE, $className, $relatedField, 'joinType'=>'JOIN');
    }
    
    // дети
//     $relations["childrens"] = array(self::HAS_MANY, 'TreeObjectNode', 'ParentId');
    
    return $relations;
  }
  
  public function defaultScope() {
  
    $relations = array_keys($this->relatedTables());
		return array(
      'with'=>$relations
    );
	}
	
	/*
	* Вытаскивает элемент из дерева и возвращает его (в результате чего его можно куда нить вставить
	* 
	*/  	
	public function remove() {
  
  }
  
  /*
  * Создает копию указанного объекта
  * без привяки его к позиции в дереве сайта
  */    
  public function __clone(){
  
  }
  
  /*
  * Текущий объект вложить в указанный $object
  * С учетом определенной позиции $position:
  * 0    - в начало
  * 2,4  - на указанную позицию
  * null - в конец (по умолчанию)       
  */    
  public function append(TreeLeaf $object, $position = null) {
    
    // вносим информацию в добавляемый объект
    $object->ParentId = $this->Id;
    
    if ($position === null) {
      $childrens = $this->childrens;
      $lastOrderBy = 0;
      if (count($childrens)) {
        list($lastItem) = array_reverse(array_values($childrens)); 
        $lastOrderBy = $lastItem->OrderBy;
      }
      $object->OrderBy = $lastOrderBy+1;
      
      $this->addChildren($object);
    }
    else {
      throw new CException("Other type of adding doesn't support now.");
    }
  }
  
//   /*
//   * Текущий объект ставится ДО переданного $object
//   */  
//   public function insertBefore(PageTreeAbstract $object) {
//   
//   }
//   
//   /*
//   * Текущий объект ставится ПОСЛЕ переданного $object
//   */ 
//   public function insertAfter(PageTreeAbstract $object) {
//   
//   }
  
	public function rules(){
  		
		return array(
// 			array('Name', 'validateNameSiteUnique', 'message'=>'Name в пределах сайта должно быть уникально'),
			array('Name', 'length', 'max'=>100),
			array('Caption', 'length', 'max'=>250),
			array('IsActive', 'boolean'),
		);
	}
	
// 	public function validateNameSiteUnique($attribute, $params = array()){
//   
//     $value = $this->$attribute;
//     
//     Yii::app()->db->createCommand('
//       select
//         Object.Id
//         
//       from
//         Object
//         join (Relation, SiteObject)
//     ');
//   }
  
  /*
  *  Перед сохранением надо проверить, есть ли у этого объекта родитель
  *  
  */    
  protected function beforeSave() {
  
    if ($this->isMoved && $this->parent === null) {
      throw new TreeException("Не указан родитель для элемента");
    }

    if (!$this->isChanged()) {
      return false;
    }
    
    if ($this->isNewRecord) {
      $this->DateCreate = time();
    }
    $this->DateUpdate = time();
  
    return parent::beforeSave();
  }
  
  protected function afterConstruct() {
  
    $relations = array_keys($this->relatedTables());
    foreach ($relations as $relationName) {
      $metaData = $this->getMetaData()->relations[$relationName];
      $this->$relationName = $relation = new $metaData->className;
    }
    
    $this->Class = $this->getClassId();
    $this->setIsMoved();
    parent::afterConstruct();
  }
  
	public function attributeNames() {
		
    return array_merge(
      parent::attributeNames(),
      array_keys($this->_relatedFields)
    );
	}
	
	public function attributeLabels() {
		
		return $this->_attributeLabels;
	}
  
  protected function afterSave() {
    
    parent::afterSave();
    
    $this->setChanged(false);
  
    // после сохранения обновляем информацию в связанных таблицах
    $relations = array_keys($this->relatedTables());
    foreach ($relations as $relationName) {
      
      $metaData = $this->getMetaData()->relations[$relationName];
      if (($relation = $this->getRelated($relationName)) === null) {
        $relation = $this->$relationName = $relation = new $metaData->className;
      }
      $foreignKey = $metaData->foreignKey;
      $relation->$foreignKey = $this->getPrimaryKey(); 
      $relation->save();
    }
    
    if ($this->isMoved) {
      $this->buildRelations();
    }
  }
  
  protected function afterDelete() {
    
    // удлаяем все из связанных таблиц
    $relations = array_keys($this->relatedTables());
    foreach ($relations as $relationName) {
      if (($relation = $this->$relationName) !== null) {
        $relation->delete();
      }
    }
    
    // удялем вложенные объекты
    foreach ($this->childrens as $children) {
      $children->delete();
    }
    
    // удаляем Relations
    TreeRelation::model()->deleteAll("Page = :Id", array("Id"=>$this->Id));
    
    parent::afterDelete();
  }
  
  public function getPrev() {
  
    if ($this->_prev === null) {
      
      $fEnabled = true;
      $this->_prev = array();
      foreach ($this->parent->childrens as $item) {
      
        if ($item->Id === $this->Id) {
          $fEnabled = false;
        }
        else if ($fEnabled){
          $this->_prev[] = $item;
        }
      }
    }
    
    return $this->_prev;
  }
  
  public function getNext() {
  
    if ($this->_next === null) {
      
      $fEnabled = false;
      $this->_next = array();
      foreach ($this->parent->childrens as $item) {
      
        if ($item->Id === $this->Id) {
          $fEnabled = true;
        }
        else if ($fEnabled){
          $this->_next[] = $item;
        }
      }
    }
    return $this->_next;
  }
  
  public function addChildren(TreeLeaf $node) {
    
    $this->getChildrens();
    $this->_childrens[] = $node;
  }
  
  public function getChildrens() {
  
    if ($this->_childrens !== null) {
      return $this->_childrens;
    }
  
    $childrens = array();
    $sql = "
      select
        Object.Id,
        DictClass.ClassName
        
      from
        Object
        join DictClass
          on DictClass.Id = Object.Class
          
      where
        Object.ParentId = :id
        
      order by
        Object.OrderBy
    ";
    $command = Yii::app()->db->createCommand($sql);
    $command->bindValue(":id", $this->Id, PDO::PARAM_INT);
    $dataReader = $command->query();
    
    foreach($dataReader as $row) {
      if ($item = self::createObject($row["ClassName"], $row["Id"])) {
        $childrens[] = $item;
      }
    }
    return $this->_childrens = $childrens;
  }
  
  public function buildRelations() {
  
    $id   = $this->Id;
    $type = TreeRelation::TYPE_OBJECT_TREE;
    
    // удаляем все старые связи
    TreeRelation::model()->deleteAllByAttributes(
      array(
        "Page"=>$id, 
        "Type"=>$type
      )
    );
    
    // создаем связи для текущего узла
    TreeRelation::buildNode($id, $type);
    
    // перестраиваем связи дял всех детей
    foreach ($this->childrens as $children) {
      $children->buildRelations();
    }
  }
}
