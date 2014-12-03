<?php

namespace app\models;

use Yii;
use yii\mongodb\Query;
use yii\caching\Cache;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for collection "Template".
 *
 * @property \MongoId|string $_id
 * @property mixed $id
 * @property mixed $title
 * @property mixed $desc
 * @property mixed $content
 */
class Template extends \yii\mongodb\ActiveRecord
{

    //public $tvlist;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Template'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'templatename',
            'description',
            'editor_type',
            'category',
            'icon',
            'template_type',
            'content',
            'locked',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id','id','templatename','description','editor_type','category','icon','template_type','content','locked',], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => 'ID',
            'templatename' => 'Название шаблона',
            'description' => 'Описание',
            'editor_type' => 'Тип',
            'category'=>'Категория',
            'icon'=>'Иконка',
            'template_type'=>'Тип шаблона',
            'content'=>'Содержимое',
            'locked'=>'Заблокирован',
        ];
    }


    /*
     *связь тв-параметров с шаблоном
     */
    public function getTv(){

        //запрос на получение всего массива данных по шаблону в том числе и списка неизвестных полей
        $query = new Query();

        $query->from('Template');

        $query->where(['_id' => (string)$this->_id]);

        $row = $query->one();

        //получаем список тв-параметров подвязанных к шаблону, список неизвестных аттрибутов
        $list  = [];

        foreach($row as $index=>$attribute){
            if(!in_array($index, $this->attributes())){
                $list[] = $index;
            }
        }

        return Tv::find()->where(['in', 'name', $list])->all();
    }



    /*
     *связь тв-параметров с шаблоном
     */
    public function getTvList(){

        //запрос на получение всего массива данных по шаблону в том числе и списка неизвестных полей
        $query = new Query();

        $query->from('Template');

        $query->where(['id' => (int)$this->id]);

        $row = $query->one();

        //получаем список тв-параметров подвязанных к шаблону, список неизвестных аттрибутов
        $list  = [];

        foreach($row as $index=>$attribute){
            if(!in_array($index, $this->attributes())){
                $list[] = $index;
            }
        }

        //запрос на получение всего массива данных по шаблону в том числе и списка неизвестных полей
        $query_tv = new Query();

        $query_tv->from('Tv');

        $query_tv->where(['in', 'name', $list]);

        $rows_tv = $query_tv->all();

        return $rows_tv;
    }

    /*
     * получаем список шаблонов
     */
    public static function getTplList(){

        $query = new Query();

        $query->select(['id','templatename'])->from('Template');

        $query->orderBy('templatename asc');

        $tpls = $query->all();

        return ArrayHelper::map($tpls, 'id', 'templatename');
    }
}
