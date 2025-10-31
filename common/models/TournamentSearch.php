<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tournament;

/**
 * TournamentSearch represents the model behind the search form of `common\models\Tournament`.
 */
class TournamentSearch extends Tournament
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_torneio', 'best_of', 'limite_inscricoes', 'organizador_id', 'aprovado_por', 'id_jogo'], 'integer'],
            [['nome', 'regras', 'premios', 'data_inicio', 'data_fim', 'estado'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Tournament::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_torneio' => $this->id_torneio,
            'best_of' => $this->best_of,
            'limite_inscricoes' => $this->limite_inscricoes,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'organizador_id' => $this->organizador_id,
            'aprovado_por' => $this->aprovado_por,
            'id_jogo' => $this->id_jogo,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'regras', $this->regras])
            ->andFilterWhere(['like', 'premios', $this->premios])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
