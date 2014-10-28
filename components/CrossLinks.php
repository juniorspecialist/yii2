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

        //echo $this->callString.'<br>';//die();

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

        $query->select(['id', 'tv_seria','tv_vendorcode','alias', 'tv_param1', 'tv_param2', 'tv_param3', 'pagetitle', 'tv_name', 'tv_model', 'tv_artikul', 'tv_diametr', 'tv_weight']);

        // compose the query
        $query->from(Content::collectionName())
            //->where((array)['and', 'parent'=>(int)$this->model['parent'], ['or', 'tv_param1!=""', 'tv_param2!=""', 'tv_param3!=""']]);
            //->where(['not in', ['tv_param1'=>'""'], ['tv_param2' => '""'], ['tv_param3' => '""'] ])
            ->Where(['parent'=>(int)$this->model['parent']])
//            ->andWhere(['not in', 'tv_param1', '""'])
//            ->andWhere(['not in', 'tv_param2', '""'])
//            ->andWhere(['not in', 'tv_param3', '""'])
            ;
//            ->orFilterWhere(['not in', 'tv_param1', '""'])
//            ->orFilterWhere(['not in', 'tv_param2', '""'])
//            ->orFilterWhere(['not in','tv_param3', '""']);
        //$query->limit(4);

        //echo '<pre>'; print_r($query); die();

        $rows = $query->all();

        //echo '<pre>'; print_r($rows); die();

        $find = false;

        $count_find = 1;

        if(!empty($rows)){
            foreach($rows as $child) {

                if($count_find==4){break;}

                //если находим тек. док. то даём флаг старта для формирования блоков перелинковки
                if($child['id']==$this->model['id']){$find = true;continue;}

                if(empty($child['tv_param'.$count_find])){ continue;}

                //нашли тек. документ по очереди в выборке, теперь берём следующие за ним доки, чтоб сформировать блоки перелинковки
                if($find==true){

                    $this->result.= str_replace('[+param+]', $this->replaceParamsInTemplate($child, $count_find, $child['tv_param'.$count_find]), $this->content_tpl);

                    $count_find++;
                }
            }

            if($find==true){
                $this->foreachDataToTpl($rows, $find, $count_find);
            }
        }

        unset($rows);
    }

    public function foreachDataToTpl($rows, $find = false, $count_find = 0){
        foreach($rows as $child) {

            if($count_find==4){break;}

            //если находим тек. док. то даём флаг старта для формирования блоков перелинковки
            if($child['id']==$this->model['id']){$find = true;continue;}

            if(empty($child['tv_param'.$count_find])){ continue;}

            //нашли тек. документ по очереди в выборке, теперь берём следующие за ним доки, чтоб сформировать блоки перелинковки
            if($find==true){

                $this->result.= str_replace('[+param+]', $this->replaceParamsInTemplate($child, $count_find, $child['tv_param'.$count_find]), $this->content_tpl);

                $count_find++;
            }
        }
    }

    /*
     * производим замену всех тв-параметров в шаблоне сниппета на их значения
     */
    public function replaceParamsInTemplate($child, $count_find, $param){

        if(isset($child['tv_vendorcode'])){$param= str_replace('{*vendorcode*}', $child['tv_vendorcode'], $param);}else{$param= str_replace('{*vendorcode*}', '', $param); }

        if(isset($child['tv_seria'])){$param= str_replace('{*seria*}', $child['tv_seria'], $param);}else{$param= str_replace('{*seria*}', '', $param); }

        if(isset($child['tv_model'])){$param= str_replace('{*model*}', $child['tv_model'], $param);}else{$param= str_replace('{*model*}', '', $param); }

        if(isset($child['tv_name'])){$param= str_replace('{*name*}', $child['tv_name'], $param);}else{$param= str_replace('{*name*}', '', $param); }

        if(isset($child['tv_artikul'])){$param= str_replace('{*artikul*}', $child['tv_artikul'], $param);}else{$param= str_replace('{*artikul*}', '', $param); }

        if(isset($child['tv_diametr'])){$param= str_replace('{*diametr*}', $child['tv_diametr'], $param);}else{$param= str_replace('{*diametr*}', '', $param); }

        if(isset($child['tv_weight'])){$param= str_replace('{*weight*}', $child['tv_weight'], $param);}else{$param= str_replace('{*weight*}', '', $param); }

        if(isset($child['tv_glubina'])){$param= str_replace('{*glubina*}', $child['tv_glubina'], $param);}else{$param= str_replace('{*glubina*}', '', $param); }

        if(isset($child['tv_du'])){$param= str_replace('{*du*}', $child['tv_du'], $param);}else{$param= str_replace('{*du*}', '', $param); }

        $param= str_replace(
            ['<a>','{*pagetitle*}'],
            ['<a href="'.Url::to(['/site/index','alias'=>$child['alias']]).'">',$child['pagetitle']],
            $param);

        return $param;
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
}