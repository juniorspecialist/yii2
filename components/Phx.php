<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.08.14
 * Time: 20:12
 */
namespace app\components;

use yii\web\UrlRule;
use app\models\Content;
use yii\mongodb\Query;
use yii\helpers\Url;

class Phx extends \app\components\Ditto {

    public $if;
    public $is;
    public $else;
    public $then;
    public $html;

    /*
     * парсим строку вызова сниппета Phx
     */
    public function parseString(){

        //echo '======================='.$this->callString.'============================<br>';

        //TODO
        if(preg_match('/phx(.*?)/i',$this->callString)){
            //распарсим параметры конструкции-условия
            //echo 'callString='.$this->callString.'<br>';
            $this->rule_if_else();
        }elseif(preg_match('/^\[\*(.*?):(.*?)`\*\]/mis', $this->callString)){
            //случай вызова условия - [*price-place:is=``:then=``:else=`[*price-place*]`*]
            //парсим строку и анализируем параметры
            $this->rule_is_some_param();
        }
    }

    /*
     * определили вызов конструкции ([*price-place:is=``:then=``:else=`[*price-place*]`*])-обработка её параметров
     * т.е. вместо "price-place" может любой тв-параметр
     */
    public function rule_is_some_param(){

        //сперва определим что за параметр идёт у нас первым в условии, а потом парсим условия(if_else)

        preg_match('/^\[\*(.*?)\:is/', $this->callString, $matches_come_param);
        //$matches_come_param[1] - некий тв-параметр, который используется в условии выражения
        $some_param = str_replace(['[',']','`', ' '],'',$matches_come_param[1]);

        //echo '<pre>'; print_r($matches_come_param); die($this->callString);

        //другой случай, когда вызов сниппета-[*isfolder:is=`1`:then=`wara<br>`:is=``:then=`CO`:else=`COM 350/ 03`+]
        $this->parseIfElseParams($this->callString);

        //парсим параметр THEN

        //обработка значения THEN
        if(preg_match('/then=`(.*?)`(:is|\+\]|\*\])/mis', $this->callString, $then_list)){
            //if_list[1] - значение параметра для условия
            //определим какой тип значения указан
            //print_r($then_list);die();
            $this->then= $this->parseValue($then_list[1]);
        }

        if(isset($this->model[$some_param])){
            if((string)$this->model[$some_param]==$this->is){
                $this->result = $this->then;
            }else{
                //проверим существования условия на ELSE
                $this->result = $this->else;
            }
        }else{
            //проверим существования условия на ELSE
            $this->result = $this->else;
        }
    }

    /*
     * определяем какое значение параметра объявлено и преобразовываем его в значение конечное
     * значение может быть как-сниппет,чанк, тв-параметр, определяем и преобразовываем в конечное значение
     */
    public function parseValue($value){

        $new_value = '';

//        //параметр - вызов сниппет
//        if( preg_match('~\[(\[|\!)(.*?)(\!|\])\]~ms', $value,$find)){
//            //echo 'snippet<br>';echo '<pre>'; print_r($find);
//            //заменяем вызов чанка его содержимым
//            $new_value = str_replace($value, Parser::mergeSnippet($value, $this->model), $value);
//        }
//
//
//        //параметр - вызов чанк
//        if(preg_match('/{{(.*?)}}/i',$value,$find)){
//            //echo 'chunk<br>';
//            $new_value = str_replace($value, Parser::mergeChunkContent($value), $value);
//        }
//
//        //параметр - тв-параметр документа
//        if (preg_match('~\[(\*|\+)(.*?)(\*|\+)\]~', $value, $matches)) {
//            //echo '<pre>'; print_r($matches); //die();
//            $new_value = Parser::mergeTvParamsContent($value, $this->model);
//        }

        return $value;
    }


    /*
     * парсим параметры условия
     */
    public function parseIfElseParams($param){

        //[+phx:if=`[*vendorcode*]`:is=`DeLonghi`:then=`[!Wayfinder? &startId=`61463` &hideSubMenus=`true` &outerClass=`nav  nav-list` &sortBy=`menutitle`   &innerRowTpl=`rowTpl` &innerClass=`nav  nav-list nav-list2`!]`:else=``+]
        //обработка значения IF
        if(preg_match('/if=`(.*?)`(:is|\+\]|\*\])/i', $param, $if_list)){
            //if_list[1] - значение параметра для условия
            $this->if = $this->parseValue($if_list[1]);
        }

        //обработка значения IS
        if(preg_match('/is=`(.*?)`(:then|\+\]|\*\])/i', $param, $is_list)){
            //if_list[1] - значение параметра для условия
            $this->is = $this->parseValue($is_list[1]);
        }

        //обработка значения THEN
        if(preg_match('/then=`(.*?)`(:else|\+\]|\*\])/mis', $param, $then_list)){
            //print_r($then_list);
            //if_list[1] - значение параметра для условия
            //определим какой тип значения указан
            $this->then= $this->parseValue($then_list[1]);
        }
        //обработка значения ELSE
        if(preg_match('/else=(.*)/s', $param)){
            if(preg_match('/else=`(.+)`(\+\]|\*\])/simx', $param, $else_list)){
                //if_list[1] - значение параметра для условия
                $this->else = $this->parseValue($else_list[1]);
            }

        }
        //echo 'if='.$this->if.'|is='.$this->is.'|then='.$this->then.'|else='.$this->else.'<br>';
    }

    /*
     * нашли вызов "if_else" конструкции, поэтому парсим параметры этой конструкции
     */
    public function rule_if_else(){

        //строка вызова пример - [+phx:if=`Hyundai`:is=``:then=``:else=``+] [+phx:if=`сплит-система`:is=``:then=``:else=``+]
        $param = trim($this->callString);

        //парсим параметры условия
        $this->parseIfElseParams($param);

        //обработка полученных параметров и вывод результата
        if($this->if==$this->is){
            $this->result = $this->then;
        }else{
            $this->result = $this->else;
        }
    }
    /*
       * список перменных получили, обработка входящих параметров и вывод реультата
       */
    public function action(){

        // парсим строку вызова сниппета и назначаем параметры для обработки
        $this->parseString();
    }

}