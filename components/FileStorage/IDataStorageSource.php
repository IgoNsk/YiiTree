<?php

  interface IDataStorageSource {
    
    public function getHash();
    
    public function getContent();
    
    public function setContent($content);
    
    public function getFilename();
  }
