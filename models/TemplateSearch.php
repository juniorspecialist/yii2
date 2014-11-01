<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\mongodb\Query;
use yii\data\ActiveDataProvider;
use app\models\Template;

/**
 * TemplateSearch represents the model behind the search form about `app\models\Template`.
 */
class TemplateSearch extends Template
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['_id', 'id', 'title', 'desc', 'content'], 'safe'],
            [['_id','id','templatename','description','editor_type','category','icon','template_type','content','locked'], 'safe']
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
        $query = Template::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->id!=0){
            $query->andFilterWhere(['in','id', (int)$this->id]);
        }

        $query->andFilterWhere(['like', 'templatename', $this->templatename]);

        return $dataProvider;
    }
}
