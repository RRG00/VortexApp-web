<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partida".
 *
 * @property int $id_partida
 * @property int $id_torneio
 * @property int $equipa_a
 * @property int $equipa_b
 * @property int $vitorias_a
 * @property int $vitorias_b
 * @property string $estado
 * @property string|null $data
 *
 * @property Equipa $equipaA
 * @property Equipa $equipaB
 * @property Reclamacao[] $reclamacaos
 * @property Torneio $torneio
 */
class Partida extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTADO_PENDENTE = 'pendente';
    const ESTADO_EM_ANDAMENTO = 'em_andamento';
    const ESTADO_CONCLUIDA = 'concluida';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partida';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data'], 'default', 'value' => null],
            [['vitorias_a', 'vitorias_b'], 'default', 'value' => 0],
            [['id_torneio', 'estado'], 'required'],
            [['id_torneio', 'equipa_a', 'equipa_b', 'vitorias_a', 'vitorias_b'], 'integer'],
            [['estado'], 'string'],
            [['data'], 'safe'],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['id_torneio'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::class, 'targetAttribute' => ['id_torneio' => 'id']],
            [['equipa_a'], 'exist', 'skipOnError' => true, 'targetClass' => Equipa::class, 'targetAttribute' => ['equipa_a' => 'id']],
            [['equipa_b'], 'exist', 'skipOnError' => true, 'targetClass' => Equipa::class, 'targetAttribute' => ['equipa_b' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_partida' => 'Id Partida',
            'id_torneio' => 'Id Torneio',
            'equipa_a' => 'Equipa A',
            'equipa_b' => 'Equipa B',
            'vitorias_a' => 'Vitorias A',
            'vitorias_b' => 'Vitorias B',
            'estado' => 'Estado',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[EquipaA]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipaA()
    {
        return $this->hasOne(Equipa::class, ['id' => 'equipa_a']);
    }

    /**
     * Gets query for [[EquipaB]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipaB()
    {
        return $this->hasOne(Equipa::class, ['id' => 'equipa_b']);
    }

    /**
     * Gets query for [[Reclamacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReclamacaos()
    {
        return $this->hasMany(Reclamacao::class, ['id_partida' => 'id_partida']);
    }

    /**
     * Gets query for [[Torneio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTorneio()
    {
        return $this->hasOne(Tournament::class, ['id' => 'id_torneio']);
    }


    /**
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_PENDENTE => 'pendente',
            self::ESTADO_EM_ANDAMENTO => 'em_andamento',
            self::ESTADO_CONCLUIDA => 'concluida',
        ];
    }

    /**
     * @return string
     */
    public function displayEstado()
    {
        return self::optsEstado()[$this->estado];
    }

    /**
     * @return bool
     */
    public function isEstadoPendente()
    {
        return $this->estado === self::ESTADO_PENDENTE;
    }

    public function setEstadoToPendente()
    {
        $this->estado = self::ESTADO_PENDENTE;
    }

    /**
     * @return bool
     */
    public function isEstadoEmandamento()
    {
        return $this->estado === self::ESTADO_EM_ANDAMENTO;
    }

    public function setEstadoToEmandamento()
    {
        $this->estado = self::ESTADO_EM_ANDAMENTO;
    }

    /**
     * @return bool
     */
    public function isEstadoConcluida()
    {
        return $this->estado === self::ESTADO_CONCLUIDA;
    }

    public function setEstadoToConcluida()
    {
        $this->estado = self::ESTADO_CONCLUIDA;
    }
}
