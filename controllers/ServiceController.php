<?php

namespace app\controllers;

use Yii;
use app\models\Document;
use app\models\DocumentSearch;
use yii\rest\ActiveController;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class ServiceController extends ActiveController
{
   public $modelClass = 'app\models\Document';
   public function actions()
   {
          $actions = parent::actions();

            // disable the "delete" and "create" actions
            //unset($actions['delete'], $actions['create']);

            // customize the data provider preparation with the "prepareDataProvider()" method
            //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

            return $actions;
   }

   public function actionSavedocs()
   {
      
      $request_body = file_get_contents('php://input');
      $data = json_decode($request_body);
      $template_id = $data->id;
      $document = new Document();
      $document->template_id = $template_id;



      $document->name =  date('l jS \of F Y h:i:s A');
      $document->index = $data->data->index;
      $document->data = json_encode($data->data);
      $document->save();

    //$id => template_id
        return $document->getInfo();

   }
}

