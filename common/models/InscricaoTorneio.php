<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "inscricao_torneio".
 *
 * @property int $id
 * @property int $id_equipa
 * @property int $id_torneio
 * @property string|null $data_inscricao
 * @property int|null $aprovacao
 * @property int|null $posicao_final
 * @property int|null $pontos
 *
 * @property Equipa $equipa
 * @property Torneio $torneio
 */
class InscricaoTorneio extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inscricao_torneio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aprovacao', 'posicao_final'], 'default', 'value' => null],
            [['pontos'], 'default', 'value' => 0],
            [['id_equipa', 'id_torneio'], 'required'],
            [['id_equipa', 'id_torneio', 'aprovacao', 'posicao_final', 'pontos'], 'integer'],
            [['data_inscricao'], 'safe'],
            [['id_equipa'], 'exist', 'skipOnError' => true, 'targetClass' => Equipa::class, 'targetAttribute' => ['id_equipa' => 'id_equipa']],
            [['id_torneio'], 'exist', 'skipOnError' => true, 'targetClass' => Tournament::class, 'targetAttribute' => ['id_torneio' => 'id_torneio']],
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
            'id_torneio' => 'Id Torneio',
            'data_inscricao' => 'Data Inscricao',
            'aprovacao' => 'Aprovacao',
            'posicao_final' => 'Posicao Final',
            'pontos' => 'Pontos',
        ];
    }

    /**
     * Gets query for [[Equipa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipa()
    {
        return $this->hasOne(Equipa::class, ['id_equipa' => 'id_equipa']);
    }

    /**
     * Gets query for [[Torneio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTorneio()
    {
        return $this->hasOne(Torneio::class, ['id_torneio' => 'id_torneio']);
    }

}
