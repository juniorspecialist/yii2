<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.10.14
 * Time: 23:44
 */

namespace app\components;

use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;

class CrossLinks extends \app\components\Ditto{


    public $id;//ID-документа, тек.страницы, который мы исключаем из выборки по парамам

    public function __construct($model,$callString){

        $this->model = $model;

        $this->callString = $callString;
    }

    /*
        * парсим строку вызова сниппета
        */
    public function parseString(){

        echo $this->callString.'<br>';//die();

        $this->callString = str_replace('GlobalDitto2?', '', $this->callString);

        //echo $this->callString.'<br>';

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);


        foreach($params_list as $index=>$param){
            if(preg_match('/=`(.*?)`/',$param, $matches)){

                $param = str_replace(array('&','amp;','!]',']]','[!','[['), '', $param);

                $expl_list = explode('=',$param);

                echo '<pre>'; print_r($expl_list);

                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$matches[1]));
            }
        }

        //получаем содержимое шаблона вывода
        $this->getContentTpl();
    }

    /*
     * производится выборка документов исключая тек. документ
     * формируем блоки пока не набирём нужное кол-во блоков(параметр указываем в настройках)
     * текст для блоков берётся из PARAM-ов+заменяем ссылками значения внутри этого текста(парамов)
     *
     */
    public function action(){

        $query = new Query;

        // compose the query
        $query->from(Content::collectionName());

        die();
    }

    /*
     * находим шаблон для вывода данных
     */
    public function getContentTpl(){
        if(!empty($this->tpl)){
            $this->content_tpl = \app\models\Chunk::findChunkByName($this->tpl);
        }else{
            die('empty tpl in call string CROSSLink:'.$this->callString );
        }
    }

    //public function parseChunk
} 