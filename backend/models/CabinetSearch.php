<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cabinet;

/**
 * CabinetSearch represents the model behind the search form of `common\models\Cabinet`.
 */
class CabinetSearch extends Cabinet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mis_id', 'is_active', 'deleted', 'position', 'created_at', 'updated_at'], 'integer'],
            [['unique_id', 'number', 'name', 'clinic_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Cabinet::findModels();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'clinic_id' => $this->clinic_id,
            'mis_id' => $this->mis_id,
            'is_active' => $this->is_active,
            'deleted' => $this->deleted,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
