<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.10.14
 * Time: 21:35
 */

/*
 * формируем список тегов для фильтрации товаров по выбранному тегу
 * чем-то похоже на категории но по указананным тв-параметрам
 */

namespace app\components;

use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;

class TvTagCloud extends \app\components\Ditto{

    //параметры работы сниппета

    //внешний вид
    // cloud | list | custom ( в виде облака, маркированного списка или выборочно)
    public $displayType = 'cloud';

    //ID контейнера, в котором находятся отображаемые документы(можно указывать несколько ID через запятую)
    public $parent = 0;

    //глубина просмотра
    public $depth = 10;

    //ID документа, где расположен вызов Ditto
    public $landing;

    //TV-параметр, в котором содержатся теги
    public $tvTags;

    //включает/выключает отображение количества вхождений тега
    public $showCount=0;

    //список исключаемых тегов
    public $exclude;

    //включает/выключает дублирование тегов, чувствителен к регистру
    public $caseSensitive = 0;

    //индификатор Ditto, осуществляющего вызов
    public $dittoID;

    //список найденных тегов в дочерниъ элементах дерева
    public $tagsList = array();


    public function __construct($model,$callString){

        $this->model = $model;
        $this->callString = $callString;

        //ID документа, где расположен вызов Ditto
        //Значение по умолчанию: Id текущего документа
        $this->landing = $this->model->_id;
    }

    /*
     * парсим строку вызова сниппета
     */
    public function parseString(){

        echo $this->callString.'<br>';

        $this->callString = str_replace('TvTagCloud?', '', $this->callString);

        echo $this->callString.'<br>';

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);


        foreach($params_list as $index=>$param){
            if(preg_match('/=`(.*?)`/',$param, $matches)){

                $param = str_replace(array('&','amp;','!]',']]','[!','[['), '', $param);

                $expl_list = explode('=',$param);

                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$matches[1]));
            }
        }

    }

    /*
     * обработка указанных параметров и формирование условий выборки данных
     */
    public function mergeCriteria(){

        //сперва укажим условие выборки по какому тегу
        $criteria = new EMongoCriteria();

        //указали по какому параметру ищим документы
        //$criteria->compare('tv.'.$this->tvTags, '!= ""');
        //$criteria->addCondition($this->tvTags, '', '$gte');

        $condition = array();
        //если указан исключающие значения, то укажим их в условии
        if(!empty($this->exclude)){
            $criteria->addCondition('tv.'.$this->tvTags, $this->exclude, '$gte');
            $condition = array('tv.'.$this->tvTags=>$this->exclude);
        }

        //parent - может быть списком через запятую
        $list = explode(',', str_replace(array('`',' '),'',$this->parent));
        $condition = array();
        foreach($list as $parent_id){
            $condition[] = array('parent'=>$parent_id);
        }
        if(!empty($condition)){
            $criteria->addOrCondition($condition);
        }

        return $condition;
    }

    /*
     * выборка данных по условию+формирование результата
     */
    public function action(){

        $this->tpl='<ul>';

        $criteria = $this->mergeCriteria();

        // price: { $gt: 10 }

        $model = Content::model()->findOne(array('_id'=>5046));

        $model->getChildrenDepth($model,0,3);

        //$childrens = $model->findedChildrens;

        //$rows = Content::model()->distinct('tv.'.$this->tvTags, array('parent'=>$this->parent));

        //$rows = Yii::app()->mongodb->Content->distinct('tv.'.$this->tvTags, array('parent'=>5046));

        //echo '<pre>'; print_r($rows);die();

        //формируем список ссылок для облака тегов
        foreach($model->findedChildrens as $row){
            //$this->tpl.='<li>'.CHtml::link($row->att);
            //echo '<pre>'; print_r($row->tv);
            //echo '<pre>'; print_r($row->_id);

            if(isset($row->tv[$this->tvTags])){
                //echo '<pre>'; print_r($row->tv);
                $tag = $row->tv[$this->tvTags];

                $expl = explode(',',$tag);

                if($expl){
                    foreach($expl as $str){
                        $this->tagsList[$str] = trim($str);
                    }
                }else{
                    $this->tagsList[$tag] = trim($tag);
                }
            }
        }

        echo '<pre>'; print_r($this->tagsList);

        $this->tpl.='</ul>';
        die();
    }

} 