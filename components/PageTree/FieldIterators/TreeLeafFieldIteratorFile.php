<?php
  
  class TreeLeafFieldIteratorFile extends TreeLeafFieldIterator {
  
    protected $defaultHtmlOptions = array(
//       "class"=>"span12"
    );
    
    private $form;
    
    public function init() {
    
      $fieldName = $this->getName();
      
      // подключение 
      $this->model->attachBehavior(
        $fieldName."FileUpload", 
        array(
          "class"=>"treeObjectBehaviors.TreeFileUploadBehavior",
          "attributeName"=>$fieldName,
          "fileTypes"=>$this->options['fileTypes'] ? implode(",", $this->options['fileTypes']) : ""
        )
      );
    
      parent::init();
    }
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      
      $options = array_merge($this->defaultHtmlOptions, $options);
      $this->form = $form;
      
      ob_start();
      echo $form->fileFieldRow($this->model, $fieldName, $options);
      if ($file = $this->model->$fieldName) {
        $this->showFileBlock($form, $fieldName);
      }
      return ob_get_clean();
    }
    
    private function showFileBlock($form, $fieldName) {
    
      try {
        // удалить
        echo '<label>'.$form->checkbox($this->model, "delete".$fieldName).' Удалить</label>';
        // скачать
        list($scenario) = array_keys($this->model->imagePreviewScenarios());
        $path = $this->model->getImagePreview($scenario);
        echo CHtml::link("Скачать", $path, array("target"=>"_blank"));
      }
      catch (CException $e) {
        echo '<div class="alert alert-error"><strong>Ошибка!</strong> '.CHtml::encode($e->getMessage()).'</div>';
      }
    }
  }
