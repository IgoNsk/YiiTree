<?php
  
  class SiteTreeManagementController extends AdminController {
  
    public $layout = "siteTree";
    private $node;
    private $model;

    private function buildTree($nodes) {
    
      $results = null;
      
      if (!empty($nodes)) {
        $results = array();
        $parents = array();
        foreach ($nodes as $node) {
        
          $id = intval($node["Id"]);
          if (isset($parents[$node["ParentId"]])) {
            $parent = &$parents[$node["ParentId"]];
            if (!isset($parent->children)) {
              $parent->children = array();
            }
            $item = $parent->children[] = new stdClass;
            $parent->state = 'open';
            $parents[$id] = &$parent->children[array_search($item, $parent->children)];
          }
          else {
            $item = $results[] = new stdClass;
            $parents[$id] = &$results[array_search($item, $results)];
          }
          
          $item->data = array(
            'title'=>$node["Caption"],
            'attr'=>array(
              'href'=>"/admin/SiteTreeManagement/node-{$id}",
              'title'=>$node["Caption"]
            )
          );
          $item->attr = array(
            'id'=>'node_'.$id,
            'isActive'=>$node["IsActive"]
          );
          
          if ($node['HasChild']) {
            $item->state = 'closed' ; 
          }
        }
      }
    
      return $results;
    }
   
    public function actionTreeData($operation, $id) {
      
      $model = TreeObjectNode::findById($id);
      if ($model === null) {
        throw new CHttpException(400, "Not find node with id {$id}");
      }
      
      $result = array();
      switch ($operation) {
        
        case "get_children":
          $items = $model->getNodeChildrens();
          $result = $this->buildTree($items);
          break;
          
        case "get_tree":
          $items = $model->getOpenedTree();
          $result = $this->buildTree($items);
          break;
          
        case "move_node":
          if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, "Request must be POST");
          }
          try {
            $model->moveInNode($_POST["position"])
                  ->save();
            $result = array(
              "status"=>true,
              "id"=>$model->Id
            );
          }
          catch (CException $e) {
            $result = array(
              "status"=>false,
              "message"=>$e->getMessage()
            );
          }
          break;
          
        default:
          throw new CHttpException(400, "Undefined operation {$operation}");
          break;
      }
    
      $this->renderJson($result);
    }
    
    private function clearCache(CActiveRecord $model) {
    
      Yii::app()->cache->flush();
    }
    
    public function actionDelete($node) {
    
      if (!Yii::app()->request->isPostRequest) {
        throw new CHttpException(400, "Запрос на удаление должен быть POST");
      }
      
      $this->node = $node;
      $model = $this->loadModel($node);
      $model->delete();
      
      $this->clearCache($model);
      
      $this->redirect(array('SiteTreeManagement/view', 'node'=>$model->parent->Id));
    }
    
    public function actionModule($node, $module) {
     
      $model = $this->loadModel($node);
      $this->node = $node;
      
      $actionMap = $model->getActionMap();      
      if (!isset($actionMap[$module])) {
        throw new CHttpException("404", "Не найден контроллер для указанного модуля");
      }
      
      $scriptMap = Yii::app()->clientscript->scriptMap;
      $scriptMapFiles = array('jquery.js', 'jquery.min.js');
      foreach ($scriptMapFiles as $scriptMapFile) {
        Yii::app()->clientscript->scriptMap[$scriptMapFile] = false;
      }
      
      ob_start();
      $this->forward($actionMap[$module], false);
      $body = ob_get_clean();
      Yii::app()->clientscript->scriptMap = $scriptMap;
      
      $this->render('module', array(
  			'model'=>$model,
  			'content'=>$body
  		));
    }
    
    public function actionView($node) {
    
      $model = $this->loadModel($node);
      $this->node = $node;
  		$this->performAjaxValidation($model);
      
      $postName = get_class($model);
  		if (isset($_POST[$postName])) {
  			$model->attributes = $_POST[$postName];
  			if ($model->save()) {
  			
  			  $this->clearCache($model);
  			  if (!empty($_POST["SaveAndNext"])) {
  			  
  			    if (empty($model->next)) {
              $this->redirect(array('SiteTreeManagement/add', 'node'=>$model->ParentId, 'classId'=>$model->Class));
            }
            else {
              list($next) = array_values($model->next);
              $this->redirect(array('SiteTreeManagement/view', 'node'=>$next->Id));
            }
          }
          else {
  				  $this->redirect(array('SiteTreeManagement/view', 'node'=>$model->Id));
  				}
  			}
  		}
      
      $this->render('edit', array(
  			'model'=>$model,
  		));
    }
    
    public function actionAdd($node, $classId = null) {
    
      $nodeModel = $this->loadModel($node);
      $error = null;
      $this->node = $node;
      try {
        // список свех классво, которые можно добавить в текущий объект
        $classes = $nodeModel->childrenClasses();
        if (empty($classes)) {
          throw new CException("В данный объект нельзя добавлять детей.");
        }
        
        // определяем 
        if ($classId === null) {
          list($className) = array_keys($classes);
          $this->redirect(array('SiteTreeManagement/add', 'node'=>$nodeModel->Id, 'classId'=>$className::$classId));
        }
        
        $className = TreeObjectNode::getClassNameById($classId);
        
        if (!in_array($className, array_keys($classes))) {
          throw new CException("В данный узел нельзя добалять объекты типа {$className}");
        }
        
        $model = new $className;
        
        // ajax валидация полей
    		$this->performAjaxValidation($model);
    		
    		// вкладываем в родителя наш новый объект
    		$nodeModel->append($model);
    		
    		if (isset($_POST[$className])) {
    			$model->attributes = $_POST[$className];
    			if ($model->save()) {
    			
    			  $this->clearCache($model);
    			  // Добавление еще одного такого же
    			  if (!empty($_POST["SaveAndNext"])) {
              $this->redirect(array('SiteTreeManagement/add', 'node'=>$nodeModel->Id, 'classId'=>$model->Class));
            }
            else {
    				  $this->redirect(array('SiteTreeManagement/view', 'node'=>$model->Id));
    				}
    			}
    		}
    		
    		$this->render('create', array(
          'node'=>$nodeModel,
    			'model'=>$model,
    			'errors'=>$error
    		));
  		}
  		catch (CException $e) {
        $error = $e->getMessage();
        __($error);
      }
      
    }
    
    private function loadModel($id) {
    
      $model = TreeObjectNode::findById($id);
  		if ($model===null) {
  			throw new CHttpException(404, 'Запрашиваемая страница не найдена');
  		}
  		$model->adminPrepare();
  		$this->model = $model;
  		return $model;
    }
    
   /**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
  	protected function performAjaxValidation($model) {
  		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-site-object-form') {
  			echo CActiveForm::validate($model);
  			Yii::app()->end();
  		}
  	}
  	
  	public function getNode() {
  	
      return $this->node;
    }
    
    public function getModel() {
      
      return $this->model;
    }
  
    public function actionIndex(){
    
      $node = TreeApplication::model()->find();
      if ($node === null) {
        throw new CHttpException("404", "Не найдено ни одного сайта - нечего отображать");
      }
      
      $this->redirect(array("SiteTreeManagement/view", "node"=>$node->Id));
    }
  }
