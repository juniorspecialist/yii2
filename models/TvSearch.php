<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tv;

/**
 * TvSearch represents the model behind the search form about `app\models\Tv`.
 */
class TvSearch extends Tv
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'type', 'name', 'caption', 'description', 'elements', 'default_text'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Tv::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'caption', $this->caption])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'elements', $this->elements])
            ->andFilterWhere(['like', 'default_text', $this->default_text]);

        return $dataProvider;
    }
}
