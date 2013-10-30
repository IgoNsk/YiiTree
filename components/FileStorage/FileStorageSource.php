<?php

  class FileStorageSource extends CComponent implements IDataStorageSource {
    
    private $_content;
    protected $_filename;
    protected $_hash;
    
    public function __construct($filename) {
    
      $this->_filename = $filename;
      $this->_hash = md5($this->_filename);
    }
    
    public function getHash() {
      
      return $this->_hash;
    }
    
    public function getContent() {
      
      return $this->_content;
    }
    
    public function setContent($content) {

      $this->_content = $content;
    }
    
    public function getFilename() {
    
      return $this->_filename;
    }
  }
