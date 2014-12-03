<?php

namespace app\modules\manager\controllers;

use app\components\ContentWizard;
use app\models\Template;
use Yii;
use app\models\Content;
use app\models\ContentSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends ContentBaseController
{

    //public $layout = 'manager_content';

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
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {


        $searchModel = new ContentSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTree(){

        return $this->render('tree');
    }

    /**
     * Displays a single Content model.
     * @param integer $_id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Content();

        //обработка подвязанны к шаблону тв-параметров и отображение их в форме
        $wizard = new ContentWizard($model->findContentArray(['_id'=>(string)$model->_id]), $model->tpl);
        $wizard->isNewRecord = $model->isNewRecord;
        $wizard->run();


       // $model->loadDefaultValues();//установка некоторых значений по умолчанию

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => (string)$model->_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tv'=>$wizard->tv,
            ]);
        }
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //обработка подвязанны к шаблону тв-параметров и отображение их в форме
        $wizard = new ContentWizard($model->findContentArray(['_id'=>(string)$model->_id]), $model->tpl);
        $wizard->isNewRecord = $model->isNewRecord;
        $wizard->run();

        //валидация данных
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // теперь обработаем тв-параметры и сохраним все данные вместе
            $content = \Yii::$app->mongodb->getCollection('Content');
            //очищаем от пустых значений POST массив(по тв-параметрам)
            foreach($_POST['Content'] as $name=>$value_tv){
                if(empty($value_tv) && !in_array( $name,$model->attributes())){
                    unset($_POST['Content'][$name]);
                }else{
                    //TODO заглушка, сделать нормально
                    if($name=='template'){$_POST['Content'][$name] = (int)$value_tv;}
                }
            }

            $content->update(['_id'=>$model->_id],$_POST['Content']);

            \Yii::$app->getSession()->setFlash('info', 'Успешно обновили документ');

            return $this->redirect(['update', 'id' => (string)$model->_id]);
        } else {

            return $this->render('update', [
                'model' => $model,
                'tv'=>$wizard->tv,
            ]);
        }
    }

    /**
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Content::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
