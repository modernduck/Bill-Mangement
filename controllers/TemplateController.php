<?php

namespace app\controllers;

use Yii;
use app\models\Template;
use app\models\TemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * TemplateController implements the CRUD actions for Template model.
 */
class TemplateController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Template models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Template model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Template model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Template();
        
        $file_name = date('Y-m-d-H-i-s').'-'.rand();
            $model->main_path = $file_name;
        $errors = [];
        if ($model->load(Yii::$app->request->post()) && $model->save()){
            // validation failed: $errors is an array containing error messages
            $model->html_file = UploadedFile::getInstance($model, 'html_file');
            $model->js_file = UploadedFile::getInstance($model, 'js_file');
            $model->css_file = UploadedFile::getInstance($model, 'css_file');

            $result = $model->upload($file_name);
            if($result != true)
            {

               $errors = $model->errors;
                array_push($errors, array('test' => 'upload error somehow'));
                    return $this->render('create', [
                    'model' => $model,
                    'errors' => $errors,
                ]);
            }else
            {

                
                
            
                return $this->redirect(['view', 'id' => $model->id]);    
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'errors' => $errors,
            ]);
        }
    }

    public function actionNewdoc($id)
    {
        $model = $this->findModel($id);
        $path_html  = './templates/'.$model->main_path.'/index.html';
        $css_path = './templates/'.$model->main_path.'/style.css';
        $js_path = './templates/'.$model->main_path.'/main.js';
        $file =  file_get_contents($path_html);
        $file = str_replace("{{@css}}", $css_path, $file);
        $file = str_replace("{{@js}}", $js_path, $file);
        echo $file;
    }

    /**
     * Updates an existing Template model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Template model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Template model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Template the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
