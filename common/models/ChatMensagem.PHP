<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;

class ChatMensagem extends ActiveRecord
{
    public static function tableName()
    {
        return 'chat_mensagens';
    }

    public function rules()
    {
        return [
            [['id_equipa', 'iduser', 'mensagem'], 'required'],
            [['id_equipa', 'iduser'], 'integer'],
            [['mensagem'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'iduser']);
    }
}
