<?php

namespace app\models;

use Yii;
use yii\mongodb\Query;
use yii\caching\Cache;

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
}
