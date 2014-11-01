<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.08.14
 * Time: 23:25
 */
namespace app\components;

use yii\helpers\Html;
use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;

class Wayfinder extends  \app\components\Ditto{

    //Скрывать подменю и выводить для активного пункта(Значение по умолчанию: 0)
    public $hideSubMenus = 0;// скрывать или нет суб-меню(т.е. по текущ. доку будет выборка и это будет подменю)
    public $outerClass;//класс для CSS меню(CSS-класс для контейнера меню)
    public $startId;//ID страницы с которой будем выбирать дочерние страницы
    public $sortBy;//по какому параметру сортировать
    public $sortDir;//направление сортировки

    public $level = 0;// Количество уровней в меню(по умолчанию: 0)(0 - показывать все уровни)


    public $innerClass = '';//CSS-класс для подпунктов меню(Формат: название CSS класса)

    public $current_level = 0;//счётчик уровней вложенности меню


    /*
     * парсим строку вызова сниппета, разбираем её и определеяем её параметры для вызова
     */
    public function parseString(){

        //строка вызова пример - Ditto? &tpl=`radiator-tovar-ditto` &extenders=`request`
        $this->callString = str_replace('Wayfinder?', '', $this->callString);

        //echo $this->callString.'<br>';

        //получаем массив параметров для вызова обработки
        $params_list = explode('&', $this->callString);

        foreach($params_list as $index=>$param){
            if(preg_match('/=/',$param)){
                //echo 'i='.$index.'<br>';//echo 'p='.$param.'<br>';
                $expl_list = explode('=',$param);
                $this->{$expl_list[0]} = trim(str_replace(array('`','[',']','!'),'',$expl_list[1]));
            }
        }
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
        $query->select(['menutitle', 'id','pagetitle', 'alias'])
            ->from(Content::collectionName())
            ->where(['parent'=>(int)$this->startId])
            ->andWhere(['not in', 'menutitle', '']);

        if(!empty($this->sortBy)){
            $query->orderBy(array($this->sortBy=>'asc'));
        }

        // execute the query
        $rows = $query->all();

        $this->result = '<ul class="'.$this->outerClass.'">';

        if(!empty($rows)){
            foreach($rows as $model) {

                $current_item = false;//текущий элемент меню

                $class = '';// class="active"

                //выделим текущую страницу в списке меню
                if(Url::to()==Url::toRoute(['/site/index', 'alias'=>$model['alias']])){
                    $current_item = true;
                    $class = 'class="active" ';
                }else{
                    if(is_array($this->model)){
                        if($model['id']==$this->model['parent']){//выделим родительскую страницу в меню
                            $class = 'class="active" ';
                            $current_item = true;
                        }
                    }else{
                        if($model['id']==$this->model->parent){//выделим родительскую страницу в меню
                                $class = 'class="active" ';
                                $current_item = true;
                            }
                        }
                }

                $this->result.='<li '.$class.' >'.Html::a($model['menutitle'],Url::toRoute(['/site/index', 'alias'=>$model['alias']]), ['title'=>$model['pagetitle']]).'</li>';


                //значит неограниченное кол-во вложенностей уровней меню
                if($this->level==0){$this->level = 100;}

                //формирование подменю
                $this->sub_item_menu($model['id'], $current_item);
            }

            unset($rows);

            $this->result.= '</ul>';
        }
    }

    /*
     * строим подменю для указанного ID-документа
     */
    public function  sub_item_menu($doc_id, $current_item){

        //если надо выводим подменю по текущему выделенному пункту меню
        if($current_item && $this->level!==0 && $this->current_level<$this->level){

            if($this->hideSubMenus==1 || $this->hideSubMenus==true){

                $this->result.= '<ul class="'.$this->innerClass.'">';

                $query_sub_menu = new Query;
                // compose the query
                $query_sub_menu->select(['menutitle', 'id','pagetitle', 'alias'])
                    ->from(Content::collectionName())
                    ->where(['parent'=>(int)$doc_id])
                    ->andWhere(['not in', 'menutitle', '']);
                $query_sub_menu->orderBy('menutitle');

                // execute the query
                $rows_sub_menu = $query_sub_menu->all();
                if($rows_sub_menu){
                    foreach($rows_sub_menu as $sub_item){

                        $class = '';

                        $current_item = false;//текущий элемент меню

                        //выделим текущую страницу в списке меню
                        if(Url::to()==Url::toRoute(['/site/index', 'alias'=>$sub_item['alias']])){
                            $current_item = true;
                            $class = 'class="active" ';
                        }else{
                            if(is_array($this->model)){
                                if($sub_item['id']==$this->model['parent']){//выделим родительскую страницу в меню
                                    $class = 'class="active" ';
                                    $current_item = true;
                                }
                            }else{
                                if($sub_item['id']==$this->model->parent){//выделим родительскую страницу в меню
                                    $class = 'class="active" ';
                                    $current_item = true;
                                }
                            }
                        }

                        $this->result.='<li '.$class.' >'.Html::a('<i class="icon-cog"></i>'.$sub_item['menutitle'],Url::toRoute(['/site/index', 'alias'=>$sub_item['alias']]), ['title'=>$sub_item['pagetitle']]).'</li>';

                        $this->sub_item_menu($sub_item['id'],$current_item);

                    }
                }


                unset($rows_sub_menu);

                $this->result.= '</ul>';

                $this->current_level++;//увеличили счётчик уровней вложенности меню

            }
        }
    }
}