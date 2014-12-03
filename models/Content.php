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
    //public $parent;
    //public $id;
    //public $template;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Content'];
    }

    //TODO доделать заполнение параметров документа по-умолчанию. при создании нового дока
    public function init()
    {
        parent::init();
        //$this->status = 'active';
    }

    public function behaviors()
    {
        return [
//            [
//                'class' => SluggableBehavior::className(),
//                'attribute' => 'pagetitle',
//                 'slugAttribute' => 'alias',
//            ],

//            [
//                'class' => TimestampBehavior::className(),
//                'attributes' => [
//                    \yii\mongodb\ActiveRecord::EVENT_BEFORE_INSERT => ['createdby', 'editedby'],
//                    \yii\mongodb\ActiveRecord::EVENT_BEFORE_UPDATE => ['editedby'],
//                ],
//            ],
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

            ['template', 'filter', 'filter' => function ($value) {
                // normalize template input here
                return settype($value, 'integer');
            }],

            [['id', 'parent', 'hidemenu','deleted', 'template','cacheable','searchable','menuindex','isfolder','published'], 'filter', 'filter' => 'intval'],
            [['pub_date'], 'default', 'value' => time()],
            // normalize "template" input

            [['isfolder'], 'default', 'value' => 0],
            [['cacheable','searchable'], 'default', 'value' => 1],
            //['id', 'unique', 'targetClass' => Content::className(), 'message' => 'This id has already been taken.'],
            [['alias','id'], 'unique', 'targetClass' => Content::className(), 'message' => 'Данный псевдоним уже существует.'],
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



//    public function getPrimaryKey($asArray = false){
//        return $this->id;
//    }

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


    /*
     * поиск документа по условию+возвращаем не объект а МАССИВ
     * $where - массив условий для выборки(поиска)
     * $how - сколько записей ожидаем получить, 1  - одна запись, если не равно-1, значит много
     */
    public function findContentArray(array $where, $how = 1){

        $query = new Query();

        $query->from(Content::collectionName());

        $query->where($where);

        if($how==1){
            return $query->one();
        }else{
            return $query->all();
        }
    }

    public function afterFind()
    {
        //работа с датами в документе
        if($this->pub_date!=0 && empty($this->pub_date)){
            $this->pub_date = date('Y-m-d', $this->pub_date);
        }else{
            $this->pub_date = '';
        }

        if($this->publishedon!=0 && empty($this->publishedon)){
            $this->publishedon = date('Y-m-d', $this->publishedon);
        }else{
            $this->publishedon = '';
        }
    }
}
