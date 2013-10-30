<?php

class DefaultController extends AdminController
{
	public function actionIndex() {
		$this->render('index');
	}
	
	public function actionClearCache() {
  
    // Сброс кеша
    Yii::app()->cache->flush();
       
    // обновляем assets
    $path = Yii::getPathOfAlias(Yii::app()->params['assetsPath']);
    touch($path);
    
    $this->redirect(array("index"));
  }
	
	public function actionError() {
    if($error=Yii::app()->errorHandler->error)
    {
    	if (Yii::app()->request->isAjaxRequest) {
    		echo $error['message'];
    	}
    	else {
        $this->render('error', $error);
      }
    }
	}
  
  public function actionLogin() {
  
    $app = Yii::app();
    
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
    
    $model = new AdminLoginForm;
    if ($auth = $app->request->getPost("AdminLoginForm")) {
      $model->attributes = $auth;
      if ($model->validate() && $model->login()) {
        $this->redirect(Yii::app()->user->returnUrl);
      }
      else{
        Yii::app()->user->setFlash("error", "неправильный логин пароль");
      }
    }

    $this->layout = "column1";
    $this->render('login',array('model'=>$model));
  }
  
  public function actionLogout() {
    Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
  }
}
