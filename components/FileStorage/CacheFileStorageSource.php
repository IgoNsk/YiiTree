<?php

  class CacheFileStorageSource extends FileStorageSource  {
    
    private $_options;
    
    public function __construct($filename, array $options = array()) {
    
      $this->_filename = $filename;
      $this->_options  = $options;
      
      $this->_hash = md5($this->_filename.serialize($this->_options));
    }
  }
