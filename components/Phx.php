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
        }

        if(preg_match('/\[\*isfolder/i',$this->callString)){
            $this->rule_is_folder();
        }
    }

    /*
     * определили вызов конструкции is_folder-обработка её параметров
     */
    public function rule_is_folder(){

        //echo $this->callString.'<br>';


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

        //echo '<pre>'; print_r($this->model); die();

            if((string)$this->model['isfolder']==$this->is){
                //die($this->then);
                $this->result = $this->then;
            }else{
                //проверим существования условия на ELSE
                $this->result = $this->else;
            }


        //die($this->is.'=is|'.$this->callString.'|'.$this->result);
    }

    /*
     * определяем какое значение параметра объявлено и преобразовываем его в значение конечное
     * значение может быть как-сниппет,чанк, тв-параметр, определяем и преобразовываем в конечное значение
     */
    public function parseValue($value){
        //параметр - вызов сниппет
        if( preg_match('~\[(\[|\!)(.*?)(\!|\])\]~ms', $value,$find)){
            //echo 'snippet<br>';echo '<pre>'; print_r($find);
            //заменяем вызов чанка его содержимым
            $value = str_replace($value, Parser::mergeSnippet($value, $this->model), $value);
        }


        //параметр - вызов чанк
        if(preg_match('/{{(.*?)}}/i',$value,$find)){
            //echo 'chunk<br>';
            $value = str_replace($value, Parser::mergeChunkContent($value), $value);
        }

        //параметр - тв-параметр документа
        if (preg_match('~\[(\*|\+)(.*?)(\*|\+)\]~', $value, $matches)) {
            //echo '<pre>'; print_r($matches); //die();
            $value = Parser::mergeTvParamsContent($value, $this->model);
        }

        return $value;
    }


    /*
     * парсим параметры условия
     */
    public function parseIfElseParams($param){

        //обработка значения IF
        if(preg_match('/if=`(.*?)`/i', $param, $if_list)){
            //if_list[1] - значение параметра для условия
            $this->if = $this->parseValue($if_list[1]);
        }

        //обработка значения IS
        if(preg_match('/is=`(.*?)`/i', $param, $is_list)){
            //if_list[1] - значение параметра для условия
            $this->is = $this->parseValue($is_list[1]);
        }

        //обработка значения THEN
        if(preg_match('/then=`(.*?)`/mis', $param, $then_list)){
            //if_list[1] - значение параметра для условия
            //определим какой тип значения указан
            $this->then= $this->parseValue($then_list[1]);
        }
        //обработка значения ELSE
        if(preg_match('/else=(.*)/s', $param)){
            if(preg_match('/else=`(.+)`/simx', $param, $else_list)){
                //if_list[1] - значение параметра для условия
                $this->else = $this->parseValue($else_list[1]);
            }

        }
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