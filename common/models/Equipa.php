<?php

namespace common\models;

use common\models\MembrosEquipa;
use Yii;

/**
 * This is the model class for table "equipa".
 *
 * @property int $id
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

    public $imageFile;

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

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
            [['nome'], 'match', 'pattern' => '/^[A-Za-z0-9]+$/', 'message' => 'O nome da equipa só pode conter letras (A-Z) e números (0-9).'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
        return $this->hasMany(Inscricao::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[MembrosEquipas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMembrosEquipas()
    {
        return $this->hasMany(MembrosEquipa::class, ['id_equipa' => 'id']);
    }

    /**
     * Gets query for [[Partidas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartidas()
    {
        return $this->hasMany(Partida::class, ['equipa_a' => 'id']);
    }

    /**
     * Gets query for [[Partidas0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartidas0()
    {
        return $this->hasMany(Partida::class, ['equipa_b' => 'id']);
    }

    public function getCapitao()
    {
        return $this->hasOne(MembrosEquipa::class, ['id_equipa' => 'id'])
            ->andWhere(['funcao' => 'capitao']);

       /* return $this->hasOne(MembrosEquipa::class, ['id_utilizador' => 'id_capitao'])
            ->andWhere(['funcao' => 'capitao'])->andWhere(['id_equipa' => 'id']);*/
    }
/*
    public function getCapitao()
    {
        return $this->hasOne(User::class, ['id' => 'id_capitao']);
    }*/

    public function getUtilizadors()
    {
        return $this->hasMany(MembrosEquipa::class, ['id_utilizador' => 'id'])
            ->inverseOf('equipa');
    }

    public function getProfileImage()
    {
        return $this->hasOne(Images::class, ['id' => 'id']);
    }
    
    public function getCapitaoUsername()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'id_capitao']);
    }
    


    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $images = new Images();
            $images->path = 'uploads/' . $this->imageFile->baseName;
            $images->extension = $this->imageFile->extension;
            $images->id = $this->id;
            $images->save();

            return true;
        } else {
            return false;
        }

    }

}
