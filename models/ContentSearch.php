<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Content;

/**
 * ContentSearch represents the model behind the search form about `app\models\Content`.
 */
class ContentSearch extends Content
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'id', 'contentType', 'pagetitle', 'description', 'alias', 'published', 'pub_date', 'content', 'isfolder', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'deleted', 'publishedon', 'menutitle', 'hidemenu', 'parent', 'introtext'], 'safe'],
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
        $query = Content::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->id!=0){
            $query->andFilterWhere(['in','id', (int)$this->id]);
        }

        $query->andFilterWhere(['like', 'contentType', $this->contentType])
            ->andFilterWhere(['like', 'pagetitle', $this->pagetitle])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'alias', (string)$this->alias])
            ->andFilterWhere(['like', 'published', $this->published])
            ->andFilterWhere(['like', 'pub_date', $this->pub_date])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'isfolder', $this->isfolder])
            ->andFilterWhere(['like', 'template', $this->template])
            ->andFilterWhere(['like', 'menuindex', $this->menuindex])
            ->andFilterWhere(['like', 'searchable', $this->searchable])
            ->andFilterWhere(['like', 'cacheable', $this->cacheable])
            ->andFilterWhere(['like', 'createdby', $this->createdby])
            ->andFilterWhere(['like', 'createdon', $this->createdon])
            ->andFilterWhere(['like', 'editedby', $this->editedby])
            ->andFilterWhere(['like', 'deleted', $this->deleted])
            ->andFilterWhere(['like', 'publishedon', $this->publishedon])
            ->andFilterWhere(['like', 'menutitle', $this->menutitle])
            ->andFilterWhere(['like', 'hidemenu', $this->hidemenu])
            ->andFilterWhere(['like', 'parent', $this->parent])
            ->andFilterWhere(['like', 'introtext', $this->introtext]);


        return $dataProvider;
    }
}
