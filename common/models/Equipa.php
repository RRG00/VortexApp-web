<?php

namespace common\models;

use Yii;
use common\models\MembrosEquipa;


/**
 * This is the model class for table "equipa".
 *
 * @property int $id_equipa
 * @property string $nome
 * @property string $data_criacao
 *
 * @property Inscricao[] $inscricaos
 * @property MembrosEquipa[] $membrosEquipas
 * @property Partida[] $partidas
 * @property Partida[] $partidas0
 */
class Equipa extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'data_criacao'], 'required'],
            [['data_criacao'], 'safe'],
            [['nome'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_equipa' => 'Id Equipa',
            'nome' => 'Nome',
            'data_criacao' => 'Data Criacao',
        ];
    }

    /**
     * Gets query for [[Inscricaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInscricaos()
    {
        return $this->hasMany(Inscricao::class, ['id_equipa' => 'id_equipa']);
    }

    /**
     * Gets query for [[MembrosEquipas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMembrosEquipas()
    {
        return $this->hasMany(MembrosEquipa::class, ['id_equipa' => 'id_equipa']);
    }

    /**
     * Gets query for [[Partidas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartidas()
    {
        return $this->hasMany(Partida::class, ['equipa_a' => 'id_equipa']);
    }

    /**
     * Gets query for [[Partidas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartidas0()
    {
        return $this->hasMany(Partida::class, ['equipa_b' => 'id_equipa']);
    }

    public function getCapitao()
    {
        return $this->hasOne(MembrosEquipa::class, ['id_equipa' => 'id_equipa'])
            ->andWhere(['funcao' => 'capitao']);
    }

    public function getUtilizadors()
    {
        return $this->hasMany(MembrosEquipa::class, ['id_equipa' => 'id_equipa'])
            ->inverseOf('equipa');
    }

}
