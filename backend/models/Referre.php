<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "referre".
 *
 * @property int $id
 * @property int $id_partida
 */
class Referre extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'referre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_partida'], 'required'],
            [['id_partida'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_partida' => 'Id Partida',
        ];
    }

}
