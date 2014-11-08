<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\mongodb\Query;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for collection "Content".
 *
 * @property \MongoId|string $_id
 * @property mixed $id
 * @property mixed $root
 * @property mixed $contentType
 * @property mixed $pagetitle
 * @property mixed $description
 * @property mixed $alias
 * @property mixed $published
 * @property mixed $pub_date
 * @property mixed $content
 * @property mixed $isfolder
 * @property mixed $template
 * @property mixed $menuindex
 * @property mixed $searchable
 * @property mixed $cacheable
 * @property mixed $createdby
 * @property mixed $createdon
 * @property mixed $editedby
 * @property mixed $deleted
 * @property mixed $publishedon
 * @property mixed $menutitle
 * @property mixed $hidemenu
 * @property mixed $parent
 * @property mixed $introtext
 */
class Content extends \yii\mongodb\ActiveRecord
{

//    public $pagetitle;
//    public $alias;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Content'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'pagetitle',
                 'slugAttribute' => 'alias',
            ],

            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\mongodb\ActiveRecord::EVENT_BEFORE_INSERT => ['createdby', 'editedby'],
                    \yii\mongodb\ActiveRecord::EVENT_BEFORE_UPDATE => ['editedby'],
                ],
            ],
        ];
    }

    /*
     * связь документа с шаблоном
     */

    public function getTpl()
    {
        // Order has_one Customer via Customer.id -> customer_id
        return $this->hasOne(Template::className(), ['id' => 'template']);
    }

    /*
     *связь с родителем документа
     */
    public function getParentcontent(){
        return $this->hasOne(Content::className(), ['id' =>'parent']);
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'contentType',
            'pagetitle',
            'description',
            'alias',
            'published',
            'pub_date',
            'content',
            'isfolder',
            'template',
            'menuindex',
            'searchable',
            'cacheable',
            'createdby',
            'createdon',
            'editedby',
            'deleted',
            'publishedon',
            'menutitle',
            'hidemenu',
            'parent',
            'introtext',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['username', 'password'], 'required'],
            [['parent', 'pagetitle'], 'required'],
            //['id, parent', 'filter', 'filter' => 'intval'],
            [['pub_date'], 'default', 'value' => time()],
            [['isfolder'], 'default', 'value' => 0],
            [['cacheable','searchable'], 'default', 'value' => 1],
            //['id', 'unique', 'targetClass' => Content::className(), 'message' => 'This id has already been taken.'],
            ['alias', 'unique', 'targetClass' => Content::className(), 'message' => 'Данный псевдоним уже существует.'],
            [['id',  'contentType', 'pagetitle', 'description', 'alias', 'published', 'pub_date', 'content', 'isfolder', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'deleted', 'publishedon', 'menutitle', 'hidemenu', 'parent', 'introtext'], 'safe']
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
            'contentType' => 'Тип содержимого',
            'pagetitle' => 'Заголовок',
            'description' => 'Описание',
            'alias' => 'Псевдоним',
            'published' => 'Опубликовать',
            'pub_date' => 'Дата публикации',
            'content' => 'Содержимое ресурса',
            'isfolder' => 'Папка',
            'template' => 'Шаблон',
            'menuindex' => 'Позиция в меню',
            'searchable' => 'Доступен для поиска',
            'cacheable' => 'Кешируемый',
            'createdby' => 'Создан',
            'createdon' => 'Дата создания',
            'editedby' => 'Дата редактирования',
            'deleted' => 'Удалён',
            'publishedon' => 'Опубликован',
            'menutitle' => 'Пункт меню',
            'hidemenu' => 'Показывать в меню',
            'parent' => 'Родитель',
            'introtext' => 'Introtext',
        ];
    }

    public function getTvParam($name_tv_param){

        $list = $this->toArray();

        echo '<pre>'; print_r($list);

        if(!empty($list[$name_tv_param])){
            return $list[$name_tv_param];
        }else{
            return '';
        }
    }

    public function getPrimaryKey($asArray = false){
        return $this->id;
    }

    /*
     * получаем список элементов дерева по PARENT
     */
    public static function getNode($parent_id=0){
        $query = new Query();
        $query->select(['id','pagetitle','alias', 'isfolder']);
        $query->from(Content::collectionName());
        $query->where(['parent'=>(int)$parent_id]);
        $nodes = $query->all();

        $tree = [];

        if($nodes){
           foreach($nodes as $node){
               $tree[] =[
                   'text'=>Html::a($node['pagetitle'], Url::toRoute(['/manager/content/update/', 'id'=>(string)$node['_id']]), ['title'=>$node['pagetitle'],'target'=>'main', 'class'=>'node']),
                   'id'=>$node['id'],
                   'hasChildren'=>$node['isfolder']==0 ? false : true,
               ];
           }
        }

        return json_encode($tree);
    }



    public function afterFind()
    {
        //работа с датами в документе
        if($this->pub_date==0 || empty($this->pub_date)){
            $this->pub_date = date('Y-m-d', time());
        }

//        $query = new Query();
//        $query->select([$this->idFieldName, $this->keyFieldName, $this->valueFieldName]);
//        $query->from($this->tableName);
//        $query->where([$this->idFieldName => $this->owner->id]);
//
//        foreach ($query->all() as $property) {
//            $this->_properties[$property[$this->keyFieldName]] = Json::decode($property[$this->valueFieldName]);
//        }
    }

}
