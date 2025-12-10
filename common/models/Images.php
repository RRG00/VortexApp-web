<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_jogo
 * @property int|null $id_torneio
 * @property string $path
 * @property string $extension
 * @property int|null $id_equipa
 */
class Images extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_jogo', 'id_torneio', 'id_equipa'], 'default', 'value' => null],
            [['id_user', 'id_jogo', 'id_torneio', 'id_equipa'], 'integer'],
            [['path', 'extension'], 'required'],
            [['path'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'id_jogo' => 'Id Jogo',
            'id_torneio' => 'Id Torneio',
            'path' => 'Path',
            'extension' => 'Extension',
            'id_equipa' => 'Id Equipa',
        ];
    }

}
