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

        //if ($_REQUEST['root'] == "source"){

        $my_data = array(
            array(
                'text'     => 'Node 1',
                'expanded' => true, // будет развернута ветка или нет (по умолчанию)
                'children' => array(
                    array(
                        'text'     => 'Node 1.1',
                        'expanded' => false, // будет развернута ветка или нет (по умолчанию)
                        'children' => array(
                            array(
                                'text'     => 'Node 1.1.1',
                            ),
                        )
                    ),
                    array(
                        'text'     => 'Node 1.2',
                        'expanded' => true,
                        'children' => array(
                            array(
                                'text'     => 'Node 1.2.1',
                                'expanded' => true,
                                'children' => array(
                                    array(
                                        'text'     => 'Node 1.2.2.1',
                                        'expanded' => true,
                                        'children' => array(
                                            array(
                                                'text'     => 'Node 1.2.2.1.1',
                                            ),
                                        )
                                    ),
                                )
                            ),
                        )
                    ),
                    array(
                        'text'     => 'Node 1.3',
                        'expanded' => true,
                        'children' => array(
                            array(
                                'text'     => 'Node 1.3.1',
                            ),
                        )
                    ),
                )
            ),
        );

        //    return json_encode($data);
        return $this->render('node', ['data'=>$my_data]);
            //Yii::$app->end();
        //}

    }
} 