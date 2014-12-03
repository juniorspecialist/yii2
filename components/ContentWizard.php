<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.09.14
 * Time: 20:28
 */
namespace app\components;

/*
 * конструктор форм полей добавления/редактирования документов:
 * формируем список тв-параметров с их значениями по умолчанию
 * либо уже существующими+соответствующими типами полей
 */

use yii\data\ActiveDataProvider;
use yii\mongodb\Query;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class ContentWizard {

    public $template;// сущность - шаблон(к нему подвязан список тв-параметров)

    public $model;//сущность - контент-документ, хранит в себе список параметров

    public $tv; //список тв-параметров по которым будем формировать HTML код

    public $form;//класс формы, где мы выводим код тв-параметров

    public $isNewRecord = false;

    public function __construct($model, $template, $form=''){
        $this->model = $model;
        $this->template = $template;
        $this->form = $form;
    }

    /*
     * обработка данных и формирование тв-параметров по их типам и значениям
     */
    public function run(){

        //список тв-параметров, подвязанных к шаблону
        //var_dump($this->template);die();
        $tv_list = $this->template->tvlist;

        if(!empty($tv_list)){
            //проходимся по списку тв-параметров, котор. подвязаны к шаблону
            foreach($tv_list as $tv){

                //формируем HTML-код относительно параметров тв-параметра
                if($this->isNewRecord){

                    //определяем значение для тв-параметра
                    $value = (!empty($_POST['tv'][$tv['tv_name']]))?$_POST['tv'][$tv['name']]:$_POST['tv'][$tv['name']];

                    $this->tv[] = $this->getHtmlElementByTv($tv, $value);

                }else{
                    //значение тв-параметров берём из их значений из БД
                    $this->tv[] = $this->getHtmlElementByTv($tv, isset($this->model['tv_'.$tv['name']])?$this->model['tv_'.$tv['name']]:'');
                }
            }
        }
    }

    /*
     * на основании типа тв-параметра - возвращаем HTML-элемент со значениями
     * возвращаем HTML элемент+ его название(label)
     */
    public function getHtmlElementByTv($tv, $value = ''){
        //текстовый тип тв-параметра
        if($tv['type']=='text'){
            return [Html::textInput('Content[tv_'.$tv['name'].']', $value, ['style'=>'width:100%','class'=>'form-control']), $this->getLabelHtmlElement($tv)];
        }
        //текстовый - textarea тип тв-параметра
        if($tv['type']=='textareamini' ||  $tv['type']=='textarea' || $tv['type']=='richtext'){
            return [Html::textarea('Content[tv_'.$tv['name'].']', $value, ['style'=>'width:100%','class'=>'form-control']),$this->getLabelHtmlElement($tv)];
        }
        //текстовый тип тв-параметра
        if($tv['type']=='number'){
            return [Html::textInput('Content[tv_'.$tv['name'].']', $value, ['style'=>'width:100%','class'=>'form-control']), $this->getLabelHtmlElement($tv)];
        }
        //картинка- тип тв-параметра
        if($tv['type']=='image'){
            return [Html::textInput('Content[tv_'.$tv['name'].']', $value, ['style'=>'width:100%','class'=>'form-control']), $this->getLabelHtmlElement($tv)];
        }
        //выпадающий список- тип тв-параметра
        //формат:Disabled==-1||Base Name==0||Append Extension==1||Folder==2  ( "default_text": "-1" - индекс)
        //или Этиленгликоль с традиционными присадками || Этиленгликоль с карбоксилатными присадками || Пропиленгликоль
        if($tv['type']=='dropdown'){
            //пример значений:"elements": "не указано||комплектующие||услуги||теплообменники",
            //"default_text": "не указано"

            $data = array();
            //преобразовываем строку в массив значений
            $explode = explode('||', $tv['elements']);
            foreach($explode as $row){
                //2 варианта, когда(Disabled==-1) и когда (не указано||комплектующие||услуги)
                if(preg_match('/==/i', $row)){//вариант(1)
                    $expl_str = explode('==', $row);
                    $data[$expl_str[1]] = $expl_str[0];
                }else{//вариант(2)
                    $data[] = $row;
                }
            }

            return [Html::dropDownList('Content[tv_'.$tv['name'].']', $value, $data,['style'=>'width:100%','class'=>'form-control']), $this->getLabelHtmlElement($tv)];
        }
        //чекбокс - тип тв-параметры
        /*"elements": "Да==1",
          "default_text": "Нет==0"
         */
        if($tv['type']=='checkbox'){
            //формат значения:"Да==1"
            $label_checkbox = '';
            ////2 варианта, когда(Disabled==-1) и когда (не указано||комплектующие||услуги)
            if(preg_match('/==/i', $tv['elements'])){//вариант(1)
                $expl_str = explode('==', $tv['elements']);
                $data[$expl_str[1]] = $expl_str[0];
                $label_checkbox = $expl_str[0];
            }else{//вариант(2)
                $data[] = $tv['elements'];
            }

            return [Html::checkbox('Content[tv_'.$tv['name'].']', $value, ['style'=>'margin-left:20px','label'=>$label_checkbox]), $this->getLabelHtmlElement($tv), 'label'=>true];
        }
    }

    /*
     * формируем LABEL , для Html-элемента, который формируем
     */
    public function getLabelHtmlElement($tv){
        return Html::label($tv['caption'], $tv['name'], ['class'=>'control-label']);
    }
} 