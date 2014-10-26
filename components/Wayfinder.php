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

    public $hideSubMenus;// скрывать или нет суб-меню
    public $outerClass;//класс для CSS меню
    public $startId;//ID страницы с которой будем выбирать дочерние страницы
    public $sortBy;//по какому параметру сортировать
    public $sortDir;//направление сортировки


    /*
     * парсим строку вызова сниппета, разбираем её и определеяем её параметры для вызова
     */
    public function parseString(){

        //строка вызова пример - Ditto? &tpl=`radiator-tovar-ditto` &extenders=`request`
        $this->callString = str_replace('Wayfinder?', '', $this->callString);

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
                //<a href="/portfolio.html" title="Наши клиенты">Клиенты</a>.
                //http://modx/[(site_url)]?r=site/index&alias=produkcija_bofill
                $class = '';// class="active"

                //выделим текущую страницу в списке меню
                if(Url::to()==Url::toRoute(['/site/index', 'alias'=>$model['alias']])){
                    $class = 'class="active" ';
                }else{
                    if(is_array($this->model)){
                        if($model['id']==$this->model['parent']){//выделим родительскую страницу в меню
                            $class = 'class="active" ';
                        }
                    }else{
                        if($model['id']==$this->model->parent){//выделим родительскую страницу в меню
                                $class = 'class="active" ';
                            }
                        }
                }

                $this->result.='<li '.$class.' >'.Html::a($model['menutitle'],Url::toRoute(['/site/index', 'alias'=>$model['alias']]), array('title'=>$model['pagetitle'])).'</li>';
            }
            $this->result.= '</ul>';
        }
    }
}