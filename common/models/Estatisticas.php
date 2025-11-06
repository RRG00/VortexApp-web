<?php
namespace common\models;
use Yii;

/**
 * This is the model class for table "estatisticas".
 *
 * @property int $id_estatistica
 * @property int $id_utilizador
 * @property int $id_jogo
 * @property int $vitorias
 * @property int $derrotas
 * @property string $pontuacao
 * @property string $kd
 *
 * @property User $utilizador
 * @property Jogo $jogo
 */
class Estatisticas extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'estatisticas';
    }

    public function rules()
    {
        return [
            [['id_utilizador', 'id_jogo'], 'required'],
            [['id_utilizador', 'id_jogo', 'vitorias', 'derrotas'], 'integer'],
            [['pontuacao', 'kd'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_estatistica' => 'ID',
            'id_utilizador' => 'Utilizador',
            'id_jogo' => 'Jogo',
            'vitorias' => 'Vitórias',
            'derrotas' => 'Derrotas',
            'pontuacao' => 'Pontuação',
            'kd' => 'K/D',
        ];
    }

    /**
     * Gets query for [[User]].
     */
    public function getUtilizador()
    {
        return $this->hasOne(User::class, ['id' => 'id_utilizador']); // Changed to User and check the primary key
    }

    /**
     * Gets query for [[Jogo]].
     */
    public function getJogo()
    {
        return $this->hasOne(Jogo::class, ['id_jogo' => 'id_jogo']);
    }
}