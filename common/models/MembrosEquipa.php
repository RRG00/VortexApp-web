<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "membros_equipa".
 *
 * @property int $id
 * @property int $id_equipa
 * @property string $funcao
 * @property int $id_utilizador
 *
 * @property Equipa $equipa
 * @property User $user
 */
class MembrosEquipa extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'membros_equipa';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_equipa', 'funcao', 'id_utilizador'], 'required'],
            [['id_equipa', 'id_utilizador'], 'integer'],
            [['id_utilizador'], 'unique', 'message' => 'Este utilizador já pertence a uma equipa.'],
            [['funcao'], 'string', 'max' => 100],
            [['id_utilizador'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_utilizador' => 'id']],
            [['id_equipa'], 'exist', 'skipOnError' => true, 'targetClass' => Equipa::class, 'targetAttribute' => ['id_equipa' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_equipa' => 'Id Equipa',
            'funcao' => 'Funcao',
            'id_utilizador' => 'Id User',
        ];
    }

    /**
     * Gets query for [[Equipa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipa()
    {
        return $this->hasOne(Equipa::class, ['id' => 'id_equipa']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_utilizador']);
    }

    public function getCapitao()
    {
        return self::find()
            ->where([
                'id_equipa' => $this->id_equipa,
                'funcao'    => 'capitao',
            ])
            ->one();
        //return $this->
    }

    public function create($equipaId, $userId){
        $this->id_utilizador = $userId;
        $this->funcao = 'capitao';
        $this->id_equipa = $equipaId;
    }

}
