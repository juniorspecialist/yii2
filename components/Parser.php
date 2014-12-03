<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03.08.14
 * Time: 23:26
 */

/*
 * из смешанного набора тегов и разного рода обозначений на вызовы сниппетов - формируем html-код
 * т.е. первоначально у нас может быть html-код со вставками включений всяких виджетов+сниппетов+hml
 * в итоге получаем чистый HTML-код, заменяя все вызовы сниппетов и чанков и виджетов их значениями
 */

namespace app\components;

use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;

class Parser {

    public $template;//шаблон вывода для статьи-документа
    public $model;//массив с данными по документу
    public $html;//результирующий HTML-код, после всех обработок


    /*
     * $template - содержимое шаблона, который использует данный документ
     * $model - содержимое контента, коллекция Content
     */
    public function __construct($template='', $model){
        $this->model = $model;
        $this->template = $template;
        $this->html = $template;
    }

    /*
     * проверяем есть ли вызовы тегов - сниппеты, чанки,тв-параметры
     * находим вызовы и заменяем их значениями
     */
    public function issetParseData($debug = false){

        $isset = false;

        //проверим надо ли ещё раз запускать
        if (preg_match('~{{(.*?)}}~', $this->html)) {$isset = true;}

        if(preg_match('~\[(\[|\!)(.*?)(\!|\])\]~ms', $this->html)){$isset = true;}

        //if (preg_match('~\[(\*|\+)(.*?)(\*|\+)\]~', $this->html)) {$isset = true;}

        //if (preg_match('/\[\+(.*)\+\]/', $this->html)) {$isset = true;}
        //[+phx:if=`[+artikul+]`:is=``:then=`CF 124 CSE`:else=`CF [+artikul+]`+]
        //if(preg_match('/\[\+(phx):(.*):(.*)\+\]/i',$this->html)){  $isset = true;}

        return $isset;
    }

    public function run(){

        $this->parseMetaTagsPage();

        $this->parseChunk();

        //die($this->html);

        $this->parseSnippet();

        //проверим надо ли запускать чанки,сниппеты и т.д. по странице
        if($this->issetParseData()){
            $this->run();
        }
    }

    /*
     * парсим сниппеты
     */
    public function parseSnippet(){

        $this->parseMetaTagsPage();

        //die($this->html);

         //preg_match_all('/\[(\[|\!|\+phx)(.*?)(\!|\+|\])\]/mis', $this->html, $matches);
        preg_match_all('/\[(\[|\!)(.*?)(\!|\])\]/mis', $this->html, $matches);

        //определяем вызовы PHX сниппета
        preg_match_all('/\[\+phx(.*?)\+\]/mis', $this->html, $matches_phx);

        if(!empty($matches_phx[0])){
            foreach($matches_phx[0] as $phx_rule){
                $matches[0][] = $phx_rule;
            }
        }

        //echo '<pre>'; print_r($matches[0]); //die();

        //отлавливаем PHX - isFolder - и добавим в список найденных снипетов
        preg_match('/\[(\*isfolder)(.*?)(`\*)\]/mis', $this->html, $isFolder);
        if(!empty($isFolder[0])){
            $matches[0][] = $isFolder[0];
        }

//        preg_match_all('/\[(\*\w)(.*?)(`\*)\]/mis', $this->html, $isParam);
//        if(!empty($isParam[0])){
//            //$matches[0][] = $isFolder[0];
//            echo '<pre>'; print_r($isParam); die();
//        }



        /*
         * <h1>[*h1*]</h1>
[*price-place:is=``:then=``:else=`[*price-place*]`*]
[*price-place-extra:is=``:then=``:else=`<button type="button" class="btn btn-block btn-info btn-large"  data-toggle="collapse" data-target="#prices">Полный прайс-лист <i class="icon-chevron-down icon-white"></i></button>
<div id="prices" class="collapse out">[*price-place-extra*]</div>`*]
[*content*]
         */
        $matches_main = array();

        $replace = array();
        foreach($matches[0] as $index=>$snippet){

            //echo 'snippet='.$snippet.'<br>';

            $replace[] = Parser::mergeSnippet($snippet, $this->model);//

            $matches_main[] = $matches[0][$index];
        }

        $this->html = str_replace($matches_main, $replace, $this->html);

        unset($matches_main);
        unset($replace);
    }

    /*
     * определяем вызов сниппета и запускаем его выполнение
     * определяем какой сниппет был вызван и потом запускаем сниппет на выполнение
     * $html - строка содержит вызов сниппета, определяем его вызов и что за сниппет
     */
    static function mergeSnippet($html, $model){

        //echo 'html='.$html.'<br>';

        $replace = '';//результат работы сниппета

        //нашли вызов сниппета - PHX
        if(preg_match('/(.*?):(.*?)/',$html)){

            //echo 'phx='.$html.'<br>';

            $phx = new \app\components\Phx($model,$html);
            $phx->html = $html;
            $phx->action();
            $replace =  $phx->result;
        }

        //ищем вызов сниппет
        if(preg_match('/(\W|^)Ditto(\W|$)/',$html)){

            //echo $html.'<br>';

            $ditto = new \app\components\Ditto($model, $html);
            //разбираем параметры вызова сниппета - Дитто
            $ditto->parseString();
            //запишим на страницу результат работы сниппета
            $replace = $ditto->result;

            //echo 'replace='.$replace.'<br>';
        }
        // ищем вызов сниппета - Wayfinder

        if(preg_match('/(\W|^)Wayfinder(\W|$)/',$html)){
            $wayfinder = new \app\components\Wayfinder($model, $html);
            //разбираем параметры вызова сниппета - Wayfinder
            $wayfinder->parseString();
            //запишим на страницу результат работы сниппета
            $replace = $wayfinder->result;
        }

        //нашли вызов сниппета - Хлебные крошки
        if(preg_match('/(\W|^)Breadcrumbs(\W|$)/i',$html)){
            $breadcrumbs = new \app\components\Breadcrumbs($model);
            //$matches_main[] = $matches[0][$index];
            $replace = $breadcrumbs->run();
        }

        //вызов сниппета(TvTagCloud) - для тегирования по тв-параметру
        if(preg_match('/(\W|^)TvTagCloud(\W|$)/i',$html)){
            $tv_tag = new \app\components\TvTagCloud($model,$html);
            $tv_tag->html = $html;
            $tv_tag->parseString();
            $tv_tag->action();
            $replace =  $tv_tag->result;
            //die();
        }

        //вызов сниппета (GlobalDitto2) - перелинковка страничек
        if(preg_match('/(\W|^)GlobalDitto2(\W|$)/i',$html)){

            $cross_link = new \app\components\CrossLinks($model,$html);
            $cross_link->html = $html;
            $cross_link->parseString();
            $cross_link->action();
            $replace =  $cross_link->result;
        }

        return $replace;//возвращаем результат работы сниппета
    }

    /*
     * проставляем мета-теги, заголовок страницы, ключевые слова и т.д.
     */
    public function parseMetaTagsPage(){

        //парсим хитрые PHX-условия
        //обработка вызовов PHX условий с произвольными полями для условия
        /*
         * [*price-place:is=``:then=``:else=`[*price-place*]`*] [*price-place-extra:is=``:then=``:else=`Полный прайс-лист [*price-place-extra*]`*]
         */

        if (preg_match_all('/^\[\*(.*?):(.*?)`\*\]/mis', $this->html, $matches_phx)) {
            //$matches_phx[0] - массив строк совпадений
            if(!empty($matches_phx[0])){
                foreach($matches_phx[0] as $phx_snippet_call_string){
                    $phx = new \app\components\Phx($this->model,$phx_snippet_call_string);
                    $phx->html = $phx_snippet_call_string;
                    $phx->action();
                    $replace =  $phx->result;
                    $this->html = str_replace($phx_snippet_call_string, $replace, $this->html);
                }
            }
        }


        if (preg_match_all('~\[(\*|\+)(.*?)(`\*|\*|\+)\]~', $this->html, $matches)) {

            foreach($matches[2] as $param){

                //отфильтровываем мета-теги в которых встречаются PHX условия
                if(preg_match('/(:is=|:else|:then)/', $param)){
                    continue;
                }

                //заменяем вызов чанка его содержимым
                $replaced = false;

                //бывает, что
                if(is_array($this->model)){

                    //заменяем вызов чанка его содержимым
                    if(!empty($this->model[$param])){
                        $replaced = true;
                        $this->html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $this->model[$param], $this->html);
                    }

                    if(isset($this->model['tv_'.$param])){
                        $replaced = true;
                        $this->html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $this->model['tv_'.$param], $this->html);
                    }

                    if(isset($this->model['id'])){
                        $this->html = str_replace('[~'.$this->model['id'].'~]', Url::to(['/site/index', 'alias'=>$this->model['alias']]) , $this->html);
                        $this->html = str_replace('[+url+]', Url::to(['/site/index', 'alias'=>$this->model['alias']]) , $this->html);
                    }
                }

                // если не нашли значения для замены параметры, то тогда просто заменим на пустое значение
                if(!$replaced){
                    $this->html = str_replace(array('[*'.$param.'*]'), '', $this->html);
                }
            }
        }

        //заглушка - заменяем значения некоторых параметров
        $this->html = str_replace('<base href="[(site_url)]" />', '<base href="'.Url::to().'" />', $this->html);
        $this->html = str_replace('[*canonical*]', '', $this->html);
        $this->html = str_replace('[*pagetitle*]', $this->model['pagetitle'], $this->html);
    }

    /*
     * поиск вызова чанков в коде
     */
    public function parseChunk(){

        $replace = array ();

        $matches_main= array ();

        if (preg_match_all('~{{(.*?)}}~', $this->html, $matches)) {

            foreach($matches[1] as $title_chunk){
                //заменяем вызов чанка его содержимым
                $matches_main[] = '{{'.$title_chunk.'}}';
                $replace[] = \app\models\Chunk::findChunkByName($title_chunk);
            }

            //вызов чанков заменяем их значениями
            $this->html = str_replace($matches_main, $replace, $this->html);

            $this->parseSnippet();

            unset($matches_main);unset($replace);
        }
    }

    /*
     * заменяем все вызовы тв-параметров
     */
    static function mergeTvParamsContent($html, $model){

        //if (preg_match_all('~\[(\*|\+)(.*?)(\*|\+)\]~', $html, $matches)) {
        if (preg_match_all('~\[(\*|\+)[\w|\-]{1,}(\*|\+)\]~', $html, $matches)) {

            //заменяем вызов чанка его содержимым
            $replaced = false;

            foreach($matches[0] as $param){

                $param = str_replace(array('[',']','+', '*'),'', $param);

                if(is_array($model)){
                    //заменяем вызов чанка его содержимым
                    if(!empty($model[$param])){
                        $replaced = true;
                        $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model[$param], $html);
                    }else{
                        if(isset($model['tv_'.$param])){
                            $replaced = true;
                            $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model['tv_'.$param], $html);
                        }
                        //спец. замена для сниппета- перелинковки страниц
                        if(isset($model['tv_'.$param])){
                            $replaced = true;
                            $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model['tv_'.$param], $html);
                        }
                        if(isset($model['tv_'.$param.'1'])){
                            $replaced = true;$html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model['tv_'.$param.'1'], $html);
                        }

                        if(isset($model['tv_'.$param.'2'])){
                            $replaced = true;$html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model['tv_'.$param.'2'], $html);
                        }
                        if(isset($model['tv_'.$param.'3'])){
                            $replaced = true;$html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model['tv_'.$param.'3'], $html);
                        }
                    }
                    if(isset($model['id'])){
                        $html = str_replace('[~'.$model['id'].'~]', Url::to(['/site/index', 'alias'=>$model['alias']]) , $html);
                        $html = str_replace('[+url+]', Url::to(['/site/index', 'alias'=>$model['alias']]) , $html);
                    }
                }else{
                    //заменяем вызов чанка его содержимым
                    if(!empty($model->{$param})){
                        $replaced = true;
                        $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model->{$param}, $html);
                    }else{
                        if(isset($model->{'tv_'.$param})){
                            $replaced = true;
                            $html = str_replace(array('[*'.$param.'*]', '[+'.$param.'+]'), $model->{'tv_'.$param}, $html);
                        }
                    }

                    if(isset($model->id)){
                        $html = str_replace('[~'.$model->id.'~]', Url::to(['/site/index', 'alias'=>$model->alias]) , $html);
                        $html = str_replace('[+url+]', Url::to(['/site/index', 'alias'=>$model->alias]) , $html);
                    }
                }

                $html = str_replace(array('[+'.$param.'+]','[*'.$param.'*]'),'',$html);
            }
        }
        return $html;
    }

    static function mergeChunkContent($html){
        $replace= array ();
        $matches= array ();
        if (preg_match_all('~{{(.*?)}}~', $html, $matches)) {
            foreach($matches[1] as $title_chunk){
                //заменяем вызов чанка его содержимым
                $html = str_replace('{{'.$title_chunk.'}}', \app\models\Chunk::findChunkByName($title_chunk), $html);
            }
        }

        return $html;
    }
} 