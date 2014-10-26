<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.08.14
 * Time: 19:22
 */

/*
 * сниппет хлебных крошек
 * т.е. выводим список ссылок на верхние уровни до самого верха
 */

namespace app\components;

use yii\helpers\Html;
use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;

class Breadcrumbs {

    public $model;//текущий документ

    public $pathList = array();//список заголовкой уровней вверх от текущего

    public function __construct($model){
        $this->model = $model;
    }

    /*
     * ищем заголовок родителя
     */
    public function getParent($parent_id){
        //применим список ID доков по которым будем делать поиск контента

        //$parent = Content::findOne(array('id'=>(int)$parent_id));
        $query = new Query;

        //выбираем лишь необходимые поля
        $query->select(['parent', 'alias', 'menutitle', 'pagetitle']);

        // compose the query
        $query->from(Content::collectionName());

        $query->where(array('id'=>(int)$parent_id));

        $row = $query->one();

        return $row;
    }

    public function run(){

        $list = array();
        $link = array();

        $model = $this->model;

        if(is_array($model)){
            $this->pathList[] = $model['menutitle'];
            $parent = $model['parent'];
        }else{
            $this->pathList[] = $model->menutitle;
            $parent = $model->parent;
        }

        //максимально может быть не более 20ти уровней
        for($i=0;$i<10;$i++){

            //получаем родителя от текущего уровня в дереве
            $row = $this->getParent($parent);

            if(empty($row['menutitle'])){
                break;
            }else{

                //формируем массив ссылок относительно уровней в ветке дерева
                $this->pathList[] = $this->getMenuLink($row['menutitle'], $row['alias'], array('style'=>'class="B_crumb"','title'=>$row['pagetitle']));

                $parent = $row['parent'];
            }
        }

        $this->pathList = array_reverse($this->pathList);

        if(count($this->pathList)==1){
            return '';
        }else{
            return implode('  »  ',$this->pathList);
        }
    }

    /*
     * формируем ссылку в меню хлебных крошек
     * $title - название ссылки
     * $alias - адрес который будет в ссылке
     */
    public function getMenuLink($title, $alias,  $htmloptions = array()){

        if(empty($alias)){
            return Html::a($title, Url::to(['/site/index']), $htmloptions);
        }else{
            return Html::a($title, Url::to(['/site/index', 'alias'=>$alias]), $htmloptions);
        }
    }
}