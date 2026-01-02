<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "convite".
 *
 * @property int $id_notificacao
 * @property int $id_utilizador
 * @property string $convite
 * @property string $data_envio
 */
class Convite extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'convite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_utilizador', 'convite', 'data_envio'], 'required'],
            [['id_utilizador'], 'integer'],
            [['convite'], 'string'],
            [['data_envio'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_notificacao' => 'Id Notificacao',
            'id_utilizador' => 'Id Utilizador',
            'convite' => 'Convite',
            'data_envio' => 'Data Envio',
        ];
    }

}
