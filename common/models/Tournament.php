<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TORNEIO".
 *
 * @property int $id
 * @property string $nome
 * @property int $best_of
 * @property string|null $regras
 * @property int $limite_inscricoes
 * @property string|null $premios
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $estado
 * @property int $organizador_id
 * @property int|null $aprovado_por
 * @property int $id_jogo
 *
 * @property User $aprovadoPor
 * @property Inscricao[] $iNSCRICAOs
 * @property Jogo $jogo
 * @property User $organizador
 * @property Partida[] $pARTIDAs
 * @property Patrocinador[] $pATROCINADORs
 */
class Tournament extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'torneio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['regras', 'premios', 'aprovado_por'], 'default', 'value' => null],
            [['nome', 'best_of', 'limite_inscricoes', 'data_inicio', 'data_fim', 'estado', 'organizador_id', 'id_jogo'], 'required'],
            [['best_of', 'limite_inscricoes', 'organizador_id', 'aprovado_por', 'id_jogo'], 'integer'],
            [['regras'], 'string'],
            [['descricao'], 'string', 'max' => 500],
            [['data_inicio', 'data_fim'], 'safe'],
            [['nome', 'premios', 'requisitos'], 'string', 'max' => 255],
            [['estado'], 'string', 'max' => 50],
            [['organizador_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['organizador_id' => 'id']],
            [['aprovado_por'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['aprovado_por' => 'id']],
            [['id_jogo'], 'exist', 'skipOnError' => true, 'targetClass' => JOGO::class, 'targetAttribute' => ['id_jogo' => 'id_jogo']],
            [['arbitro_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['arbitro_id' => 'id']], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'nome' => 'Nome',
            'best_of' => 'Best Of',
            'regras' => 'Regras',
            'limite_inscricoes' => 'Limite Inscricoes',
            'premios' => 'Premios',
            'data_inicio' => 'Data Inicio',
            'data_fim' => 'Data Fim',
            'estado' => 'Estado',
            'organizador_id' => 'Organizador ID',
            'aprovado_por' => 'Aprovado Por',
            'id_jogo' => 'Id Jogo',
            'arbitro_id' => 'Árbitro',
            'requisitos' => 'Requesitos',
        ];
    }

    /**
     * Gets query for [[AprovadoPor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAprovadoPor()
    {
        return $this->hasOne(User::class, ['id' => 'aprovado_por']);
    }

    /**
     * Gets query for [[Arbitro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArbitro()
    {
        return $this->hasOne(User::class, ['id' => 'arbitro_id']);
    }

  

    /**
     * Gets query for [[INSCRICAOs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getINSCRICAOs()
    {
        return $this->hasMany(Inscricao::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[Jogo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id_jogo' => 'id_jogo']);
    }

    /**
     * Gets query for [[Organizador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizador()
    {
        return $this->hasOne(User::class, ['id' => 'organizador_id']);
    }

    /**
     * Gets query for [[PARTIDAs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPARTIDAs()
    {
        return $this->hasMany(Partida::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[PATROCINADORs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPATROCINADORs()
    {
        return $this->hasMany(Patrocinador::class, ['id' => 'id']);
    }

}
