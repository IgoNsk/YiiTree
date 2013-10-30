<?php

  class TreeUrlManager extends CBaseUrlRule {
   
    public function createUrl($manager,$route,$params,$ampersand){
    
      $item = null;
      switch ($route) {
        
        case "pageById":
          $item = TreeLeaf::findById($params[0]);
          break;
          
        case "pageByName":
          $item = TreeLeaf::findByName($params[0], 1);
          break;
          
        default:
          return false;
      }
      
      if ($item === null) {
        if (YII_DEBUG) {
          throw new CException("Can't create route ({$route}) for page {$params[0]}");
        }
        return false;
      }
      
      $url = $item->Url;
//       __($url);
      $url = $this->prepareUrl($url);
      return $url;
    }
    
    public function prepareUrl($url) {
    
      $url = trim($url, '/');
      $url = $url.'/';
      
      return $url;
    }
  
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo){
      
//       $this->test();
      
      Yii::log("Profile successfully created");
      if (($item = TreeObjectPage::findByUrl($pathInfo)) === null) {
        return false;
      }
      Yii::app()->site->setRequestPage($item);
      return "pageTree/show";
    }
    
    public function test() {
      
      // ищем указанный урл в базе
      $nodes = TreeObjectPage::model()->findAll();
      foreach ($nodes as $item) {
        $item->regenerateUrl();
        $item->save();
      }
      exit();
 
//       $item = TreeLeaf::findById(1);
//       foreach ($item->childrens as $child) {
//         __($child->Caption);
//       }
//       $item->buildRelations();
//       
      
//       $filePath = "/var/www/apache/yii.top-page.ru/app/temp/2/";
//       $fileName = "4.png";
//       $item = TreeLeaf::findById(13);
//       $item->saveFile("Image", $fileName, file_get_contents($filePath.$fileName));
//       __($item->Image);
//       $item->save();
//       exit();
//       $item->Path = "test123";
//       $item->save();
      
//       $newItem = new TreeObjectPageHelp;
//       $caption = "test ".rand();
//       $attributes = array(
//         "ParentId"=>1,
//         "OrderBy"=>7,
//         "Caption"=>$caption,
//         "Header"=>$caption." Header!",
//         "Title"=>$caption." Title",
//         "Path"=>rand(),
//         "Video"=>"youtube",
//         "Image"=>"Image"
//       );
//       $newItem->setAttributes($attributes, false);
//       
//       __($newItem->Caption);
//       __($newItem->Image);
//       $newItem->save();
    }
  }
