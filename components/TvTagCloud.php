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

use yii\helpers\Html;


class TvTagCloud extends \app\components\Ditto{

    //параметры работы сниппета

    //внешний вид
    // cloud | list | custom ( в виде облака, маркированного списка или выборочно)
    public $displayType = 'cloud';

    //ID контейнера, в котором находятся отображаемые документы(можно указывать несколько ID через запятую)
    public $parent = 0;

    //глубина просмотра
    public $depth = 10;

    public $template;//ID шаблона по которому будем фильтровать документы для более быстрой выборки

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

        $this->model = $model;// это массив
        $this->callString = $callString;
    }

    /*
     * парсим строку вызова сниппета
     */
    public function parseString(){

        $this->callString = str_replace('TvTagCloud?', '', $this->callString);

        //echo $this->callString.'<br>';

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);


        foreach($params_list as $index=>$param){
            if(preg_match('/=`(.*?)`/',$param, $matches)){

                $param = str_replace(array('&','amp;','!]',']]','[!','[['), '', $param);

                $expl_list = explode('=',$param);

                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$matches[1]));
            }
        }

        //TODO заглушка проверить чтобы в вызовк сниппета облака-тегов был указан шаблон для фильтрации доков
        $this->template = 92;

    }



    /*
     * выборка данных по условию+формирование результата
     */
    public function action(){

        $this->result='<ul>';


        $collection = \Yii::$app->mongodb->getCollection(Content::collectionName());

        $tags = $collection->aggregate(
            array( '$match' => array( 'template'=> $this->template) ),//,'tv_'.$this->tvTags=>['in'=>['/.,./']]
            array( '$group' => array(
                '_id'=>'$tv_'.$this->tvTags,
                'count'=>array( '$sum' => 1 ),
            ))
        );

        //формируем список ссылок для облака тегов
        foreach($tags as $tag){

            $expl = explode(',',$tag['_id']);

            if($expl){
                foreach($expl as $str){

                    if(empty($str)){continue;}

                    $str = trim($str);

                    $this->tagsList[$str] = $tag['count'];
                }
            }else{
                $this->tagsList[$tag] = trim($tag);
            }
        }

        if(!empty($this->tagsList)){
            foreach($this->tagsList as $name_tag=>$count_tag){
                $this->result.='<li>'.Html::a($name_tag.' ('.$count_tag.')',Url::to().'?'.$this->dittoID.'_tags='.$name_tag);
                $this->result.'</li>';
            }
        }
        $this->result.='</ul>';
    }
}