<?php 

  class TreeObjectNode extends TreeLeaf {
  
    public static $classId = 3;
    public static $classLabel = "Узел";
    protected $template = "";
    
    private static $_fileStorage = null; 
  
  	public static function model($className=__CLASS__) {
  	
  		return parent::model($className);
  	}
  	
  	public static function getClassNameById($id) {
    
      $sql = "
        select
          DictClass.ClassName
          
        from
         DictClass
            
        where
          DictClass.Id = :id
      ";
      $command = Yii::app()->db->cache(3600)->createCommand($sql);
      $command->bindValue(":id", $id, PDO::PARAM_INT);
      $className = $command->queryScalar();

      return $className;
    }
  	
  	// Настройик для рендеринга объекта
  	public function getTemplate() {
    
      return $this->template;
    }
    
    public function getRenderData() {
    
      return array();
    }
    
    public function childrenClasses() {
  
      return array(
        "TreeObjectNode"=>array()
      );
    }
    
    protected function fields() {
    
      return 
        array(
          "Caption"=>array(
            "label"=>"Название",
            "type"=>"string",
            "tab"=>"main"       
          ),
//           "Name"=>array(
//             "label"=>"Системное имя",
//             "type"=>"string",
//             "tab"=>"system"       
//           ),
//           "Class"=>array(
//             "label"=>"Класс объекта",
//             "type"=>"string",
//             "tab"=>"system"       
//           )
        );
    }
    
  	protected function tabs() {
  	
      return 
        array(
          "main"=>array(
        	  "label"=>"Основное",
        	  "description"=>"Все основные поля объекта"	
          ),
//           "system"=>array(
//             "label"=>"Системные",
//             "description"=>"Системные настройки",
// //             "access"=>array("administrator")
//           ),
        );
    }
    
    // настраивем хранилище файлов объектов
    protected static function getFileStorage() {
    
      if (self::$_fileStorage === null) {
      
        self::$_fileStorage = Yii::createComponent(
          array(
            'class'=>'application.components.FileStorage.ImageFileStorage',
            'fileDir'   => 'application.data.treeObjects',
  		      'cacheDir'  => 'assets.treeObjects'
  		    )
        );
        self::$_fileStorage->init();
      }
      
      return self::$_fileStorage;
    }
    
    public function saveFile($fieldName, $originalFilename, $content) {
    
      if (!isset($this->$fieldName)) {
        throw new CException("Property with name {$fieldName} doesn't exist");
      }
    
      if (!$this->Id) {
        throw new CException("We can't save file without record ID");
      }
     
      $filename = $this->Id."_".$fieldName.".".CFileHelper::getExtension($originalFilename);   
      $this->$fieldName = self::getFileStorage()->addFile($filename, $content);
      
      return $this;
    }
    
    public function uploadFile($fieldName, CUploadedFile $file) {
      
      if (!isset($this->$fieldName)) {
        throw new CException("Property with name {$fieldName} doesn't exist");
      }
    
      if (!$this->Id) {
        throw new CException("We can't save file without record ID");
      }
      
      $filename = $this->Id."_".$fieldName.".".$file->getExtensionName(); 
      $content  = file_get_contents($file->getTempName());  
      $this->$fieldName = self::getFileStorage()->addFile($filename, $content);
      
      return $this;
    }
    
    public function deleteFile($fieldName) {
     
      if ($filename = basename($this->$fieldName)) {
        self::getFileStorage()->deleteFile($filename);
        $this->$fieldName = null;
      }
      
      return $this;
    }
    
    protected function adminActions() {
    
      $sender = $this;
    
      return array(
        "view"=>array(
          "route"=>"SiteTreeManagement/view", 
          "caption"=>'Редактировать'
        ),
        "add"=>array(
          "route"=>"SiteTreeManagement/add", 
          "caption"=>'Добавить...', 
          "items"=>function() use ($sender) {
          
            $items = array();
            foreach ($sender->childrenClasses() as $class=>$classOptions) {
              $items[] = array(
                "label"=>$class::$classLabel,
                "url"=>Yii::app()->controller->createUrl("SiteTreeManagement/add",
                  array(
                    'node'=>$sender->Id,
                    'classId'=>$class::$classId
                  )
                ),
              );
            }
            
            return $items;
          },
        ),
        "delete"=>array(
          "url"=>"#",
          "caption"=>'Удалить',
          'linkOptions'=>array(
            'submit'=>array('SiteTreeManagement/delete','node'=>$sender->Id),
            'confirm'=>'Вы уверены, что хотите удалить просматриваемый объект?',
          ),
        ),
      );
    }
    
    public function getAdminActions() {
    
      return $this->adminActions();
    }
    
    public function getImagePreview($scenario) {
    
      $scenarios = $this->imagePreviewScenarios();
      
      if (!isset($scenarios[$scenario])) {
        throw new CException("Preview image: call unknown scenario");
      }
      
      $storage = self::getFileStorage();
      list($field, $type, $options) = array_values($scenarios[$scenario]);
      $preview = $storage->getResizeFile($this->$field, $type, $options);     
      return $storage->makeWebPath($preview);
    }
    
    public function getSiteNode() {
      
      for($site = $this; $site->parent !== null && !($site instanceof TreeApplication); $site = $site->parent);
      return $site;
    }
    
    // возвращает все возможные виды пережатых картинок.
    // таким образом информация о них всегда хранится в одном месте
    public function imagePreviewScenarios() {
    
      return array();
    }
    
    // методы для быстрого извлечения информации о древовидной иерархии
    public function getNodeChildrens() {
    
      $sql = "
        select
          Object.Id,
          Object.ParentId,
          Object.Caption,
          Object.IsActive,
          if(ChildObject.Id is not null, 1, 0) as HasChild,
          0 as IsOpened
          
        from
          Object
          join Object as NodeObject
            on NodeObject.Id = Object.ParentId
          left join Object as ChildObject
            on ChildObject.ParentId = Object.Id
            
        where
          NodeObject.Id = :Node
          
        group by
          Object.Id
          
        order by
          Object.OrderBy
      ";
      $connection = Yii::app()->db;
      $command = $connection->createCommand($sql);
      $command->bindValue(":Node", $this->Id, PDO::PARAM_INT);
      
      $data = $command->queryAll();
      return $data;
    }
    
    public function getOpenedTree() {
    
      $sql = "
        select
          Object.Id,
          Object.ParentId,
          Object.Caption,
          Object.IsActive,
          if(ChildObject.Id is not null, 1, 0) as HasChild,
          NeedPage.IsOpened
          
        from
          Object
          join (
            select
              Object.Id,
              1 as IsOpened
              
            from
              Object
              
            where
              Object.Id = :Node
              
            union
            select
              Object.Id,
              0 as IsOpened
              
            from
              Object
              
            where
              Object.ParentId = :Node
              
            union
            select
              Object.Id,
              if(Object.Id = :Node, 1, 0) as IsOpened
              
            from
              Object
              join Relation
                on Relation.Node = Object.ParentId
                 
            where
              Relation.Page = :Node
              and Relation.Type = 1
              
            union
            select
              Relation.Node as Id,
              1 as IsOpened
              
            from
              Relation
                 
            where
              Relation.Page = :Node
              and Relation.Type = 1
          ) as NeedPage
            on Object.Id = NeedPage.Id
          join Relation as LevelRelation
            on LevelRelation.Page = Object.Id
            and LevelRelation.Node = 0
            and LevelRelation.Type = 1
          left join Object as ChildObject
            on ChildObject.ParentId = Object.Id
          
        group by
          Object.Id
          
        order by
          LevelRelation.Level,
          Object.OrderBy
      ";
      $connection = Yii::app()->db;
      $command = $connection->createCommand($sql);
      $command->bindValue(":Node", $this->Id, PDO::PARAM_INT);
      
      $data = $command->queryAll();
      return $data;
    }
  }
