<?php
  /**
   * Контроллер для работы с объектами дерева сайта. 
   *   
   * @package \Controller\PageTree
   */        
  class PageTreeController extends Controller {
  
    /**
     * Действие отображения запрашиваемой страницы.
     * 
     * Запрашиваемая страница определяется в UrlManager и заносится в переменную 
     * Yii::app()->site->requestPage .
     * 
     * Для отображения объекта используется шаблон, указанный в запрашиваемом компоненте.
     * 
     * @throws CHttpException Если запрашиваемая страница не найдена.                                   
     */              
    public function actionShow() {
      
      if (($item = Yii::app()->site->requestPage) === null) {
        throw new CHttpException(404, "Request page is null");
      }
      
      $data = $item->getRenderData();
      $this->title = $item->Title;
      $this->render($item->template, array("item"=>$item, 'data'=>$data));
    }
  }
