<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "equipa".
 *
 * @property int $id_equipa
 * @property string $nome
 * @property int|null $id_capitao
 * @property string $data_criacao
 * @property int|null $id_user
 *
 * @property User $capitao
 * @property InscricaoTorneio[] $inscricaoTorneios
 * @property Inscricao[] $inscricaos
 * @property MembrosEquipa[] $membrosEquipas
 * @property Partida[] $partidas
 * @property Partida[] $partidas0
 * @property User $user
 * @property User[] $utilizadors
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
            [['id_capitao', 'id_user'], 'default', 'value' => null],
            [['nome', 'data_criacao'], 'required'],
            [['id_capitao', 'id_user'], 'integer'],
            [['data_criacao'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['id_capitao'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_capitao' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
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
            'id_capitao' => 'Id Capitao',
            'data_criacao' => 'Data Criacao',
            'id_user' => 'Id User',
        ];
    }

    /**
     * Gets query for [[Capitao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCapitao()
    {
        return $this->hasOne(User::class, ['id' => 'id_capitao']);
    }

    /**
     * Gets query for [[InscricaoTorneios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInscricaoTorneios()
    {
        return $this->hasMany(InscricaoTorneio::class, ['id_equipa' => 'id_equipa']);
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * Gets query for [[Utilizadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUtilizadors()
    {
        return $this->hasMany(User::class, ['id' => 'id_user'])->viaTable('membros_equipa', ['id_equipa' => 'id_equipa']);
    }

}
