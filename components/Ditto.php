<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 06.08.14
 * Time: 18:29
 */

/*
 * обработка сниппета Ditto из модкс ево
 *
 */
namespace app\components;

use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;
use \app\components\Parser;

class Ditto {

    public $startID;//

    public $model;//текущая страница

    public $callString;//строка вызова сниппета Ditto из кода страницы с параметрами

    public $result = '';// результат работы сниппета Ditto

    public $tpl;// шаблон для вывода данных(обычно какой-то чанк)

    public $parent;

    public $documents;//список документов через запятую, которые надо выбирать

    public $content_tpl;// содержиме чанка-шаблоны для вывода

    public $filter;//фильтр который используется для заданий условий выборки для дитто

    public $display ;//сколько найденных позиций показывать(пока что используем этот как лимит при отборе)

    public function __construct($model,$callString){



        $callString = Parser::mergeTvParamsContent($callString, $model);

        $this->model = $model;

        $this->callString = $callString;
    }

    /*
     * парсим фильтрацию для дитто и преобразовываем в условие EMongoCriteria
     * &filter - Фильтр для отсеивания документов
        Формат: поле,критерий,тип сравнения
        Значение по умолчанию: NULL
        Примечание: Используется формат `поле,критерий,тип сравнения` с запятой между значениями.
    если поле начинается с букв "tv" - значит это тв-параметр для фильтрации
        Список фильтров:
        По умолчанию: NULL
        Типы сравнения:
        1 - != (не соответствует критерию)
        2 - == (соответствует критерию)
        3 - < (меньше критерия)
        4 - > (больше критерия)
        5 - <= (меньше или равен критерию)
        6 - >= (больше или равен критерию)
        TODO доделать:7 - (не содержит текст критерия),8 - (содержит текст критерия)

        Может содержать несколько запросов, разделенных глобальным разделителем |.

        $query - это запрос, который мы формируем анализируя все параметры для фильтрации в параметре  - FILTER
     */
    public function getFilterDitto(){

        //если нет данных по параметру - FILTER то не используем его анализ
        if(empty($this->filter)){return [];}

        //пример фильтра =  &filter=`template,92,1|tvoblast,[*oblast*],1`

        //определим скольско условий было прописано в параметре
        $condition_list = explode('|',$this->filter);

        //условия из массива который преобразовываем в условия для запроса - Query
        $criteria_condition_list = array();

        //пробегаемся по списку условий и добавляем их в Query
        foreach($condition_list as $condition){

            //убираем пробелы(вдруг случайно затисались)
            $condition = str_replace(' ', '', $condition);

            //в условиях к запросу, могут встречаться плейсхолдеры, учитываем это
            $condition = Parser::mergeTvParamsContent($condition, $this->model);

            //парсим одно из условий и определяем что за параметры использовать при фильтрации и с какими условиями
            //параметры условия разделены запятой(например - tvnasos,канализационные и дренажные,1)
            $params_list = explode(',', $condition);

            //print_r($params_list); die();
            //определим используется ли фильтр по тв-параметрам или по параметрам документа
            if(preg_match('/^tv(.*?)/mis',$params_list[0], $matches)){

                $params_list[0] = preg_replace('/^tv(.*?)/', '',$params_list[0]);

                $name_param = 'tv_'.$params_list[0];

                $params_list[0] = $name_param;//перезаписали без приставки
            }

            //$params_list[0] - поле для условия,$params_list[1]-значение для условия,$params_list[2] - тип сравнения
            $compare = $this->getCompareFilterCondition((int)$params_list[2]);

        }

        return $criteria_condition_list;
    }

    /*
     * список соответствий цифре в условии фильтрации по Дитто
     * тому типу сравнения к которому оно подвязано
     * $number_of_compare - цифра, которая показывает какое условие будем применять к фильтру по значению
     */
    public function getCompareFilterCondition($number_of_compare=''){
        if(empty($number_of_compare)){
            //7 - (не содержит текст критерия),8 - (содержит текст критерия)
            return array(
                1=>'not in',//!= не соответствует критерию)
                2=>'and',
                3=>'<',//<(меньше критерия)
                4=>'>',//>(больше критерия)
                5=>'<=',//<= (меньше или равен критерию)
                6=>'>=',//>= больше или равен критерию)
                7=>'not like',//>= больше или равен критерию)
                8=>'like',
            );
        }else{
            $list = self::getCompareFilterCondition();
            return (string)$list[$number_of_compare];
        }
    }


    /*
     * парсим строку вызова сниппета, разбираем её и определеяем её параметры для вызова
     */
    public function parseString(){

        //строка вызова пример - Ditto? &tpl=`radiator-tovar-ditto` &extenders=`request`
        $this->callString = str_replace('Ditto?', '', $this->callString);

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);

        foreach($params_list as $index=>$param){
            if(preg_match('/=`(.*?)`/',$param, $matches)){

                $param = str_replace(array('&','amp;','!]',']]','[!','[['), '', $param);

                $expl_list = explode('=',$param);

                $this->{$expl_list[0]} = trim(str_replace(array('`'),'',$matches[1]));
            }
        }

        //получаем содержимое шаблона вывода
        $this->getContentTpl();

        $this->action();
    }

    /*
     * список перменных получили, обработка входящих параметров и вывод реультата
     */
    public function action(){

        //список документов, которые будут условием выборки данных
        $list = array();

        $query = new Query;

        // compose the query
        $query->from(Content::collectionName());

        //если указан 1 или список документов - делаем выборку по ним
        if(!empty($this->documents)){

            $list = explode(',', str_replace(array('`',' '),'',$this->documents));

            //применим список ID доков по которым будем делать поиск контента
            foreach($list as $id){
                $query->andWhere(['id' => (int)$id]);
            }

        }elseif(!empty($this->startID)){
            //если указан начальный документ, который будет стартом для выборки доков
            $query->where([
                'parent' => (int)$this->startID,
            ]);
        }

        elseif(!empty($this->parent)||empty($this->parent) && empty($this->documents)){
            //выборка всех потомков по текущему документу, т.е. тек. документ - родитель
            //не указано, какие доки выбирать выбираем дочерние элементы тек. дока
            if(is_array($this->model)){
                $query->where([
                    'parent' =>(int) $this->model['id'],
                ]);
            }else{
                if(is_array($this->model)){
                    $query->where([
                        'parent' =>(int) $this->model['id'],
                    ]);
                }else{
                    $query->where([
                        'parent' =>(int) $this->model->id,
                    ]);
                }
            }

        }

        //если были переданые параметры для фильтрации - обработаем их и подставим в условие
        $andWhere = $this->getFilterDitto();

        if(!empty($filter)){
            foreach($andWhere as $rule){
                $query->andWhere($rule);
            }
        }

        //если указан был лимит - учитываем его
        if(!empty($this->display)){$query->limit($this->display);}

        $query->orderBy('createdon desc');

        //echo '<pre>'; print_r($query);

        $find = $query->all();

        foreach($find as $model) {
            $this->result.=Parser::mergeTvParamsContent($this->content_tpl, $model);
        }

        //echo $this->result.'<br>';
    }

    /*
     * находим шаблон для вывода данных
     */
    public function getContentTpl(){
        if(!empty($this->tpl)){
            $this->content_tpl = \app\models\Chunk::findChunkByName($this->tpl);
        }else{
            die('empty tpl in call string Ditto:'.$this->callString );
        }
    }

} 