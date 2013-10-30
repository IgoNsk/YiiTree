<?php

  interface IDataStorage {
  
    public function remove(IDataStorageSource $source, IDataStorageSource $dist = null);
    
    public function copy(IDataStorageSource $source, IDataStorageSource $dist);
    
    public function getFilePath(IDataStorageSource $source);
    
    public function save(IDataStorageSource $source);
  }
