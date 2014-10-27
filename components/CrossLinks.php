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

                //echo '<pre>'; print_r($expl_list);

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

        $query->select(['id', 'alias', 'tv_param1', 'tv_param2', 'tv_param3', 'pagetitle', 'tv_name', 'tv_model', 'tv_artikul', 'tv_diametr', 'tv_weight']);

        // compose the query
        $query->from(Content::collectionName())
            ->where(['parent'=> (int)$this->model['parent']]);
        //$query->limit(5);

        $rows = $query->all();

        $find = false;

        $count_find = 1;

        if(!empty($rows)){
            foreach($rows as $child) {

                //die($this->content_tpl);


                if($count_find==4){break;}

                //если находим тек. док. то даём флаг старта для формирования блоков перелинковки
                if($child['id']==$this->model['id']){$find = true;}

                //нашли тек. документ по очереди в выборке, теперь берём следующие за ним доки, чтоб сформировать блоки перелинковки
                if($find){

                    if(isset($child['tv_vendorcode'])){$this->result.= str_replace('{*vendorcode*}', $child['tv_vendorcode'], $this->content_tpl);}

                    if(isset($child['tv_seria'])){$this->result.= str_replace('{*seria*}', $child['tv_seria'], $this->content_tpl);}

                    if(isset($child['tv_model'])){$this->result.= str_replace('{*model*}', $child['tv_model'], $this->content_tpl);}

                    if(isset($child['tv_name'])){$this->result.= str_replace('{*name*}', $child['tv_name'], $this->content_tpl);}

                    if(isset($child['tv_artikul'])){$this->result.= str_replace('{*artikul*}', $child['tv_artikul'], $this->content_tpl);}

                    if(isset($child['tv_diametr'])){$this->result.= str_replace('{*diametr*}', $child['tv_diametr'], $this->content_tpl);}

                    if(isset($child['tv_weight'])){$this->result.= str_replace('{*weight*}', $child['tv_weight'], $this->content_tpl);}

                    if(isset($child['tv_glubina'])){$this->result.= str_replace('{*glubina*}', $child['tv_glubina'], $this->content_tpl);}

                    if(isset($child['tv_du'])){$this->result.= str_replace('{*du*}', $child['tv_du'], $this->content_tpl);}

                    $this->result.= str_replace(
                        ['<a>','{*pagetitle*}'],
                        ['<a href="'.Url::to(['/site/index','alias'=>$child['alias']]).'">',$child['pagetitle']],
                    $child['tv_param'.$count_find]);

                    //$this->result.= str_replace('',$this->content_tpl, $model);

                    //$this->result.=Parser::mergeTvParamsContent($this->content_tpl, $model);
                    $count_find++;
                }
            }
        }
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