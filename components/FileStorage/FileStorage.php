<?php

  class FileStorage implements IDataStorage{
     
    public $dirDeepLevel = 2;
    public $dirNameLength = 2;
    public $filemask = 0666;
    public $dirMask = 0777;
    
    public $dirPath;
    
    public function init() {
      
      if (!is_dir($this->dirPath)) {
        throw new CException("Не найдена указанная в конфигурации директория {$this->dirPath}");
      }
      
      if (!is_writable($this->dirPath) || !is_readable($this->dirPath)) {
        throw new CException("Нет доступа на запись/чтение в директории {$this->dirPath}");
      }
    }
    
    protected function getDirPath(IDataStorageSource $source) {
      $hash = $source->getHash();
      
      $data = array();
      for ($i = 1, $offset = 0; $i <= $this->dirDeepLevel; $i++, $offset += $this->dirNameLength) {
        $data[] = substr($hash, $offset, $this->dirNameLength);
      }
      
      $path = implode('/', $data);
      
      return $path;
    }
    
    public function getFilePath(IDataStorageSource $source, $getFullPath = true) {
     
      $paths = array();
      if ($getFullPath) {
        $paths[] = $this->dirPath;
      }
      $paths[] = $this->getDirPath($source);
      $paths[] = $source->getFilename();
      
      $path = ($getFullPath ? "" : "/").implode('/', $paths);
      
      return $path;
    }
    
    public function save(IDataStorageSource $source) {
    
      $file = $this->getFilePath($source);
      $content = $source->getContent();
      $dir = dirname($file);
      if (!is_dir($dir)) {
        if (false === @mkdir($dir, $this->dirMask, true) && !is_dir($dir)) {
          throw new CException(sprintf("Unable to create the directory (%s).", $dir));
        }
      } 
      else if (!is_writable($dir)) {
        throw new CException(sprintf("Unable to write in the directory (%s).", $dir));
      }
  
      $tmpFile = tempnam(dirname($file), basename($file));
      if (false !== @file_put_contents($tmpFile, $content)) {
        // rename does not work on Win32 before 5.2.6
        if (@rename($tmpFile, $file) || (@copy($tmpFile, $file) && unlink($tmpFile))) {
          @chmod($file, $this->filemask & ~umask());
          return $this;
        }
      }
  
      throw new CException(sprintf('Failed to write file "%s".', $file));
    }
  
    public function remove(IDataStorageSource $source, IDataStorageSource $dist = null){
      
      if ($dist !== null) {
        $this->copy($source, $dist);
      }
      
      $file = $this->getFilePath($source);
      
      if (!unlink($file)) {
        throw new CException(sprintf('Failed to unlink file "%s".', $file));
      }
      
      return $this;
    }
    
    public function copy(IDataStorageSource $source, IDataStorageSource $dist){
      
      if ($source === $dist) {
        return $this;
      }
      
      $dist->setContent($source->getContent());
      
      return $this->save($dist);
    }
  }
