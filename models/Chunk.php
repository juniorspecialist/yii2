<?php

namespace app\models;

use Yii;
use yii\mongodb\Query;
use yii\caching\Cache;
use yii\helpers\Html;

/**
 * This is the model class for collection "Chunk".
 *
 * @property \MongoId|string $_id
 * @property mixed $title
 * @property mixed $desc
 * @property mixed $content
 */
class Chunk extends \yii\mongodb\ActiveRecord
{

    const  cache_prefix = 'chunk_';
    //public $linkupdate;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['modx', 'Chunk'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'title',
            'desc',
            'content',
            'linkupdate'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'desc', 'content','linkupdate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'title' => 'Название',
            'desc' => 'Описание',
            'content' => 'Содержимое',
            'linkupdate'=>'Название'
        ];
    }

    static function findChunkByName($nameChunk=''){

        if(empty($nameChunk)){ return 'empty name chunk';}

        $nameChunk = str_replace(['"','{','}'], '', $nameChunk);

        //проверим наличие в кеше искомого значения в кеше
        $cache = Yii::$app->cache->get(Chunk::cache_prefix.$nameChunk);

        if ($cache === false) {
            //если НЕ нашли значение в КЕШЕ, значит делаем поиск в БД и заносим значение в кеш
            $query = new Query;

            $row = $query->select(['content'])->from('Chunk')->where(['title'=>$nameChunk])->one();

            //запишим в КЕШ значение чанка
            Yii::$app->cache->set(Chunk::cache_prefix.$nameChunk, $row['content']);

            return $row['content'];
        }else{
            return $cache;
        }
    }

    /*
     * формируем ссылку из названия чанка для редактирования
     */
    public function getLinkUpdate(){
        return Html::a($this->title, ['update', 'id' => (string)$this->_id]);
    }
}
