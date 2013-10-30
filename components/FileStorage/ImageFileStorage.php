<?php

  class ImageFileStorage extends CApplicationComponent {
  
    private $_fileStorage;
    private $_cacheStorage;
    
    public $fileDir;
    public $cacheDir;
    
    public function getFileStorage() {
    
      if ($this->_fileStorage === null) {
        if (!$this->fileDir) {
          throw new CException("Для компонента ImageFileStorage не задана папка хранилище файлов");
        }
        $this->_fileStorage = new FileStorage();
        $this->_fileStorage->dirPath = Yii::getPathOfAlias($this->fileDir);
        $this->_fileStorage->init();
      }
      
      return $this->_fileStorage;
    }
    
    public function getCacheStorage() {
    
      if ($this->_cacheStorage === null) {
        if (!$this->cacheDir) {
          throw new CException("Для компонента ImageFileStorage не задана папка хранилище кеша");
        }
        $this->_cacheStorage = new FileStorage();
        $this->_cacheStorage->dirPath = Yii::getPathOfAlias("webroot.".$this->cacheDir);
        $this->_cacheStorage->init();
      }
      
      return $this->_cacheStorage;
    }
    
    public function addFile($filename, $content) {
    
      $fileSource = new FileStorageSource($filename);
      $fileSource->setContent($content);
      
      return $this->fileStorage->save($fileSource)->getFilePath($fileSource, false);
    }
    
    public function deleteFile($filename) {
      
      $fileSource = new FileStorageSource($filename);
      $this->fileStorage->remove($fileSource);
    }
    
    public function makeWebPath($path){
      
      $webroot = Yii::getPathOfAlias("webroot");
      return substr($path, strlen($webroot));
    }
  
    public function getResizeFile($filename, $resizeType, array $options = array()) {
    
      $filePath = $this->fileStorage->dirPath."/".$filename;
      
      if (!file_exists($filePath)) {
        throw new CException(sprintf("Not exist file %s", $filePath)); 
      }
      
      $mtimeOriginalFile = filemtime($filePath);
    
      // версяи кеш файла строится на многих факторах
      // имя файла оригинала
      // дата модификации файла оригинала
      // опции указанные для пережатия
      $defaultOptions = array(
        'width' => 0,
        'height' => 0,
        'bgColor' => 'ffffff',
        'resizeType'=>$resizeType,
        'mtime'=>$mtimeOriginalFile
      );
      
      $options = array_merge(
        $defaultOptions,
        $options
      );
      $options = $this->normalizeArray($options);
      
      $source = new CacheFileStorageSource(basename($filename), $options);
      $cacheFileName = $this->cacheStorage->getFilePath($source);
      if (!file_exists($cacheFileName)) {
        $preview = ImageResize::ResizeImageFile(
          $filePath, 
          $options['width'], 
          $options['height'], 
          $resizeType,
          $options['bgColor']
        );
        $source->setContent($preview);
        $this->cacheStorage->save($source);
      }
      
      return $cacheFileName;
    }
    
    private function normalizeArray(array $data) {
    
      ksort($data);
      return $data;
    }
  }
