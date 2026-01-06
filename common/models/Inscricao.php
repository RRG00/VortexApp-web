<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inscricao".
 *
 * @property int $id
 * @property int $id_torneio
 * @property int $id_equipa
 * @property Equipa $equipa
 * @property Torneio $torneio
 */
class Inscricao extends \yii\db\ActiveRecord
{
    /**
     * @var int|mixed|string|null
     */
    public $id_utilizador;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inscricao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_torneio', 'id_equipa'], 'required'],
            [['id_torneio', 'id_equipa'], 'integer'],
            [['id_torneio'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::class, 'targetAttribute' => ['id_torneio' => 'id']],
            [['id_equipa'], 'exist', 'skipOnError' => true, 'targetClass' => Equipa::class, 'targetAttribute' => ['id_equipa' => 'id']],
            [['id_torneio', 'id_equipa'], 'unique',
            'targetAttribute' => ['id_torneio', 'id_equipa'],
            'message' => 'Esta equipa já está inscrita neste torneio.',
        ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_torneio' => 'Id Torneio',
            'id_equipa' => 'Id Equipa',
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
     * Gets query for [[Torneio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTorneio()
    {
        return $this->hasOne(Tournament::class, ['id' => 'id_torneio']);
    }

}
