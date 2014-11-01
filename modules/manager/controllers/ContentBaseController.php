<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.10.14
 * Time: 15:05
 */

namespace app\modules\manager\controllers;

use Yii;
//use app\models\Content;
//use app\models\ContentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ContentBaseController extends Controller{
    public $layout = 'manager_content';
} 