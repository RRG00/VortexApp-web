<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "JOGO".
 *
 * @property int $id_jogo
 * @property string $nome
 * @property string $genero
 * @property string|null $imagem
 *
 * @property Estatiscas[] $eSTATISTICASs
 * @property Noticia[] $nOTICIAs
 * @property Torneio[] $tORNEIOs
 */
class Jogo extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jogo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagem'], 'default', 'value' => null],
            [['nome', 'genero'], 'required'],
            [['nome', 'imagem'], 'string', 'max' => 255],
            [['genero'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jogo' => 'Id Jogo',
            'nome' => 'Nome',
            'genero' => 'Genero',
            'imagem' => 'Imagem',
        ];
    }

    /**
     * Gets query for [[ESTATISTICASs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getESTATISTICASs()
    {
        return $this->hasMany(ESTATISTICAS::class, ['id_jogo' => 'id_jogo']);
    }

}
