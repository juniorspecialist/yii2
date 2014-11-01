<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.10.14
 * Time: 13:33
 */
namespace app\modules\manager\controllers;

use Yii;
use app\models\Content;
use app\models\ContentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;


class TreeController extends Controller{

    public $layout = 'manager_tree';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['node'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'node' => ['post','get'],
                ],
            ],
        ];
    }

    //public $defaultAction = 'tree';

//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['post'],
//
//                    'node' => ['post']
//                ],
//            ],
//        ];
//    }

    public function actionIndex(){
        $this->layout = 'manager_main';
        return $this->render('index');

    }

    public function actionTree(){
        return $this->render('tree');
    }

    public function actionNode(){

        $this->layout = 'clean';

        //выводим список уровней - начального уровня(корень дерева)
        if ($_REQUEST['root'] == "source"){

            $data = Content::getNode();

        }else{

            $data = Content::getNode($_REQUEST['root']);
        }

        echo  $data;
    }
} 