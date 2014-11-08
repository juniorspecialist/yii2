<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "Tv".
 *
 * @property \MongoId|string $_id
 * @property mixed $type
 * @property mixed $name
 * @property mixed $caption
 * @property mixed $description
 * @property mixed $elements
 * @property mixed $default_text
 */
class Tv extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Tv'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'type',
            'name',
            'caption',
            'description',
            'elements',
            'default_text',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'caption', 'description', 'elements', 'default_text'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'type' => 'Тип',
            'name' => 'Имя',
            'caption' => 'Заголовок',
            'description' => 'Описание',
            'elements' => 'Элементы',
            'default_text' => 'Значение по умолчанию',
        ];
    }

    static function getTypesTvParams(){
        return [

        ];
    }
}
