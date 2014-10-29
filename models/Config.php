<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "Config".
 *
 * @property \MongoId|string $_id
 * @property mixed $param
 * @property mixed $value
 * @property mixed $label
 * @property mixed $type
 * @property mixed $default
 */
class Config extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Config'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'param',
            'value',
            'label',
            'type',
            'default',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param', 'value', 'label', 'type', 'default'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'param' => 'Param',
            'value' => 'Value',
            'label' => 'Label',
            'type' => 'Type',
            'default' => 'Default',
        ];
    }
}
