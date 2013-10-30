<?php

  class TreeFileUploadBehavior extends CActiveRecordBehavior {
    /**
     * @var string название атрибута, хранящего в себе имя файла и файл
     */
    public $attributeName;
    /**
     * @var array сценарии валидации к которым будут добавлены правила валидации
     * загрузки файлов
     */
    public $scenarios = array('insert','update');
    /**
     * @var string типы файлов, которые можно загружать (нужно для валидации)
     */
    public $fileTypes = '';
    
    private $deleteFile = false;
 
    public function attach($owner) {
        parent::attach($owner);
 
        if(in_array($owner->scenario,$this->scenarios)){
            // добавляем валидатор файла
            $fileValidator=CValidator::createValidator('file',$owner,$this->attributeName,
                array('types'=>$this->fileTypes,'allowEmpty'=>true));
            $owner->validatorList->add($fileValidator);
            $owner->validatorList->add(CValidator::createValidator('safe', $owner, "delete".$this->attributeName));
        }
    }
    
    // этот все для того, чтобы можно было эмулировать свойство в моедли, для удаления приложенного файла
    public function canGetProperty($name) {
    
      $name = strtolower($name);
      if ($name === strtolower("delete".$this->attributeName)) {
        return true;
      }
      else {
        return parent::canGetProperty($name);
      }
    }
    
    public function canSetProperty($name) {
    
      $name = strtolower($name);
      if ($name === strtolower("delete".$this->attributeName)) {
        return true;
      }
      else {
        return parent::canSetProperty($name);
      }
    }
    
    public function __get($name) {
    
      $name = strtolower($name);
      if ($name === strtolower("delete".$this->attributeName)) {
        return $this->getDeleteProperty();
      }
      else {
        return parent::__get($name);
      }
    }
    
    public function __set($name, $value) {
    
      $name = strtolower($name);
      if ($name === strtolower("delete".$this->attributeName)) {
        return $this->setDeleteProperty($value);
      }
      else {
        return parent::__set($name, $value);
      }
    }
    
    public function setDeleteProperty($value) {
    
      $this->deleteFile = $value;
    }
    
    public function getDeleteProperty() {
    
      return $this->deleteFile;
    }
 
    public function beforeSave($event) {
      
      if (in_array($this->owner->scenario,$this->scenarios)) {
        
        // Удаление файла
        if ($this->deleteFile) {
          $this->deleteFile();
        }
        
        // Аплоад файла  
        if ($file = CUploadedFile::getInstance($this->owner,$this->attributeName)) {
          $this->deleteFile(); // старый файл удалим, потому что загружаем новый
          $this->owner->uploadFile($this->attributeName, $file);
        }
        
//         $this->owner->save(false, array($this->attributeName));
      }
      return true;
    }
 
    public function beforeDelete($event) {
      
      $this->deleteFile();
    }
 
    public function deleteFile(){
    
      $this->owner->deleteFile($this->attributeName);
    }
  }
