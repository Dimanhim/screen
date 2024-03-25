<?php

namespace backend\models;

use common\components\AccessesComponent;
use common\models\UserAccess;
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
        $query = Cabinet::find()->where(['is', 'cabinet.deleted', null])->andWhere(['cabinet.is_active' => 1]);
        $query->joinWith(['accesses']);
        $query->andWhere([
            'user_accesses.access_type' => AccessesComponent::TYPE_CABINET,
            'user_accesses.user_id' => \Yii::$app->user->id,
        ]);

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
            'cabinet.id' => $this->id,
            'cabinet.clinic_id' => $this->clinic_id,
            'cabinet.mis_id' => $this->mis_id,
            'cabinet.is_active' => $this->is_active,
            'cabinet.deleted' => $this->deleted,
            'cabinet.position' => $this->position,
            'cabinet.created_at' => $this->created_at,
            'cabinet.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'cabinet.unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'cabinet.number', $this->number])
            ->andFilterWhere(['like', 'cabinet.name', $this->name]);

        return $dataProvider;
    }
}
