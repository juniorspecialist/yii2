<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\mongodb\Query;

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
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Content'];
    }

    public function getTpl()
    {
        // Order has_one Customer via Customer.id -> customer_id
        return $this->hasOne(Template::className(), ['id' => 'template']);
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
            ['id', 'required'],
            ['id, parent', 'filter', 'filter' => 'intval'],
            ['id', 'unique', 'targetClass' => Content::className(), 'message' => 'This id has already been taken.'],
            ['alias', 'unique', 'targetClass' => Content::className(), 'message' => 'This alias has already been taken.'],
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
            'id' => 'Id',
            'contentType' => 'Content Type',
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
            'createdon' => 'Createdon',
            'editedby' => 'Editedby',
            'deleted' => 'Удалён',
            'publishedon' => 'Publishedon',
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
                   'text'=>Html::a($node['pagetitle'], Url::toRoute(['/manager/content/update/', 'id'=>(string)$node['_id']]), ['title'=>$node['pagetitle'],'target'=>'main']),
                   'id'=>$node['id'],
                   'hasChildren'=>$node['isfolder']==0 ? false : true,
               ];
           }
        }

        return json_encode($tree);
    }

}
