<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.09.14
 * Time: 20:28
 */

/*
 * конструктор форм полей добавления/редактирования документов:
 * формируем список тв-параметров с их значениями по умолчанию
 * либо уже существующими+соответствующими типами полей
 */
class ContentWizard {

    public $template;// сущность - шаблон(к нему подвязан список тв-параметров)

    public $model;//сущность - контент-документ, хранит в себе список параметров

    public $tv; //список тв-параметров по которым будем формировать HTML код

    public $form;//класс формы, где мы выводим код тв-параметров

    public function __construct($model, $template, $form=''){
        $this->model = $model;
        $this->template = $template;
        $this->form = $form;
    }

    public function run(){

        if(!empty($this->template->tv)){
            //проходимся по списку тв-параметров, котор. подвязаны к шаблону
            foreach($this->template->tv as $tv_param){

                // по каждому тв-параметру находим его тип, значение по умолчанию и т.д.
                $tv = Tv::model()->findOne(array('name'=>$tv_param));

                //формируем HTML-код относительно параметров тв-параметра
                if($this->model->isNewRecord){
                    //$this->tv[] = $this->getHtmlElementByTv($tv, $tv->default_text);
                    if(!empty($_POST['tv'][$tv->name])){
                        $this->tv[] = $this->getHtmlElementByTv($tv, $_POST['tv'][$tv->name]);
                    }else{
                        $this->tv[] = $this->getHtmlElementByTv($tv, $tv->default_text);
                    }
                }else{
                    //значение тв-параметров берём из их значений из БД
                    $doc_value = '';
                    //if(!empty($this->model->tv->{$tv->name})){
                    if(!empty($this->model->tv[$tv->name])){
                        $doc_value = $this->model->tv[$tv->name];
                    }
                    $this->tv[] = $this->getHtmlElementByTv($tv, $doc_value);
                }
            }
        }else{
            //список тв-параметров пустой для выбранного шаблона
            //die('empty tv params tpl');
        }
    }

    /*
     * на основании типа тв-параметра - возвращаем HTML-элемент со значениями
     * возвращаем HTML элемент+ его название(label)
     */
    public function getHtmlElementByTv($tv, $value = ''){
        //текстовый тип тв-параметра
        if($tv->type=='text'){
            return  array(CHtml::textField('tv['.$tv->name.']', $value, array('style'=>'width:70%')), CHtml::label($tv->caption.'('.$tv->description.')', $tv->name));
        }
        //текстовый - textarea тип тв-параметра
        if($tv->type=='textareamini' ||  $tv->type=='textarea' || $tv->type=='richtext'){
            return array(CHtml::textArea('tv['.$tv->name.']', $value, array('style'=>'width:70%')), CHtml::label($tv->caption.'('.$tv->description.')', $tv->name));
        }
        //текстовый тип тв-параметра
        if($tv->type=='number'){
            return  array(CHtml::textField('tv['.$tv->name.']', $value, array('style'=>'width:70%')), CHtml::label($tv->caption.'('.$tv->description.')', $tv->name));
        }
        //картинка- тип тв-параметра
        if($tv->type=='image'){
            //return array(CHtml::activeFileField($this->model, 'tv.'.$tv->name),CHtml::label($tv->caption, $tv->name));
            return  array(CHtml::textField('tv['.$tv->name.']', $value, array('style'=>'width:70%')), CHtml::label($tv->caption.'('.$tv->description.')', $tv->name));
        }
        //выпадающий список- тип тв-параметра
        //формат:Disabled==-1||Base Name==0||Append Extension==1||Folder==2  ( "default_text": "-1" - индекс)
        //или Этиленгликоль с традиционными присадками || Этиленгликоль с карбоксилатными присадками || Пропиленгликоль
        if($tv->type=='dropdown'){
            //пример значений:"elements": "не указано||комплектующие||услуги||теплообменники",
            //"default_text": "не указано"

            $data = array();
            //преобразовываем строку в массив значений
            $explode = explode('||', $tv->elements);
            foreach($explode as $row){
                //2 варианта, когда(Disabled==-1) и когда (не указано||комплектующие||услуги)
                if(preg_match('/==/i', $row)){//вариант(1)
                    $expl_str = explode('==', $row);
                    $data[$expl_str[1]] = $expl_str[0];
                }else{//вариант(2)
                    $data[] = $row;
                }
            }

            return array(CHtml::dropDownList('tv['.$tv->name.']', $data, array('value'=>$value)), CHtml::label($tv->caption.'('.$tv->description.')', $tv->name));
        }
        //чекбокс - тип тв-параметры
        /*"elements": "Да==1",
          "default_text": "Нет==0"
         */
        if($tv->type=='checkbox'){
            return  array(CHtml::textField('tv['.$tv->name.']', $value, array('style'=>'width:70%')), CHtml::label($tv->caption.'('.$tv->description.')', $tv->name));
        }

    }
} 